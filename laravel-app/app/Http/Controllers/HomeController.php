<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Epi;
use App\Models\Funcionario;

class HomeController extends Controller
{
    public function index()
    {
        // Get real data from database
        $totalEpis = Epi::count();
        $totalFuncionarios = Funcionario::where('status', 'ativo')->count();
        
        // EPIs próximos ao vencimento (próximos 30 dias)
        $alertasAtivos = Epi::where('data_vencimento', '<=', now()->addDays(30))
                           ->where('data_vencimento', '>', now())
                           ->count();
        
        // EPIs em manutenção
        $manutencoesPendentes = Epi::where('status', 'manutencao')->count();
        
        // EPI breakdown by type
        $episBreakdown = [
            'capacetes' => Epi::where('tipo', 'capacete')->where('status', 'ativo')->count(),
            'oculos' => Epi::where('tipo', 'oculos')->where('status', 'ativo')->count(),
            'luvas' => Epi::where('tipo', 'luvas')->where('status', 'ativo')->count(),
            'botas' => Epi::where('tipo', 'botas')->where('status', 'ativo')->count(),
            'cintos' => Epi::where('tipo', 'cinto_seguranca')->where('status', 'ativo')->count(),
        ];
        
        // Funcionários breakdown
        $funcionariosBreakdown = [
            'producao' => Funcionario::where('departamento', 'producao')->where('status', 'ativo')->count(),
            'manutencao' => Funcionario::where('departamento', 'manutencao')->where('status', 'ativo')->count(),
            'logistica' => Funcionario::where('departamento', 'logistica')->where('status', 'ativo')->count(),
            'episVencidos' => Funcionario::whereHas('epis', function($query) {
                $query->where('data_vencimento', '<', now());
            })->count(),
            'novos' => Funcionario::where('data_admissao', '>=', now()->subDays(30))->count(),
        ];
        
        // Recent activities (simulated for now - could be from an activity log table)
        $atividadesRecentes = [
            [
                'tipo' => 'EPI',
                'descricao' => 'Novos EPIs adicionados ao estoque',
                'data' => now()->subHours(2)->format('d/m/Y H:i'),
            ],
            [
                'tipo' => 'FUNC',
                'descricao' => 'EPIs atribuídos para novos funcionários',
                'data' => now()->subHours(4)->format('d/m/Y H:i'),
            ],
            [
                'tipo' => 'ALERT',
                'descricao' => "Alerta: {$alertasAtivos} EPIs próximos ao vencimento",
                'data' => now()->subHours(6)->format('d/m/Y H:i'),
            ],
            [
                'tipo' => 'MANU',
                'descricao' => "Manutenção: {$manutencoesPendentes} EPIs necessitam manutenção",
                'data' => now()->subDay()->format('d/m/Y H:i'),
            ],
        ];
        
        // Add recent funcionários
        $recentFuncionarios = Funcionario::where('created_at', '>=', now()->subDays(7))
                                       ->latest()
                                       ->limit(3)
                                       ->get();
        
        foreach ($recentFuncionarios as $funcionario) {
            array_push($atividadesRecentes, [
                'tipo' => 'FUNC',
                'descricao' => "{$funcionario->nome} cadastrado como novo funcionário",
                'data' => $funcionario->created_at->format('d/m/Y H:i'),
            ]);
        }

        $data = [
            'serverTime' => now()->format('d/m/Y H:i:s'),
            'totalEpis' => $totalEpis,
            'totalFuncionarios' => $totalFuncionarios,
            'alertasAtivos' => $alertasAtivos,
            'manutencoesPendentes' => $manutencoesPendentes,
            'epis' => $episBreakdown,
            'funcionarios' => $funcionariosBreakdown,
            'atividadesRecentes' => collect($atividadesRecentes)->sortByDesc('data')->take(5)->values()->all(),
        ];

        return view('home', $data);
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
