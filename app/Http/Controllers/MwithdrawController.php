<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class MwithdrawController extends Controller
{
    //
    public function index() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $reference = $database->getReference('transactions');
        $lists = $reference->getValue();
        foreach($lists as $key=>$list) {
            $fromtime = date("F j, Y",substr($list['createdAt'],0,10));
            $lists[$key] += [ "date" => $fromtime ];
        }
        return view('admin/withdraw', ['lists' => $lists]);
    }   

    public function send(Request $request) {
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
            $database->getReference('transactions/'.$uid.'/status') // this is the root reference
                    ->set(strval('SUCCESS'));
            $drvRef = $database->getReference('transactions/'.$uid.'/userId') ;
            $drvId = $drvRef->getValue() ;

            $amountref = $database->getReference('transactions/'.$uid.'/amount') ;
            $amount = $amountref->getValue() ;
            
            $balref = $database->getReference('drivers/'.$drvId.'/balance') ;
            $balval = $balref->getValue() ;

            $database->getReference('drivers/'.$drvId.'/balance') // this is the root reference
                    ->set(strval($balval-$amount));

        }

        $reference = $database->getReference('transactions');
        $lists = $reference->getValue();
        foreach($lists as $key=>$list) {
            $fromtime = date("F j, Y",substr($list['createdAt'],0,10));
            $lists[$key] += [ "date" => $fromtime ];
        }
        return view('admin/withdraw', ['lists' => $lists]);
    }

    public function cancel(Request $request) {
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
            $database->getReference('transactions/'.$uid.'/status') // this is the root reference
                    ->set(strval('CANCELED'));

        }

        $reference = $database->getReference('transactions');
        $lists = $reference->getValue();
        foreach($lists as $key=>$list) {
            $fromtime = date("F j, Y",substr($list['createdAt'],0,10));
            $lists[$key] += [ "date" => $fromtime ];
        }
        return view('admin/withdraw', ['lists' => $lists]);
    }
}
