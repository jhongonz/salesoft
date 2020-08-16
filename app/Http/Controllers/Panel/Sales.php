<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class Sales extends Controller
{
	public function __construct()
	{
		Self::middleware('auth')->except(['sandbox']);
	}

	public function index()
	{
		$invoice = session('invoice');
		if (!isset($invoice['salekey']))
		{
			session()->forget('invoice');
			session()->save();

			$invoice['salekey'] = Str::random(45); 
			session(['invoice'=>$invoice]);
		}

		//dd(session('invoice'));
		$idClient = (isset($invoice['customer'])) ? $invoice['customer']->id : 0;
		$name = (isset($invoice['customer'])) ? $invoice['customer']->name.' '.$invoice['customer']->lastname : null;

		$details = '';
		if (isset($invoice['detail']))
		{
			foreach($invoice['detail'] as $item)
			{
				$details .= '<tr>
					<td>'.$item->lot.'</td>
					<td>'.$item->name.'</td>
					<td>'.$item->code.'</td>
					<td>'.$item->description.'</td>
					<td>'.$item->amount.'</td>
	            </tr>';
			}
		}
		else
		{
			$details .= '<tr>
					<td colspan="5">No hay items cargados</td>
	            </tr>';
		}

		$invoice['total'] = (isset($invoice['total'])) ? $invoice['total'] : 0;
		$rent = $invoice['total'] * (IGV_PORCENTAGE / 100);
		//dd($rent);
		$total = $invoice['total'] + $rent;
		$summary = '<table class="table">
			<tr>
				<th style="width:50%">Subtotal:</th>
				<td>'.toDecimal($invoice['total']).'</td>
			</tr>
			<tr>
				<th>IGV ('.IGV_PORCENTAGE.' %)</th>
				<td>'.toDecimal($rent).'</td>
			</tr>
			<tr>
				<th>Total:</th>
				<td>'.toDecimal($total).'</td>
			</tr>
		</table>';

		$today = \Carbon\Carbon::now(TIMEZONE)->format('d/m/Y');
		return view('panel.sale.point')
			->with('today',$today)
			->with('idClient',$idClient)
			->with('clientName',$name)
			->with('details',$details)
			->with('summary',$summary)
			->render();
	}

	public function saveCustomer(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'idCustomer'=>'required',
		]);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$customer = \App\Models\Customer::find($request->input('idCustomer'),['cus_id','cus_document_type','cus_document_number','cus_name','cus_lastname']);
		
		if (!is_null($customer))
		{
			$registry = new \stdClass();
			$registry->id = $customer->cus_id;
			$registry->type = $customer->cus_document_type;
			$registry->number = $customer->cus_document_number;
			$registry->name = $customer->cus_name;
			$registry->lastname = $customer->cus_lastname;

			$invoice = session('invoice');
			$invoice['customer'] = $registry;
			session(['invoice'=>$invoice]);

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL]);
	}

	public function saveProduct(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'idProduct'=>'required',
			'lot'=>'required'
		]);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$product = \App\Models\Product::find($request->input('idProduct'));
		
		if (!is_null($product))
		{
			$price = ($product->pro_price_cut > 0) ? $product->pro_price_cut : $product->pro_price;
			$amount = $price * $request->input('lot');

			$registry = new \stdClass();
			$registry->id = $product->pro_id;
			$registry->code = $product->pro_code;
			$registry->name = $product->pro_name;
			$registry->description = $product->pro_description;
			$registry->price = $price;
			$registry->lot = $request->input('lot');
			$registry->amount = toDecimal($amount);

			$invoice = session('invoice');
			$detail = (isset($invoice['detail'])) ? $invoice['detail'] : array();

			$isNew = true;
			$total = 0;
			if (count($detail) > 0)
			{
				foreach($detail as $item)
				{
					if ($item->id == $registry->id)
					{
						$total = $total + $registry->amount;
						$isNew = false;
						$item->lot = $registry->lot;
						$item->amount = $registry->amount;
						$item->price = $registry->price;
					}
					else
					{
						$total = $total + $item->amount;
					}
				}
			}

			if ($isNew)
			{
				$total = $total + $registry->amount;
				$detail[] = $registry;
			}

			$invoice['detail'] = $detail;
			$invoice['total'] = toDecimal($total);
			session(['invoice'=>$invoice]);

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL]);
	}

	public function process(Request $request)
	{
		$invoice = session('invoice');

		$sale = new \App\Models\Sale;
		$sale->sale__cus_id = $invoice['customer']->id;
		$sale->sale__user_id = session('user')->user_id;
		$sale->sale_subtotal = 0;
		$sale->sale_total = 0;
		$sale->sale_state = ST_ACTIVE;
		$sale->save();

		if ($sale->sale_id > 0)
		{
			$generalSubtotal = 0;
			foreach($invoice['detail'] as $item)
			{
				$subtotal = $item->price * $item->lot;
				$generalSubtotal = $generalSubtotal + $subtotal;

				$detail = new \App\Models\SaleDetail;
				$detail->det__sale_id = $sale->sale_id;
				$detail->det__pro_id = $item->id;
				$detail->det_lot = $item->lot;
				$detail->det_price = toDecimal($item->price);
				$detail->det_subtotal = toDecimal($subtotal);
				$detail->det_state = ST_ACTIVE;
				$detail->save();
			}

			$rest = $generalSubtotal * (IGV_PORCENTAGE / 100);
			$generalTotal = $generalSubtotal + $rest;

			$sale->sale_subtotal = toDecimal($generalSubtotal);
			$sale->sale_total = toDecimal($generalTotal);
			$sale->save();

			session()->forget('invoice');
			session()->save();	
		}

		return response()->json(['status'=>STATUS_OK]);
	}

	public function records()
	{
		return view('panel.sale.index')->render();
	}

	public function getMainList(Request $request)
	{
		$select = \DB::table(TB_SALES)
			->join(TB_CUSTOMERS,TB_CUSTOMERS.'.cus_id','=',TB_SALES.'.sale__cus_id')
			->where(TB_SALES.'.sale_state','>',ST_DELETE);

		if ($request->input('q') != '')
		{
			$select->where('cus_search','like','%'.$request->input('q').'%');
		}

		$sales = $select->get();
		$response = Datatables::of($sales)
			->addColumn('state', function ($item){

				if ($item->sale_state == ST_ACTIVE)
				{
					$state = '<span class="label label-success">Activo</span>';
				}
				else if ($item->sale_state == ST_INACTIVE)
				{
					$state = '<span class="label label-danger">Bloqueado</span>';
				}
				else if ($item->sale_state == ST_NEW)
				{
					$state = '<span class="label label-primary">Nuevo</span>'; 
				}

				return $state;
			})
			->addColumn('number',function($item){

				$number = 1000 + $item->sale_id;
				return $number;
			})
			->addColumn('subtotal',function($item){

				return toDecimal($item->sale_subtotal);
			})
			->addColumn('total',function($item){

				return toDecimal($item->sale_total);
			})
			->addColumn('name',function($item){

				$name = $item->cus_name.' '.$item->cus_lastname;
				return $name;
			})
			->addColumn('tool',function($item){

			$tool = '<div class="btn-group">
				<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
					<span class="sr-only">Toggle Dropdown</span>
				    <i class="fa fa-reorder"></i>
				</button>';

			$tool .= '<ul class="dropdown-menu" role="menu">';
			$tool .= '<li><a class="ajxDeleteSale" href="#" data-idsale='.$item->sale_id.'><i class="fa fa-fw fa-trash" style="color:crimson;"></i> Eliminar</a></li>';
			
			if ($item->sale_state == ST_ACTIVE)
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idsale='.$item->sale_id.'><i class="fa fa-fw fa-minus-circle" style="color:crimson;"></i> Suspender</a></li>';
			}
			else if (in_array($item->sale_state,[ST_INACTIVE,ST_NEW]))
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idsale='.$item->sale_id.'><i class="fa fa-fw fa-check-square" style="color:limegreen;"></i> Activar</a></li>';
			}

			$tool .= '</ul>';
			$tool .= '</div>';

			return $tool;
		})
		->escapeColumns([])
		->make(true);

		return $response;
	}

	public function changeState(Request $request)
	{
		$sale = \App\Models\Sale::where('sale_id',$request->input('idSale'))
			->where('sale_state','>',ST_DELETE)
			->first(['sale_id','sale_state']);

		if (!is_null($sale))
		{
			if (in_array($sale->sale_state,[ST_INACTIVE,ST_NEW]))
			{
				$state = ST_ACTIVE;
				$msg = 'Registro activado';
			}
			else if($sale->sale_state == ST_ACTIVE)
			{
				$state = ST_INACTIVE;
				$msg = 'Registro suspendido';
			}

			$sale->sale_state = $state;
			$sale->save();

			return response()->json(['status'=>STATUS_OK,'msg'=>$msg]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function delete(Request $request)
	{
		$sale = \App\Models\Sale::where('sale_id',$request->input('idSale'))
			->first(['sale_id','sale_state']);

		if (!is_null($sale))
		{
			$sale->sale_state = ST_DELETE;
			$sale->save();

			\App\Models\SaleDetail::where('det__sale_id',$sale->sale_id)->update(['det_state'=>ST_DELETE]);

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function sandbox(Request $request)
	{
		$sales = \DB::table(TB_SALES)
			->join(TB_CUSTOMERS,TB_CUSTOMERS.'.cus_id','=',TB_SALES.'.sale__cus_id')
			->where(TB_SALES.'.sale_state','>',ST_DELETE)
			->get();

		$salesResponse = array();
		foreach($sales as $sale)
		{
			$registry = new \stdClass();
			$registry->id = $sale->sale_id;
			$registry->number = (1000+$sale->sale_id);
			$registry->subtotal = toDecimal($sale->sale_subtotal);
			$registry->total = toDecimal($sale->sale_total);

			$detail = \DB::table(TB_SALES_DETAIL)
				->join(TB_PRODUCT,TB_PRODUCT.'.pro_id','=',TB_SALES_DETAIL.'.det__pro_id')
				->where(TB_SALES_DETAIL.'.det__sale_id',$sale->sale_id)
				->where(TB_SALES_DETAIL.'.det_state','>',ST_DELETE)
				->get();

			$details = array();
			foreach($detail as $item)
			{
				$product = new \stdClass();
				$product->id = $item->det_id;
				$product->idProduct = $item->det__pro_id;
				$product->code = $item->pro_code;
				$product->name = $item->pro_name;
				$product->price = toDecimal($item->pro_price);
				$product->priceOffer = toDecimal($item->pro_price_cut);
				$product->lot = $item->det_lot;

				$details[] = $product;
			}
			$registry->details = $details;

			$salesResponse[] = $registry;
		}

		return response()->json(['data'=>['sales'=>$salesResponse]], 200);
	}
}
