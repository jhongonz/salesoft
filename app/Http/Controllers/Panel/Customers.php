<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class Customers extends Controller
{
	public function __construct()
	{
		Self::middleware('auth');
	}

	public function index()
	{
		return view('panel.customer.index')->render();
	}

	public function getMainList(Request $request)
	{
		$select = \App\Models\Customer::where('cus_state','>',ST_DELETE);

		if ($request->input('q') != '')
		{
			$select->where('cus_search','like','%'.$request->input('q').'%');
		}

		$customers = $select->get();
		$response = Datatables::of($customers)
			->addColumn('state', function ($item){

				if ($item->cus_state == ST_ACTIVE)
				{
					$state = '<span class="label label-success">Activo</span>';
				}
				else if ($item->cus_state == ST_INACTIVE)
				{
					$state = '<span class="label label-danger">Bloqueado</span>';
				}
				else if ($item->cus_state == ST_NEW)
				{
					$state = '<span class="label label-primary">Nuevo</span>'; 
				}

				return $state;
			})
			->addColumn('typeDocument', function ($item){

				$document = TYPE_DOCUMENT[$item->cus_document_type];

				return $document;
			})
			->addColumn('tool',function($item){

			$tool = '<div class="btn-group">
				<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
					<span class="sr-only">Toggle Dropdown</span>
				    <i class="fa fa-reorder"></i>
				</button>';

			$tool .= '<ul class="dropdown-menu" role="menu">';
			$tool .= '<li><a class="ajxEditCustomer" href="#" data-idcustomer='.$item->cus_id.'><i class="fa fa-fw fa-pencil-square-o" style="color:dodgerblue;"></i> Editar</a></li>';
			$tool .= '<li><a class="ajxDeleteCustomer" href="#" data-idcustomer='.$item->cus_id.'><i class="fa fa-fw fa-trash" style="color:crimson;"></i> Eliminar</a></li>';
			
			if ($item->cus_state == ST_ACTIVE)
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idcustomer='.$item->cus_id.'><i class="fa fa-fw fa-minus-circle" style="color:crimson;"></i> Suspender</a></li>';
			}
			else if (in_array($item->cus_state,[ST_INACTIVE,ST_NEW]))
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idcustomer='.$item->cus_id.'><i class="fa fa-fw fa-check-square" style="color:limegreen;"></i> Activar</a></li>';
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
		$customer = \App\Models\Customer::where('cus_id',$request->input('idCustomer'))
			->where('cus_state','>',ST_DELETE)
			->first(['cus_id','cus_state']);

		if (!is_null($customer))
		{
			if (in_array($customer->cus_state,[ST_INACTIVE,ST_NEW]))
			{
				$state = ST_ACTIVE;
				$msg = 'Registro activado';
			}
			else if($customer->cus_state == ST_ACTIVE)
			{
				$state = ST_INACTIVE;
				$msg = 'Registro suspendido';
			}

			$customer->cus_state = $state;
			$customer->save();

			return response()->json(['status'=>STATUS_OK,'msg'=>$msg]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function delete(Request $request)
	{
		$customer = \App\Models\Customer::where('cus_id',$request->input('idCustomer'))
			->first(['cus_id','cus_state']);

		if (!is_null($customer))
		{
			$customer->cus_state = ST_DELETE;
			$customer->save();

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function getRegistry(Request $request)
	{
		$idCustomer = $request->input('idCustomer');

		$customer = \App\Models\Customer::where('cus_id',$request->input('idCustomer'))
			->where('cus_state','>',ST_DELETE)
			->first();

		if (!is_null($customer))
		{
			$customer->cus_birthdate = ($customer->cus_birthdate != '') ? (new \DateTime($customer->cus_birthdate))->format('d/m/Y') : '';
		}

		$view = view('panel.customer.registry')
			->with('idCustomer',$idCustomer)
			->with('customer',$customer)
			->render();

		return response()->json(['status'=>STATUS_OK,'html'=>$view]);
	}

	public function store(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'nombres'=>'required',
			'apellidos'=>'required',
			'identificacion'=>'required',
			'tipoDocumento'=>'required',
			'gender'=>'required',
			'birthdate'=>'required',
			'email'=>'required',
			'telefono'=>'required'
		]);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$customer = \App\Models\Customer::where('cus_id',$request->input('idCustomer'))
			->where('cus_state','>',ST_DELETE)
			->first();

		if (is_null($customer))
		{
			$customer = new \App\Models\Customer;
			$customer->cus_state = ST_ACTIVE;
		}

		$birthdate = ($request->input('birthdate') != '') ? \Carbon\Carbon::createFromFormat('d/m/Y',$request->input('birthdate'))->format(FORMAT_DATE_DATABASE) : '';
		$dataSearch = [
			$request->input('identificacion'),
			$request->input('nombres'),
			$request->input('direccion'),
			$request->input('telefono'),
			$request->input('email')
		];
		$customer->cus_document_type = $request->input('tipoDocumento');
		$customer->cus_document_number = $request->input('identificacion');
		$customer->cus_name = $request->input('nombres');
		$customer->cus_lastname = $request->input('apellidos');
		$customer->cus_address = $request->input('direccion');
		$customer->cus_phone = $request->input('telefono');
		$customer->cus_email = $request->input('email');
		$customer->cus_gender = $request->input('gender');
		$customer->cus_birthdate = $birthdate;
		$customer->cus_search = implode(' ',$dataSearch);
		$customer->save();

		return response()->json(['status'=>STATUS_OK]);
	}

	public function getCustomer(Request $request)
	{
		$customers = \App\Models\Customer::where('cus_state',ST_ACTIVE)->get(['cus_id','cus_document_type','cus_document_number','cus_name','cus_lastname']);

		$view = view('panel.customer.get-customer')
			->with('customers',$customers)
			->render();

		return response()->json(['status'=>STATUS_OK,'html'=>$view]);
	}
}
