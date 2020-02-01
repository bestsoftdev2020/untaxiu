<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class MdriverController extends Controller
{
    //
    public function index() {

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $reference = $database->getReference('drivers');
        $lists = $reference->getValue();
        return view('admin/driver', ['lists' => $lists]);
    }

    public function approve(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $uids = explode(',',$data['lists']) ;
        for($i = 0; $i < count($uids) ; $i++)
        {
            $uid = $uids[$i];
            $database->getReference('drivers/'.$uid.'/isApproved') // this is the root reference
                    ->set(strval('true'));
        }

        $reference = $database->getReference('drivers');
        $lists = $reference->getValue();
        return view('admin/driver', ['lists' => $lists]);
    }

    public function suspend(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $uids = explode(',',$data['lists']) ;
        for($i = 0; $i < count($uids) ; $i++)
        {
            $uid = $uids[$i];
            $database->getReference('drivers/'.$uid.'/isApproved') // this is the root reference
                    ->set(strval('false'));
        }

        $reference = $database->getReference('drivers');
        $lists = $reference->getValue();
        return view('admin/driver', ['lists' => $lists]);
    }
    
    public function delete(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $uids = explode(',',$data['lists']) ;
        for($i = 0; $i < count($uids) ; $i++)
        {
            $uid = $uids[$i];
            $database->getReference('drivers/'.$uid) // this is the root reference
                    ->remove();
            $auth = $firebase->getAuth();
            $auth->deleteUser($uid) ;
        }

        $reference = $database->getReference('drivers');
        $lists = $reference->getValue();
        return view('admin/driver', ['lists' => $lists]);
    }
}
