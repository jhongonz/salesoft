<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class Products extends Controller
{
	public function __construct()
	{
		Self::middleware('auth');
	}

	public function index()
	{
		return view('panel.product.index')->render();
	}

	public function getMainList(Request $request)
	{
		$select = \DB::table(TB_PRODUCT)
			->join(TB_CATEGORIES,TB_CATEGORIES.'.cate_id','=',TB_PRODUCT.'.pro__cate_id')
			->where(TB_PRODUCT.'.pro_state','>',ST_DELETE);

		if ($request->input('q') != '')
		{
			$select->where('pro_search','like','%'.$request->input('q').'%');
		}

		$products = $select->get();
		$response = Datatables::of($products)
			->addColumn('state', function ($item){

				if ($item->pro_state == ST_ACTIVE)
				{
					$state = '<span class="label label-success">Activo</span>';
				}
				else if ($item->pro_state == ST_INACTIVE)
				{
					$state = '<span class="label label-danger">Bloqueado</span>';
				}
				else if ($item->pro_state == ST_NEW)
				{
					$state = '<span class="label label-primary">Nuevo</span>'; 
				}

				return $state;
			})
			->addColumn('price', function ($item){

				$price = toDecimal($item->pro_price);
				return $price;
			})
			->addColumn('priceCut', function ($item){

				$priceCut = toDecimal($item->pro_price_cut);
				return $priceCut;
			})
			->addColumn('tool',function($item){

			$tool = '<div class="btn-group">
				<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
					<span class="sr-only">Toggle Dropdown</span>
				    <i class="fa fa-reorder"></i>
				</button>';

			$tool .= '<ul class="dropdown-menu" role="menu">';
			$tool .= '<li><a class="ajxEditProduct" href="#" data-idproduct='.$item->pro_id.'><i class="fa fa-fw fa-pencil-square-o" style="color:dodgerblue;"></i> Editar</a></li>';
			$tool .= '<li><a class="ajxDeleteProduct" href="#" data-idproduct='.$item->pro_id.'><i class="fa fa-fw fa-trash" style="color:crimson;"></i> Eliminar</a></li>';
			
			if ($item->pro_state == ST_ACTIVE)
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idproduct='.$item->pro_id.'><i class="fa fa-fw fa-minus-circle" style="color:crimson;"></i> Suspender</a></li>';
			}
			else if (in_array($item->pro_state,[ST_INACTIVE,ST_NEW]))
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idproduct='.$item->pro_id.'><i class="fa fa-fw fa-check-square" style="color:limegreen;"></i> Activar</a></li>';
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
		$product = \App\Models\Product::where('pro_id',$request->input('idProduct'))
			->where('pro_state','>',ST_DELETE)
			->first(['pro_id','pro_state']);

		if (!is_null($product))
		{
			if (in_array($product->pro_state,[ST_INACTIVE,ST_NEW]))
			{
				$state = ST_ACTIVE;
				$msg = 'Registro activado';
			}
			else if($product->pro_state == ST_ACTIVE)
			{
				$state = ST_INACTIVE;
				$msg = 'Registro suspendido';
			}

			$product->pro_state = $state;
			$product->save();

			return response()->json(['status'=>STATUS_OK,'msg'=>$msg]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function delete(Request $request)
	{
		$product = \App\Models\Product::where('pro_id',$request->input('idProduct'))
			->first(['pro_id','pro_state']);

		if (!is_null($product))
		{
			$product->pro_state = ST_DELETE;
			$product->save();

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function getRegistry(Request $request)
	{
		$idProduct = $request->input('idProduct');

		$product = \App\Models\Product::where('pro_id',$request->input('idProduct'))
			->where('pro_state','>',ST_DELETE)
			->first();

		if (!is_null($product))
		{
			$product->pro_price = toDecimal($product->pro_price);
			$product->pro_price_cut = toDecimal($product->pro_price_cut);
		}

		$categories = \App\Models\Category::where('cate_state','>',ST_DELETE)->get();

		$view = view('panel.product.registry')
			->with('idProduct',$idProduct)
			->with('product',$product)
			->with('categories',$categories)
			->render();

		return response()->json(['status'=>STATUS_OK,'html'=>$view]);
	}

	public function store(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'name'=>'required',
			'description'=>'required',
			'price'=>'required',
			'category'=>'required',
			'code'=>['required',Rule::unique(TB_PRODUCT,'pro_code')->ignore($request->input('idProduct'),'pro_id')->where(function($query) {
				return $query->where('pro_state','>',ST_DELETE);
			})]
		]);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$product = \App\Models\Product::where('pro_id',$request->input('idProduct'))
			->where('pro_state','>',ST_DELETE)
			->first();

		if (is_null($product))
		{
			$product = new \App\Models\Product;
			$product->pro_state = ST_ACTIVE;
		}

		$search = [
			$request->input('code'),
			$request->input('name'),
			$request->input('description')
		];
		$product->pro_name = $request->input('name');
		$product->pro_description = $request->input('description');
		$product->pro_code = $request->input('code');
		$product->pro__cate_id = $request->input('category');
		$product->pro_price = toDecimal($request->input('price'));
		$product->pro_price_cut = toDecimal($request->input('priceCut'));
		$product->pro_search = implode(' ',$search);
		$product->save();

		return response()->json(['status'=>STATUS_OK]);
	}

	public function getProducts(Request $request)
	{
		$products = \App\Models\Product::where('pro_state',ST_ACTIVE)->get(['pro_id','pro_code','pro_name','pro_description']);

		$view = view('panel.product.get-products')
			->with('products',$products)
			->render();

		return response()->json(['status'=>STATUS_OK,'html'=>$view]);
	}
}
