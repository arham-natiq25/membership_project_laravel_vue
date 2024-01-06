<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    function UpdateGateway(Request $request)
    {
        $gateway = $request->gateway;


        $setting = Setting::find(1);
        if ($setting === null) {
            Setting::create([
                'active_gateway' => $gateway
            ]);
        } else {
            $setting->update([
                'active_gateway' => $gateway
            ]);
        }
        return response()->json('Gaetway updated successfully');
    }
    function getGateway()
    {
        $setting = Setting::find(1);
        if ($setting === null || $setting->active_gateway === 1) {
            return $gateway = 1;
        } else {
            return $gateway = 0;
        }
    }
}
