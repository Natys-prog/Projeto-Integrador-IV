<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'serverTime' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function initDatabase()
    {
        $service = new \App\Services\DatabaseInitializationService();
        $result = $service->createDatabase();
        
        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json($result, 500);
        }
    }
}
