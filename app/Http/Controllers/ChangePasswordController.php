<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\User;

class ChangePasswordController extends Controller
{
    public function process(Request $request){
    	return $this->getPasswordResetTableRow($request)->count() > 0 ? $this->changePassword($request) : $this->tokenInvalid();
    }
    private function getPasswordResetTableRow($request){
    	return DB::table('password_resets')->where([
    		'email' => $request->email,
    		'token' => $request->resetToken
    	]);
    }
    private function changePassword($request){
    	$user = User::whereEmail($request->email)->first();
    	$user->update([
    		'password' => $request->password
    	]);
    	$this->getPasswordResetTableRow($request)->delete();
    	return response()->json([
    		'data' => 'Password updated successfully.'
    	], Response::HTTP_CREATED);
    }
    private function tokenInvalid(){
    	return response()->json([
    		'error' => 'Token or Email is incorrect.'
    	], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
