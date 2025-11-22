<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EpiController extends Controller
{
    public function index()
    {
        // Aqui você pode buscar os EPIs do banco de dados
        $epis = []; // Será preenchido com dados do banco
        
        return view('epi', [
            'epis' => $epis,
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }
}
