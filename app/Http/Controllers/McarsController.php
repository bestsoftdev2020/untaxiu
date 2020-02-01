<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class McarsController extends Controller
{
    //
    public function index() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $reference = $database->getReference('master_settings/prices/default/vehicles');
        $lists = $reference->getValue();
        return view('admin/cars', ['lists' => $lists]);
    }

    public function save(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
    
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/enable')->set(strval('true'));
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/icon')->set($data['icon']);
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/map_icon')->set($data['map_icon']);
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/name')->set($data['name']);
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/price')->set($data['price']);
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/type')->set($data['type']);
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'].'/key')->set($data['type']);

        $reference = $database->getReference('master_settings/prices/default/vehicles');
        $lists = $reference->getValue();
        return view('admin/cars', ['lists' => $lists]);
    }

    public function delete(Request $request) {
        $data = $request->all() ;

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        
        $database->getReference('master_settings/prices/default/vehicles/'.$data['type'])->remove();

        $reference = $database->getReference('master_settings/prices/default/vehicles');
        $lists = $reference->getValue();
        return view('admin/cars', ['lists' => $lists]);
    }

    public function default() {

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/untaxiu-8c070169f4ca.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://untaxiu.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();

        $database->getReference('master_settings/prices/default/vehicles')->remove();
    
        $database->getReference('master_settings/prices/default/vehicles/sedan/enable')->set(strval('true'));
        $database->getReference('master_settings/prices/default/vehicles/sedan/icon')->set('assets/img/sedan.svg');
        $database->getReference('master_settings/prices/default/vehicles/sedan/map_icon')->set('assets/img/map-sedan.png');
        $database->getReference('master_settings/prices/default/vehicles/sedan/name')->set('Sedan');
        $database->getReference('master_settings/prices/default/vehicles/sedan/price')->set(20);
        $database->getReference('master_settings/prices/default/vehicles/sedan/type')->set('sedan');
        $database->getReference('master_settings/prices/default/vehicles/sedan/key')->set('sedan');

        $database->getReference('master_settings/prices/default/vehicles/suv/enable')->set(strval('true'));
        $database->getReference('master_settings/prices/default/vehicles/suv/icon')->set('assets/img/suv.svg');
        $database->getReference('master_settings/prices/default/vehicles/suv/map_icon')->set('assets/img/map-suv.png');
        $database->getReference('master_settings/prices/default/vehicles/suv/name')->set('SUV');
        $database->getReference('master_settings/prices/default/vehicles/suv/price')->set(20);
        $database->getReference('master_settings/prices/default/vehicles/suv/type')->set('suv');
        $database->getReference('master_settings/prices/default/vehicles/suv/key')->set('suv');

        $database->getReference('master_settings/prices/default/vehicles/taxi/enable')->set(strval('true'));
        $database->getReference('master_settings/prices/default/vehicles/taxi/icon')->set('assets/img/taxi.svg');
        $database->getReference('master_settings/prices/default/vehicles/taxi/map_icon')->set('assets/img/map-taxi.png');
        $database->getReference('master_settings/prices/default/vehicles/taxi/name')->set('Taxi');
        $database->getReference('master_settings/prices/default/vehicles/taxi/price')->set(40);
        $database->getReference('master_settings/prices/default/vehicles/taxi/type')->set('taxi');
        $database->getReference('master_settings/prices/default/vehicles/taxi/key')->set('taxi');

        $reference = $database->getReference('master_settings/prices/default/vehicles');
        $lists = $reference->getValue();
        return view('admin/cars', ['lists' => $lists]);
    }
}
