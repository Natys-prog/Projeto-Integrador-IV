<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistema de Gestão EPI</title>
    @stack('styles')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f7fa; }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
        }
        
        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .user-details span {
            display: block;
            font-size: 0.9rem;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Toggle Button */
        .toggle-sidebar-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            margin-right: 1rem;
            transition: transform 0.3s;
        }

        .toggle-sidebar-btn:hover {
            transform: scale(1.1);
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            width: 260px;
            height: calc(100vh - 70px);
            background: #2c3e50;
            color: white;
            overflow-y: auto;
            transition: width 0.3s ease, transform 0.3s ease;
            z-index: 999;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-label {
            display: none;
        }

        .sidebar.collapsed .menu-item {
            padding: 1rem;
            text-align: center;
            position: relative;
        }

        .sidebar.collapsed .menu-item:hover::after {
            content: attr(data-label);
            position: absolute;
            left: 85px;
            top: 0;
            background: #34495e;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            white-space: nowrap;
            font-size: 0.9rem;
            z-index: 1001;
        }
        
        .sidebar-menu {
            padding: 2rem 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .menu-item:hover,
        .menu-item.active {
            background: #34495e;
            color: white;
            border-left-color: #667eea;
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 1rem;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .sidebar-label {
            transition: opacity 0.3s;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        .main-content.sidebar-collapsed {
            margin-left: 80px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .card-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .card-description {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .epi-icon { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .funcionario-icon { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
        .alert-icon { background: linear-gradient(135deg, #ffecd2, #fcb69f); color: #e67e22; }
        .maintenance-icon { background: linear-gradient(135deg, #a8edea, #fed6e3); color: #27ae60; }
        
        .alert {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 260px;
                transform: translateX(-100%);
                z-index: 1001;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 260px;
                transform: translateX(-100%);
            }

            .sidebar.collapsed.active {
                transform: translateX(0);
                width: 260px;
            }
            
            .main-content {
                margin-left: 0;
            }

            .main-content.sidebar-collapsed {
                margin-left: 0;
            }
            
            .header {
                padding: 1rem;
            }
            
            .menu-toggle {
                display: block !important;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div style="display: flex; align-items: center;">
            <button class="toggle-sidebar-btn" onclick="toggleSidebarCollapse()" title="Expandir/Recolher">≡</button>
            <h1>📋 Sistema de Gestão EPI</h1>
        </div>
        
        @auth
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-details">
                <span style="font-weight: 600;">{{ Auth::user()->name }}</span>
                <span style="opacity: 0.8;">{{ Auth::user()->email }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Sair</button>
            </form>
        </div>
        @endauth
    </header>
    
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('home') }}" class="menu-item {{ Route::currentRouteName() === 'home' ? 'active' : '' }}" data-label="Dashboard">
                <i>🏠</i> <span class="sidebar-label">Dashboard</span>
            </a>
            <a href="{{ route('epi.index') }}" class="menu-item {{ Route::currentRouteName() === 'epi.index' ? 'active' : '' }}" data-label="EPIs">
                <i>🦺</i> <span class="sidebar-label">EPIs</span>
            </a>
            <a href="funcionarios" class="menu-item {{ Route::currentRouteName() === 'funcionarios.index' ? 'active' : '' }}" data-label="Funcionários">
                <i>👥</i> <span class="sidebar-label">Funcionários</span>
            </a>
            <a href="relatorios" class="menu-item {{ Route::currentRouteName() === 'relatorios.index' ? 'active' : '' }}" data-label="Relatórios">
                <i>📊</i> <span class="sidebar-label">Relatórios</span>
            </a>
            <a href="configuracoes" class="menu-item {{ Route::currentRouteName() === 'configuracoes.index' ? 'active' : '' }}" data-label="Configurações">
                <i>⚙️</i> <span class="sidebar-label">Configurações</span>
            </a>
            <a href="alertas" class="menu-item {{ Route::currentRouteName() === 'alertas.index' ? 'active' : '' }}" data-label="Alertas">
                <i>❗</i> <span class="sidebar-label">Alertas</span>
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        @if(session('flash'))
            <div class="alert alert-success">{{ session('flash') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleSidebarCollapse() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
            
            // Salvar preferência no localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Restaurar preferência ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                const toggleBtn = document.querySelector('.toggle-sidebar-btn');
                
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.sidebar').classList.remove('active');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
