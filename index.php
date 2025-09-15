<?php
session_start();

// Simple authentication check - adjust as needed for your application
if (!isset($_SESSION['user_id'])) {
    // For demo purposes, set a default user - remove in production
    $_SESSION['user_id'] = 'demo_user';
    $_SESSION['company_name'] = 'Mi Empresa';
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$company_name = $_SESSION['company_name'] ?? 'Mi Empresa';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($company_name); ?></title>
    <!-- Fallback CSS for icons and fonts -->
    <style>
        /* Icon fallbacks using CSS */
        .fas {
            display: inline-block;
            font-weight: bold;
        }
        .fa-tachometer-alt::before { content: "📊"; }
        .fa-chart-line::before { content: "📈"; }
        .fa-users::before { content: "👥"; }
        .fa-cog::before { content: "⚙️"; }
        .fa-file-invoice::before { content: "📄"; }
        .fa-box::before { content: "📦"; }
        .fa-calendar::before { content: "📅"; }
        .fa-envelope::before { content: "✉️"; }
        .fa-sign-out-alt::before { content: "🚪"; }
        .fa-dollar-sign::before { content: "💰"; }
        .fa-user-plus::before { content: "👤+"; }
        .fa-clock::before { content: "⏰"; }
        .fa-heart::before { content: "❤️"; }
        .fa-chart-pie::before { content: "🥧"; }
    </style>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Blue Header Strip */
        .header-strip {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .datetime-display {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
        }

        .company-name {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            flex: 1;
            letter-spacing: 0.5px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 260px;
            height: calc(100vh - 70px);
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 999;
        }

        .sidebar-header {
            padding: 25px 20px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }

        .sidebar-subtitle {
            font-size: 14px;
            color: #6b7280;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            display: block;
            padding: 15px 25px;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item:hover {
            background-color: #f3f4f6;
            border-left-color: #2563eb;
            color: #2563eb;
        }

        .nav-item.active {
            background-color: #eff6ff;
            border-left-color: #2563eb;
            color: #2563eb;
            font-weight: 500;
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        .content-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 16px;
            color: #6b7280;
        }

        /* Charts Container */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-container {
            position: relative;
            height: 400px;
        }

        .pie-chart-container {
            position: relative;
            height: 350px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header-strip {
                padding: 10px 15px;
            }

            .company-name {
                font-size: 18px;
            }

            .datetime-display {
                font-size: 12px;
            }

            .charts-container {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Blue Header Strip -->
    <header class="header-strip">
        <div class="header-left">
            <div class="datetime-display" id="datetime"></div>
        </div>
        <div class="company-name"><?php echo htmlspecialchars($company_name); ?></div>
        <a href="?logout=1" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">Panel de Control</div>
            <div class="sidebar-subtitle">Gestión empresarial</div>
        </div>
        <div class="sidebar-nav">
            <a href="#" class="nav-item active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-chart-line"></i>
                Reportes
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-users"></i>
                Usuarios
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-cog"></i>
                Configuración
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-file-invoice"></i>
                Facturas
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-box"></i>
                Inventario
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-calendar"></i>
                Calendario
            </a>
            <a href="#" class="nav-item">
                <i class="nav-icon fas fa-envelope"></i>
                Mensajes
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h1 class="page-title">Dashboard Principal</h1>
            <p class="page-subtitle">Resumen general de la actividad empresarial</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">1,245</div>
                        <div class="stat-label">Total Ventas</div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">89</div>
                        <div class="stat-label">Nuevos Clientes</div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">456</div>
                        <div class="stat-label">Pedidos Pendientes</div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">98.5%</div>
                        <div class="stat-label">Satisfacción</div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Container -->
        <div class="charts-container">
            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    Ventas Mensuales
                </h3>
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Distribución por Categoría
                </h3>
                <div class="pie-chart-container">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Real-time date and time update
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('datetime').textContent = now.toLocaleDateString('es-ES', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Simple CSS-based charts as fallback
        function createSimpleLineChart() {
            const canvas = document.getElementById('lineChart');
            if (canvas) {
                canvas.style.background = 'linear-gradient(45deg, #e0f2fe 0%, #81d4fa 100%)';
                canvas.style.borderRadius = '8px';
                canvas.style.display = 'flex';
                canvas.style.alignItems = 'center';
                canvas.style.justifyContent = 'center';
                canvas.style.color = '#1976d2';
                canvas.style.fontSize = '18px';
                canvas.style.fontWeight = 'bold';
                canvas.innerHTML = '<div>📈 Gráfico de Ventas<br/><small>Datos actualizándose...</small></div>';
            }
        }

        function createSimplePieChart() {
            const canvas = document.getElementById('pieChart');
            if (canvas) {
                canvas.style.background = 'conic-gradient(#2563eb 0deg 108deg, #10b981 108deg 198deg, #f59e0b 198deg 288deg, #8b5cf6 288deg 360deg)';
                canvas.style.borderRadius = '50%';
                canvas.style.border = '8px solid white';
                canvas.style.display = 'flex';
                canvas.style.alignItems = 'center';
                canvas.style.justifyContent = 'center';
                canvas.style.color = 'white';
                canvas.style.fontSize = '16px';
                canvas.style.fontWeight = 'bold';
                canvas.style.textShadow = '1px 1px 2px rgba(0,0,0,0.7)';
                canvas.innerHTML = '<div style="text-align: center">🥧<br/>Distribución<br/>por Categoría</div>';
            }
        }

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            createSimpleLineChart();
            createSimplePieChart();
        });

        // Mobile sidebar toggle (if needed)
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>