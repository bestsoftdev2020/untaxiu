<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class MtripsController extends Controller
{
    //
    public function index() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $reference = $database->getReference('trips');
        $lists = $reference->getValue();
        foreach($lists as $key=>$list) {
            if(isset($list['createdAt'])) {
                $fromtime = date("F j, Y, g:i A",substr($list['createdAt'],0,10));
                $lists[$key] += [ "beginTime" => $fromtime ];
            }
            if(isset($list['pickedUpAt'])) {
                $endtime = date("F j, Y, g:i A",substr($list['pickedUpAt'],0,10));
                $lists[$key] += [ "endTime" => $endtime ];
            }
            $customid = $list['passengerId'] ;
            $cusref = $database->getReference('passengers/'.$customid.'/name');
            $cusname = $cusref->getValue() ;
            $lists[$key] += [ "customerName" => $cusname ];
            $drvid = $list['driverId'] ;
            $drvref = $database->getReference('drivers/'.$drvid.'/name');
            $drvname = $drvref->getValue() ;
            $lists[$key] += [ "driverName" => $drvname ];
        }
        return view('admin/trips', ['lists' => $lists]);
    }
}
