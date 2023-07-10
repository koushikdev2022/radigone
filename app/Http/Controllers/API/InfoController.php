<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info()
    {
        return response()->json([
            'app_name' => 'Radigone',
            'api_version' => '1.0',
        ]);
    }
}
