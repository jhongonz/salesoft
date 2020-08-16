<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class Categories extends Controller
{
	public function __construct()
	{
		Self::middleware('auth');
	}

	public function index()
	{
		return view('panel.category.index')->render();
	}

	public function getMainList(Request $request)
	{
		$select = \App\Models\Category::where('cate_state','>',ST_DELETE);

		if ($request->input('q') != '')
		{
			$select->where('cate_name','like','%'.$request->input('q').'%');
		}

		$categories = $select->get();
		$response = Datatables::of($categories)
			->addColumn('state', function ($item){

				if ($item->cate_state == ST_ACTIVE)
				{
					$state = '<span class="label label-success">Activo</span>';
				}
				else if ($item->cate_state == ST_INACTIVE)
				{
					$state = '<span class="label label-danger">Bloqueado</span>';
				}
				else if ($item->cate_state == ST_NEW)
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
			$tool .= '<li><a class="ajxEditCategory" href="#" data-idcategory='.$item->cate_id.'><i class="fa fa-fw fa-pencil-square-o" style="color:dodgerblue;"></i> Editar</a></li>';
			$tool .= '<li><a class="ajxDeleteCategory" href="#" data-idcategory='.$item->cate_id.'><i class="fa fa-fw fa-trash" style="color:crimson;"></i> Eliminar</a></li>';
			
			if ($item->cate_state == ST_ACTIVE)
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idcategory='.$item->cate_id.'><i class="fa fa-fw fa-minus-circle" style="color:crimson;"></i> Suspender</a></li>';
			}
			else if (in_array($item->cate_state,[ST_INACTIVE,ST_NEW]))
			{
				$tool .= '<li><a class="ajxChangeStatus" href="#" data-idcategory='.$item->cate_id.'><i class="fa fa-fw fa-check-square" style="color:limegreen;"></i> Activar</a></li>';
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
		$category = \App\Models\Category::where('cate_id',$request->input('idCategory'))
			->where('cate_state','>',ST_DELETE)
			->first(['cate_id','cate_state']);

		if (!is_null($category))
		{
			if (in_array($category->cate_state,[ST_INACTIVE,ST_NEW]))
			{
				$state = ST_ACTIVE;
				$msg = 'Registro activado';
			}
			else if($category->cate_state == ST_ACTIVE)
			{
				$state = ST_INACTIVE;
				$msg = 'Registro suspendido';
			}

			$category->cate_state = $state;
			$category->save();

			return response()->json(['status'=>STATUS_OK,'msg'=>$msg]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function delete(Request $request)
	{
		$category = \App\Models\Category::where('cate_id',$request->input('idCategory'))
			->first(['cate_id','cate_state']);

		if (!is_null($category))
		{
			$category->cate_state = ST_DELETE;
			$category->save();

			return response()->json(['status'=>STATUS_OK]);
		}

		return response()->json(['status'=>STATUS_FAIL,'msg'=>'Registro no encontrado']);
	}

	public function getRegistry(Request $request)
	{
		$idCategory = $request->input('idCategory');

		$category = \App\Models\Category::where('cate_id',$request->input('idCategory'))
			->where('cate_state','>',ST_DELETE)
			->first();

		$view = view('panel.category.registry')
			->with('idCategory',$idCategory)
			->with('category',$category)
			->render();

		return response()->json(['status'=>STATUS_OK,'html'=>$view]);
	}

	public function store(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'name'=>'required'
		]);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$category = \App\Models\Category::where('cate_id',$request->input('idCategory'))
			->where('cate_state','>',ST_DELETE)
			->first();

		if (is_null($category))
		{
			$category = new \App\Models\Category;
			$category->cate_state = ST_ACTIVE;
		}

		$category->cate_name = $request->input('name');
		$category->save();

		return response()->json(['status'=>STATUS_OK]);
	}
}
