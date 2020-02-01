<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class MloginController extends Controller
{
    public function login(Request $request) {

        if(session('musername') != null)
            return app(\App\Http\Controllers\MindexController::class)->index();

        $input = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();

        $auth = $firebase->getAuth();
        try {
            if((isset($input['username'])) && ($input['username'] == 'admin')) {
                $users = $auth->verifyPassword('admin@email.com', $input['pass']);
                session(['memail' => $users->email]) ;
                session(['mrealname' => $users->displayName]) ;
                session(['musername' => $input['username']]) ;
                session(['mpassword' => $input['pass']]) ;
                session(['mmobilenumber' => $users->phoneNumber]) ;
                session(['mid' => $users->uid]) ;
                return app(\App\Http\Controllers\MindexController::class)->index();
            }
            else
                return view('admin/login') ;        
        } catch (Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            return view('admin/login') ;        
        }    
    }

    public function logout() {
        session(['memail' => null]) ;
        session(['mrealname' => null]) ;
        session(['musername' => null]) ;
        session(['mpassword' => null]) ;
        session(['mmobilenumber' => null]) ;
        session(['mid' => null]) ;
        return view('admin/login') ;        
    }

    public function lock(Request $request) {
        $input = $request->all() ;
        if($input) 
            if($input['lockpass'] == session('mpassword'))
                return app(\App\Http\Controllers\MindexController::class)->index();
        return view('admin/lock') ;
    }

    public function profile(Request $request) {
        return view('admin/profile',['success' => '2','psuccess' => '2']) ;
    }

    public function changeaccount(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();

        $auth = $firebase->getAuth();

        $uid = session('mid');

        $properties = [
            'displayName' => $data['firstName'],
            'phoneNumber' => $data['mobileNumber']
        ];

        try {
            $updatedUser = $auth->updateUser($uid, $properties);
            session(['mrealname' => $data['firstName']]) ;
            session(['mmobilenumber' => $data['mobileNumber']]) ;
            $users = $auth->getUser($uid) ;
            return view('admin/profile',['success' => '1', 'psuccess' => '2']) ;
        } catch (Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            return view('admin/profile',['success' => '0', 'psuccess' => '2']) ;
        }            
    }

    public function changepass(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();

        $auth = $firebase->getAuth();

        $uid = session('mid');

        try {
            $updatedUser = $auth->changeUserPassword($uid, $data['newPass']);
            session(['mpassword' => $data['newPass']]) ;
            return view('admin/profile',['psuccess' => '1', 'success' => '2']) ;
        } catch (Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            return view('admin/profile',['psuccess' => '0', 'success' => '2']) ;
        }            
    }
}
