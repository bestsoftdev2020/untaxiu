<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class MindexController extends Controller
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
            if($list['status'] != 'waiting') {
                unset($lists[$key]) ;
                continue;
            }
            $fromtime = date("F j, Y, g:i A",substr($list['createdAt'],0,10));
            $customid = $list['passengerId'] ;
            $cusref = $database->getReference('passengers/'.$customid.'/name');
            $cusname = $cusref->getValue() ;
            $lists[$key] += [ "customerName" => $cusname ];
            $drvid = $list['driverId'] ;
            $drvref = $database->getReference('drivers/'.$drvid.'/name');
            $drvname = $drvref->getValue() ;
            $lists[$key] += [ "driverName" => $drvname ];
            $lists[$key] += [ "beginTime" => $fromtime ];
            
            $cartyperef = $database->getReference('drivers/'.$drvid.'/type');   
            $cartype = $cartyperef->getValue() ;
            $carurlref = $database->getReference('master_settings/prices/default/vehicles/'.$cartype.'/map_icon') ;
            $caricon = $carurlref->getValue() ;
            $lists[$key] += [ "carType" => $cartype ];
            $lists[$key] += [ "carIcon" => $caricon ];
        }
        
        return view('admin/index', ['lists' => $lists]);
    }
}
