<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
	public function __construct()
	{
		Self::middleware('guest')->only(['index']);
		Self::middleware('auth')->only('home');
	}

	public function sandbox()
	{
		dd('Here in login');
	}

	public function index()
	{
		return view('panel.index')->render();
	}

	public function authenticate(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'user_login'=>'required|string',
			'password'=>'required|string'
		],[
			'required'=> 'Obligatorio'
		]);

		if ($validator->fails())
		{
			return response()->json(['status'=>STATUS_FAIL,'errors'=>$validator->errors()]);
		}

		$dataUser = Self::validateAccess($request);
		if ($dataUser)
		{
			$credentials = [
				'user_login'=>$dataUser->user_login,
				'password'=>$request->post('password'),
				'user_id'=>$dataUser->user_id
			];

			if (Auth::attempt($credentials,false))
			{
				//session()->flush();
				session()->regenerate();
				session(['user'=>$dataUser]);

				return response()->json([
		            'status'=>STATUS_OK,
		            'msg'=>'Usuario autenticado'
		        ]);
			}
		}

		return response()->json([
            'status'=>STATUS_FAIL,
            'msg'=>'Usuario no autenticado'
        ]);
	}

	public function validateAccess(Request $request)
	{
		$user = \DB::table(TB_USERS)
			->join(TB_MANAGERS,TB_USERS.'.user__registry_id','=',TB_MANAGERS.'.admin_id')
			->where(TB_USERS.'.user_login','=',$request->post('user_login'))
			->where(TB_MANAGERS.'.admin_state','>',ST_DELETE)
			->where(TB_USERS.'.user_state','=',ST_ACTIVE)
			->orderBy(TB_USERS.'.user_id','DESC')
			->first();

		if ($user)
		{
			return $user;
		}

		return false;
	}

	public function home()
	{
		return view('panel.home')->render();
	}

	public function logout(Request $request)
	{
		Auth::logout();
		//$request->session()->invalidate();
		session()->flush();

		return response()->json([
			'status'=>STATUS_OK,
			'msg'=>'Sesion cerrada con exito.!'
		]);
	}
}
