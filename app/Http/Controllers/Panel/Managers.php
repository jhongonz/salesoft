<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class Managers extends Controller
{
	public function __construct()
	{
		Self::middleware('auth');
	}

	public function index()
	{
		return view('panel.manager.index')->render();
	}

	public function getMainList(Request $request)
	{
		$select = \DB::table(TB_MANAGERS)
			->join(TB_USERS,TB_USERS.'.user__registry_id','=',TB_MANAGERS.'.admin_id')
			->where(TB_MANAGERS.'.admin_state','>',ST_DELETE);

		if ($request->input('q') != '')
		{
			$select->where(TB_MANAGERS.'.admin_search','like','%'.$request->input('q').'%');
		}

		$managers = $select->get([
			'admin_id','admin_state','admin_name','admin_lastname','admin_email',
			'user_id','user_state','user__pro_id','user_login'
		]);
		$response = Datatables::of($managers)
		->addColumn('state', function ($item){

				if ($item->admin_state == ST_ACTIVE)
				{
					$state = '<span class="label label-success">Activo</span>';
				}
				else if ($item->admin_state == ST_INACTIVE)
				{
					$state = '<span class="label label-danger">Bloqueado</span>';
				}
				else if ($item->admin_state == ST_NEW)
				{
					$state = '<span class="label label-primary">Nuevo</span>'; 
				}

				return $state;
			})
		->addColumn('tool',function($item){

			$tool = '<div class="btn-group">
				<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
					<span class="sr-only">Toggle Dropdown</span>
				    <i class="fa fa-reorder"></i>
				</button>';

			$tool .= '<ul class="dropdown-menu" role="menu">';
			$tool .= '<li><a class="ajxEditUser" href="#" data-idadmin='.$item->admin_id.'><i class="fa fa-fw fa-pencil-square-o" style="color:dodgerblue;"></i> Editar</a></li>';
			$tool .= '<li><a class="ajxDeleteUser" href="#" data-idadmin='.$item->admin_id.'><i class="fa fa-fw fa-trash" style="color:crimson;"></i> Eliminar</a></li>';
			
			if ($item->admin_state == ST_ACTIVE)
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idadmin='.$item->admin_id.'><i class="fa fa-fw fa-minus-circle" style="color:crimson;"></i> Suspender</a></li>';
			}
			else if (in_array($item->admin_state,[ST_INACTIVE,ST_NEW]))
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idadmin='.$item->admin_id.'><i class="fa fa-fw fa-check-square" style="color:limegreen;"></i> Activar</a></li>';
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
		$manager = \App\Models\Manager::where('admin_id',$request->input('idAdmin'))
			->where('admin_state','>',ST_DELETE)
			->first(['admin_id','admin_state']);

		if (!is_null($manager))
		{
			if (in_array($manager->admin_state,[ST_INACTIVE,ST_NEW]))
			{
				$state = ST_ACTIVE;
				$msg = 'Registro activado';
			}
			else if($manager->admin_state == ST_ACTIVE)
			{
				$state = ST_INACTIVE;
				$msg = 'Registro suspendido';
			}

			$manager->admin_state = $state;
			$manager->save();

			\App\Models\User::where('user__registry_id',$manager->admin_id)->update(['user_state'=>$state]);

			return response()->json(['status'=>STATUS_OK,'msg'=>$msg]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function delete(Request $request)
	{
		$manager = \App\Models\Manager::where('admin_id',$request->input('idAdmin'))
			->first(['admin_id','admin_state']);

		if (!is_null($manager))
		{
			$manager->admin_state = ST_DELETE;
			$manager->save();

			$user = \App\Models\User::where('user__registry_id',$manager->admin_id)
				->first(['user_id','user_state']);

			$user->user_state = ST_DELETE;
			$user->save();

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function getRegistry(Request $request)
	{
		$idAdmin = $request->input('idAdmin');

		$manager = \DB::table(TB_MANAGERS)
			->join(TB_USERS,TB_USERS.'.user__registry_id','=',TB_MANAGERS.'.admin_id')
			->where(TB_MANAGERS.'.admin_id',$idAdmin)
			->first([
				'admin_id','admin_identifier_type','admin_identifier','admin_name','admin_lastname','admin_address','admin_phone','admin_email',
				'user_id','user__registry_id','user_login'
			]);

		$view = view('panel.manager.registry')
			->with('idAdmin',$idAdmin)
			->with('manager',$manager)
			->render();

		return response()->json(['status'=>STATUS_OK,'html'=>$view]);
	}

	public function store(Request $request)
	{
		$rules = [
			'nombres'=>'required',
			'apellidos'=>'required',
			'identificacion'=>'required',
			'tipoDocumento'=>'required',
			'login'=>['required',Rule::unique(TB_USERS,'user_login')->ignore($request->input('idUsuario'),'user_id')->where(function($query) {
				return $query->where('user_state','>',ST_DELETE);
			})]
		];
		if ($request->input('idUsuario') == '')
		{
			$rules['password'] = 'required|min:7';
		}
		$validator = \Validator::make($request->all(),$rules);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$manager = \App\Models\Manager::where('admin_id',$request->input('idUsuario'))
			->where('admin_state','>',ST_DELETE)
			->first();

		if (is_null($manager))
		{
			$manager = new \App\Models\Manager;
			$manager->admin_state = ST_NEW;
		}

		$dataSearch = [
			$request->input('identificacion'),
			$request->input('nombres'),
			$request->input('direccion'),
			$request->input('telefono'),
			$request->input('email')
		];
		$manager->admin_identifier_type = $request->input('tipoDocumento');
		$manager->admin_identifier = $request->input('identificacion');
		$manager->admin_name = $request->input('nombres');
		$manager->admin_lastname = $request->input('apellidos');
		$manager->admin_address = $request->input('direccion');
		$manager->admin_phone = $request->input('telefono');
		$manager->admin_email = $request->input('email');
		$manager->admin_search = implode(' ',$dataSearch);
		$manager->save();

		if ($manager->admin_id > 0)
		{
			$user = \App\Models\User::where('user__registry_id',$manager->admin_id)
				->where('user_state','>',ST_DELETE)
				->first();

			if (is_null($user))
			{
				$user = new \App\Models\User;
				$user->user__registry_id = $manager->admin_id;
				$user->user_state = ST_NEW;
				$user->user__pro_id = 18; //ROOT PROFILE, FOR THIS TEST IS THE ONLY ONE PROFILE ACTIVED
			}

			if ($request->input('password') != '')
			{
				$user->password = \Hash::make($request->input('password'));
			}

			$user->user_login = $request->input('login');
			$user->save();
		}

		return response()->json(['status'=>STATUS_OK]);
	}
}
