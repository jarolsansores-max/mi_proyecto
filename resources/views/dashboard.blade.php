<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Mi Proyecto</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Franja del Menú / Barra de Navegación */
        .navbar-strip {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .navbar-logo {
            font-weight: 600;
            font-size: 1.25rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-logo span {
            color: var(--primary);
        }

        /* Menú y Dropdown */
        .menu-container {
            position: relative;
        }

        .menu-trigger {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .menu-trigger:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
        }

        .menu-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            width: 220px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            padding: 0.5rem;
            display: none;
            flex-direction: column;
            z-index: 101;
            transform-origin: top right;
            animation: dropdownFade 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .menu-dropdown.show {
            display: flex;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: scale(0.95) translateY(-5px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            text-align: left;
            background: none;
            border: none;
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.08);
        }

        .dropdown-item.active {
            color: white;
            background: var(--primary);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--glass-border);
            margin: 0.5rem 0;
        }

        .logout-button {
            color: #fca5a5;
        }

        .logout-button:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        /* Contenido Principal */
        .main-container {
            margin-top: 5rem;
            flex-grow: 1;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            padding: 3rem;
            border-radius: 1.5rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 600px;
            transition: all 0.3s ease;
            animation: cardEntrance 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Cuando la vista de Aspirantes o Alumnos está activa queremos más ancho */
        .card.full-screen {
            max-width: 1400px;
            padding: 2rem;
            width: 95vw;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 2.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            margin-top: 0;
        }

        .subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        /* Contenido Dinámico de Pestañas */
        .content-panel {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .content-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Estilo para los detalles de las vistas */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            text-align: left;
            margin-top: 1.5rem;
        }

        .info-card {
            background: rgba(0, 0, 0, 0.2);
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--glass-border);
        }

        .info-card-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .info-card-value {
            font-weight: 600;
            font-size: 1rem;
        }

        .user-table-container {
            overflow-x: auto;
            margin-top: 1.5rem;
            text-align: left;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .user-table th,
        .user-table td {
            padding: 0.95rem 1rem;
            font-size: 0.95rem;
            color: var(--text-main);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            text-align: left;
        }

        .user-table th {
            background: rgba(15, 23, 42, 0.75);
            color: var(--text-muted);
            font-weight: 600;
        }

        .user-table tr:last-child td {
            border-bottom: none;
        }

        .user-table button {
            border: none;
            border-radius: 0.5rem;
            padding: 0.45rem 0.85rem;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .user-table .edit-btn {
            background: rgba(99, 102, 241, 0.15);
            color: white;
            margin-right: 0.5rem;
        }

        .user-table .delete-btn {
            background: rgba(248, 113, 113, 0.15);
            color: #fca5a5;
        }

        .user-table button:hover {
            filter: brightness(1.05);
        }

        /* Estilos de Configuración */
        .config-list {
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.15);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--glass-border);
        }

        .config-info h4 {
            margin: 0;
            font-size: 0.95rem;
        }

        .config-info p {
            margin: 0.25rem 0 0 0;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #475569;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }

        /* Circulos Decorativos */
        .decorative-circle {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #a855f7 100%);
            filter: blur(120px);
            z-index: -2;
            opacity: 0.25;
        }

        .circle-1 { top: -100px; left: -100px; }
        .circle-2 { bottom: -100px; right: -100px; }

        /* Left Sidebar Menu */
        .left-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 0;
            z-index: 99;
            overflow-y: auto;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 1rem;
        }

        .sidebar-item {
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: none;
            border: none;
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-align: left;
        }

        .sidebar-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-item.active {
            color: white;
            background: var(--primary);
        }

        /* Adjust main layout for sidebar */
        body {
            margin-left: 250px;
        }

        .navbar-strip {
            left: 250px;
            right: 0;
        }

        .main-container {
            margin-left: 0;
        }

        /* Modal and Form Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            padding: 2rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 500px;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 1rem;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .close-btn:hover {
            color: white;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem 1rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 0.5rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        .form-group select option {
            background: #1e293b;
            color: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(0, 0, 0, 0.3);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .confirm-email-group {
            display: none;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
        }

        .confirm-email-group.show {
            display: flex;
        }

        .form-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--glass-border);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid var(--glass-border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .user-btn {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .user-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
        }

        .success-message {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #10b981;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        .success-message.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #fca5a5;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        .error-message.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="decorative-circle circle-1"></div>
    <div class="decorative-circle circle-2"></div>

    <!-- Sidebar Izquierdo -->
    <aside class="left-sidebar">
        <nav class="sidebar-menu">
            <button class="sidebar-item active" onclick="switchPanel('dashboard', this)" title="Ir a Inicio">
                🏠 Inicio
            </button>
            <button class="sidebar-item" onclick="switchPanel('reports', this)" title="Ver Estadísticas">
                📊 Estadísticas
            </button>
            <button class="sidebar-item" onclick="switchPanel('users', this)" title="Gestionar Usuarios">
                👥 Usuarios
            </button>
            <button class="sidebar-item" onclick="switchPanel('carreras', this)" title="Gestionar Carreras">
                🎓 Carreras
            </button>
            <button class="sidebar-item" onclick="switchPanel('aspirantes', this)" title="Gestionar Aspirantes">
                📝 Aspirantes
            </button>
            <button id="sidebarAlumnosBtn" class="sidebar-item" onclick="switchPanel('alumnos', this)" title="Gestionar Alumnos">
                🎓 Alumnos
            </button>
        </nav>
    </aside>

    <!-- Barra de Navegación (Franja) -->
    <header class="navbar-strip">
        <a href="#" class="navbar-logo">
            <span>🏫</span> Control Escolar
        </a>

        <div style="display: flex; align-items: center; gap: 1rem;">
            <div class="menu-container">
                <button class="menu-trigger" id="menuBtn">
                    <span>{{ Auth::user()->name }}</span>
                    <span style="font-size: 0.8rem;">▼</span>
                </button>
                
                <!-- Menú Desplegable -->
                <div class="menu-dropdown" id="menuDropdown">
                    <!-- Título 1 -->
                    <button class="dropdown-item" onclick="switchPanel('profile', this)">
                        👤 Mi Perfil
                    </button>

                    <!-- Título 2 -->
                    <button class="dropdown-item" onclick="switchPanel('settings', this)">
                        ⚙️ Ajustes
                    </button>

                    <div class="dropdown-divider"></div>

                    <!-- Cerrar Sesión -->
                    <form action="{{ route('logout') }}" method="POST" id="logoutForm" style="display: none;">
                        @csrf
                    </form>
                    <button class="dropdown-item logout-button" onclick="document.getElementById('logoutForm').submit();">
                        🚪 Cerrar Sesión
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenedor de Contenido Principal -->
    <main class="main-container">
        <div class="card">
            
            <!-- Panel 1: Bienvenido (Inicio) -->
            <div id="panel-dashboard" class="content-panel">
                <h1>¡Hola, {{ Auth::user()->name }}!</h1>
                <p class="subtitle">Has iniciado sesión correctamente. Este es tu panel de control privado.</p>
                <div style="background: rgba(0, 0, 0, 0.2); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
                    <p style="margin: 0; color: white;">Utiliza el menú de la esquina superior derecha para navegar por las diferentes secciones del sistema.</p>
                </div>
            </div>

            <!-- Panel 2: Perfil -->
            <div id="panel-profile" class="content-panel">
                <h1>Mi Perfil</h1>
                <p class="subtitle">Información detallada de tu cuenta</p>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-card-label">Nombre Completo</div>
                        <div class="info-card-value">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Correo Electrónico</div>
                        <div class="info-card-value">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Rol del Usuario</div>
                        <div class="info-card-value">Administrador</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Miembro desde</div>
                        <div class="info-card-value">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : 'Reciente' }}</div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Ajustes -->
            <div id="panel-settings" class="content-panel">
                <h1>Ajustes</h1>
                <p class="subtitle">Configura tus preferencias del sistema</p>
                <div class="config-list">
                    <div class="config-item">
                        <div class="config-info">
                            <h4>Notificaciones</h4>
                            <p>Recibir notificaciones por correo electrónico</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="config-item">
                        <div class="config-info">
                            <h4>Modo Desarrollador</h4>
                            <p>Habilitar logs y herramientas avanzadas</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Estadísticas / Reportes -->
            <div id="panel-reports" class="content-panel">
                <h1>Estadísticas del Mes</h1>
                <p class="subtitle">Resumen de actividad del proyecto</p>
                <div class="info-grid">
                    <div class="info-card" style="grid-column: span 2; text-align: center;">
                        <div class="info-card-label" style="font-size: 0.9rem;">Rendimiento del Sistema</div>
                        <div class="info-card-value" style="font-size: 2.5rem; color: #10b981; margin: 0.5rem 0;">99.9%</div>
                        <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted);">Servidores activos y funcionando con total normalidad.</p>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Conexiones</div>
                        <div class="info-card-value">Estables</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Latencia de DB</div>
                        <div class="info-card-value">12ms</div>
                    </div>
                </div>
            </div>

            <!-- Panel 5: Usuarios -->
            <div id="panel-users" class="content-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <h1>Usuarios</h1>
                        <p class="subtitle">Gestiona los usuarios registrados en el sistema</p>
                    </div>
                    <button class="btn btn-primary" id="openUserFormBtn">+ Agregar Usuario</button>
                </div>

                <div class="info-grid" style="margin-top: 1.5rem; grid-template-columns: 1fr 1fr;">
                    <div class="info-card">
                        <div class="info-card-label">Total de Usuarios</div>
                        <div class="info-card-value" id="totalUsers">{{ $users->count() }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Usuarios Activos</div>
                        <div class="info-card-value">{{ $users->count() }}</div>
                    </div>
                </div>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Creado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div id="panel-carreras" class="content-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <h1>Carreras</h1>
                        <p class="subtitle">Gestiona las carreras disponibles</p>
                    </div>
                    <button class="btn btn-primary" id="openCarreraFormBtn">+ Agregar carrera</button>
                </div>

                <div class="info-grid" style="margin-top: 1.5rem; grid-template-columns: 1fr 1fr;">
                    <div class="info-card">
                        <div class="info-card-label">Total de Carreras</div>
                        <div class="info-card-value" id="totalCarreras">0</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Carreras Activas</div>
                        <div class="info-card-value" id="activeCarreras">0</div>
                    </div>
                </div>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="carreraTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div id="panel-aspirantes" class="content-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <h1>Aspirantes</h1>
                        <p class="subtitle">Gestiona aspirantes y asigna carreras</p>
                    </div>
                    <button class="btn btn-primary" id="openAspiranteFormBtn">+ Agregar aspirante</button>
                </div>

                <div class="info-grid" style="margin-top: 1.5rem; grid-template-columns: repeat(2, 1fr);">
                    <div class="info-card">
                        <div class="info-card-label">Total de Aspirantes</div>
                        <div class="info-card-value" id="totalAspirantes">0</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Aspirantes Aceptados</div>
                        <div class="info-card-value" id="acceptedAspirantes">0</div>
                    </div>
                </div>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Nombre</th>
                                <th>Carrera</th>
                                <th>Fecha Nacimiento</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="aspiranteTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div id="panel-alumnos" class="content-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <h2>Alumnos</h2>
                        <p class="subtitle">gestiona alumnos registrados</p>
                    </div>
                    <button class="btn btn-primary" id="openAlumnoFormBtn">+ Agregar alumno</button>
                </div>

                <div class="info-grid" style="margin-top: 1.5rem; grid-template-columns: repeat(3, 1fr);">
                    <div class="info-card">
                        <div class="info-card-label">Aspirantes</div>
                        <div class="info-card-value" id="totalAspirantesEnAlumnos">0</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Total de Alumnos</div>
                        <div class="info-card-value" id="totalAlumnos">0</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-label">Alumnos Activos</div>
                        <div class="info-card-value" id="activeAlumnos">0</div>
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 0.5rem; text-align: left; font-size: 1.1rem;">Alumnos Registrados</h3>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Carrera</th>
                                <th>CURP</th>
                                <th>Fecha Nacimiento</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="alumnoTableBody"></tbody>
                    </table>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 0.5rem; text-align: left; font-size: 1.1rem;">Aspirantes Disponibles</h3>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Nombre</th>
                                <th>Carrera</th>
                                <th>CURP</th>
                                <th>Fecha Nacimiento</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="aspiranteAlumnosTableBody"></tbody>
                    </table>
                </div>


            </div>

        </div>
    </main>

    <!-- Modal para Agregar Usuario -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="userModalTitle">Agregar Nuevo Usuario</h2>
                <button class="close-btn" onclick="closeUserModal()">&times;</button>
            </div>

            <div id="successMessage" class="success-message">
                ✓ Usuario registrado exitosamente
            </div>
            <div id="errorMessage" class="error-message">
                ✗ Error al registrar el usuario
            </div>

            <form id="userForm">
                <input type="hidden" id="userId">
                @csrf
                <div class="form-group">
                    <label for="userName">Nombre Completo</label>
                    <input 
                        type="text" 
                        id="userName" 
                        placeholder="Usuario" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="userEmail">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="userEmail" 
                        placeholder="Email" 
                        required
                        oninput="onEmailChange()"
                    >
                </div>

                <!-- Este bloque aparece cuando el email es válido -->
                <div id="confirmEmailGroup" class="confirm-email-group">
                    <div class="form-group">
                        <label for="userEmailConfirm">Confirmar Correo Electrónico</label>
                        <input 
                            type="email" 
                            id="userEmailConfirm" 
                            placeholder="Repite tu correo electrónico"
                        >
                    </div>

                    <div class="form-group">
                        <label for="userPassword">Contraseña</label>
                        <input 
                            type="password" 
                            id="userPassword" 
                            placeholder="Mínimo 8 caracteres" 
                            minlength="8"
                        >
                    </div>

                    <div class="form-group">
                        <label for="userPasswordConfirm">Confirmar Contraseña</label>
                        <input 
                            type="password" 
                            id="userPasswordConfirm" 
                            placeholder="Repite tu contraseña" 
                            minlength="8"
                        >
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="saveUserButton">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Carrera -->
    <div id="carreraModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="carreraModalTitle">Agregar carrera</h2>
                <button class="close-btn" onclick="closeCarreraModal()">&times;</button>
            </div>

            <div id="successCarreraMessage" class="success-message">✓ La carrera se registró correctamente</div>
            <div id="errorCarreraMessage" class="error-message">✗ No se pudo guardar la carrera</div>

            <form id="carreraForm">
                <input type="hidden" id="carreraId">
                @csrf
                <div class="form-group">
                    <label for="carreraNombre">Nombre de la Carrera</label>
                    <input type="text" id="carreraNombre" placeholder="Nombre de la carrera" required>
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeCarreraModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="saveCarreraButton">Guardar carrera</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Aspirante -->
    <div id="aspiranteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="aspiranteModalTitle">Agregar aspirante</h2>
                <button class="close-btn" onclick="closeAspiranteModal()">&times;</button>
            </div>

            <div id="successAspiranteMessage" class="success-message">✓ El aspirante se registró correctamente</div>
            <div id="errorAspiranteMessage" class="error-message">✗ No se pudo guardar el aspirante</div>

            <form id="aspiranteForm">
                <input type="hidden" id="aspiranteId">
                @csrf
                <div class="form-group">
                    <label for="aspiranteFolio">Folio</label>
                    <input type="text" id="aspiranteFolio" placeholder="Folio" required>
                </div>
                <div class="form-group">
                    <label for="aspiranteCurp">CURP</label>
                    <input type="text" id="aspiranteCurp" placeholder="CURP" maxlength="18" required>
                </div>
                <div class="form-group">
                    <label for="aspiranteNombre">Nombre</label>
                    <input type="text" id="aspiranteNombre" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <label for="aspiranteApPaterno">Apellido Paterno</label>
                    <input type="text" id="aspiranteApPaterno" placeholder="Apellido paterno" required>
                </div>
                <div class="form-group">
                    <label for="aspiranteApMaterno">Apellido Materno</label>
                    <input type="text" id="aspiranteApMaterno" placeholder="Apellido materno">
                </div>
                <div class="form-group">
                    <label for="aspiranteFechaNacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="aspiranteFechaNacimiento">
                </div>
                <div class="form-group">
                    <label for="aspiranteEmail">Correo Electrónico</label>
                    <input type="email" id="aspiranteEmail" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="aspiranteTelefono">Teléfono</label>
                    <input type="text" id="aspiranteTelefono" placeholder="Teléfono">
                </div>
                <div class="form-group">
                    <label for="aspiranteCarrera">Carrera</label>
                    <select id="aspiranteCarrera" required>
                        <option value="">Selecciona una carrera</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="aspirantePromedio">Promedio de Bachillerato</label>
                    <input type="number" id="aspirantePromedio" placeholder="Ej. 8.75" step="0.01" min="0" max="100">
                </div>
                <div class="form-group">
                    <label for="aspiranteEstatus">Estatus</label>
                    <select id="aspiranteEstatus" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Aceptado">Aceptado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="aspiranteDocumentosCompletos">Documentos Completos</label>
                    <input type="number" id="aspiranteDocumentosCompletos" placeholder="0 o 1" min="0" max="1" required>
                </div>
                <div class="form-group">
                    <label for="aspiranteObservaciones">Observaciones</label>
                    <input type="text" id="aspiranteObservaciones" placeholder="Observaciones">
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeAspiranteModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="saveAspiranteButton">Guardar aspirante</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Alumno -->
    <div id="alumnoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="alumnoModalTitle">Agregar alumno</h2>
                <button class="close-btn" onclick="closeAlumnoModal()">&times;</button>
            </div>

            <div id="successAlumnoMessage" class="success-message">✓ El alumno se registró correctamente</div>
            <div id="errorAlumnoMessage" class="error-message">✗ No se pudo guardar el alumno</div>

            <form id="alumnoForm">
                <input type="hidden" id="alumnoId">
                @csrf
                <div class="form-group">
                    <label for="alumnoAspiranteSearch">Buscar aspirante</label>
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <input type="text" id="alumnoAspiranteSearch" placeholder="Escribe folio o nombre" style="flex:1;">
                        <button type="button" class="btn btn-secondary" onclick="clearAlumnoAspiranteSelection()" style="padding:0.45rem 0.8rem;">Limpiar</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="alumnoAspirante">Copiar datos desde aspirante</label>
                    <select id="alumnoAspirante">
                        <option value="">Selecciona un aspirante</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alumnoMatricula">Matrícula</label>
                    <input type="text" id="alumnoMatricula" placeholder="Matrícula" required>
                </div>
                <div class="form-group">
                    <label for="alumnoCurp">CURP</label>
                    <input type="text" id="alumnoCurp" placeholder="CURP" maxlength="18" required>
                </div>
                <div class="form-group">
                    <label for="alumnoNombre">Nombre</label>
                    <input type="text" id="alumnoNombre" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <label for="alumnoApPaterno">Apellido Paterno</label>
                    <input type="text" id="alumnoApPaterno" placeholder="Apellido paterno" required>
                </div>
                <div class="form-group">
                    <label for="alumnoApMaterno">Apellido Materno</label>
                    <input type="text" id="alumnoApMaterno" placeholder="Apellido materno">
                </div>
                <div class="form-group">
                    <label for="alumnoFechaNacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="alumnoFechaNacimiento">
                </div>
                <div class="form-group">
                    <label for="alumnoEmail">Correo Electrónico</label>
                    <input type="email" id="alumnoEmail" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="alumnoTelefono">Teléfono</label>
                    <input type="text" id="alumnoTelefono" placeholder="Teléfono">
                </div>
                <div class="form-group">
                    <label for="alumnoCarrera">Carrera</label>
                    <select id="alumnoCarrera" required>
                        <option value="">Selecciona una carrera</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alumnoEstatus">Estatus</label>
                    <select id="alumnoEstatus" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeAlumnoModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="saveAlumnoButton">Guardar alumno</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const menuDropdown = document.getElementById('menuDropdown');
        const userModal = document.getElementById('userModal');
        const userForm = document.getElementById('userForm');
        const userEmail = document.getElementById('userEmail');
        const confirmEmailGroup = document.getElementById('confirmEmailGroup');

        const carreraModal = document.getElementById('carreraModal');
        const carreraForm = document.getElementById('carreraForm');
        const carreraNombre = document.getElementById('carreraNombre');
        const carreraIdInput = document.getElementById('carreraId');
        const carreraTableBody = document.getElementById('carreraTableBody');
        const totalCarrerasCount = document.getElementById('totalCarreras');
        const activeCarrerasCount = document.getElementById('activeCarreras');
        const openCarreraFormBtn = document.getElementById('openCarreraFormBtn');
        const saveCarreraButton = document.getElementById('saveCarreraButton');
        const carreraModalTitle = document.getElementById('carreraModalTitle');
        const successCarreraMessage = document.getElementById('successCarreraMessage');
        const errorCarreraMessage = document.getElementById('errorCarreraMessage');
        let currentCarreras = [];
        let editingCarreraId = null;

        const aspiranteModal = document.getElementById('aspiranteModal');
        const aspiranteForm = document.getElementById('aspiranteForm');
        const aspiranteIdInput = document.getElementById('aspiranteId');
        const aspiranteFolio = document.getElementById('aspiranteFolio');
        const aspiranteCurp = document.getElementById('aspiranteCurp');
        const aspiranteNombre = document.getElementById('aspiranteNombre');
        const aspiranteApPaterno = document.getElementById('aspiranteApPaterno');
        const aspiranteApMaterno = document.getElementById('aspiranteApMaterno');
        const aspiranteFechaNacimiento = document.getElementById('aspiranteFechaNacimiento');
        const aspiranteEmail = document.getElementById('aspiranteEmail');
        const aspiranteTelefono = document.getElementById('aspiranteTelefono');
        const aspiranteCarrera = document.getElementById('aspiranteCarrera');
        const aspirantePromedio = document.getElementById('aspirantePromedio');
        const aspiranteEstatus = document.getElementById('aspiranteEstatus');
        const aspiranteDocumentosCompletos = document.getElementById('aspiranteDocumentosCompletos');
        const aspiranteObservaciones = document.getElementById('aspiranteObservaciones');
        const aspiranteTableBody = document.getElementById('aspiranteTableBody');
        const totalAspirantesCount = document.getElementById('totalAspirantes');
        const acceptedAspirantesCount = document.getElementById('acceptedAspirantes');
        const openAspiranteFormBtn = document.getElementById('openAspiranteFormBtn');
        const saveAspiranteButton = document.getElementById('saveAspiranteButton');
        const aspiranteModalTitle = document.getElementById('aspiranteModalTitle');
        const successAspiranteMessage = document.getElementById('successAspiranteMessage');
        const errorAspiranteMessage = document.getElementById('errorAspiranteMessage');
        let currentAspirantes = [];
        let editingAspiranteId = null;

        const alumnoModal = document.getElementById('alumnoModal');
        const alumnoForm = document.getElementById('alumnoForm');
        const alumnoIdInput = document.getElementById('alumnoId');
        const alumnoAspirante = document.getElementById('alumnoAspirante');
        const alumnoAspiranteSearch = document.getElementById('alumnoAspiranteSearch');
        const alumnoMatricula = document.getElementById('alumnoMatricula');
        const alumnoCurp = document.getElementById('alumnoCurp');
        const alumnoNombre = document.getElementById('alumnoNombre');
        const alumnoApPaterno = document.getElementById('alumnoApPaterno');
        const alumnoApMaterno = document.getElementById('alumnoApMaterno');
        const alumnoFechaNacimiento = document.getElementById('alumnoFechaNacimiento');
        const alumnoEmail = document.getElementById('alumnoEmail');
        const alumnoTelefono = document.getElementById('alumnoTelefono');
        const alumnoCarrera = document.getElementById('alumnoCarrera');
        const alumnoEstatus = document.getElementById('alumnoEstatus');
        const alumnoTableBody = document.getElementById('alumnoTableBody');
        const aspiranteAlumnosTableBody = document.getElementById('aspiranteAlumnosTableBody');
        const totalAlumnosCount = document.getElementById('totalAlumnos');
        const totalAspirantesEnAlumnosCount = document.getElementById('totalAspirantesEnAlumnos');
        const activeAlumnosCount = document.getElementById('activeAlumnos');
        const openAlumnoFormBtn = document.getElementById('openAlumnoFormBtn');
        const saveAlumnoButton = document.getElementById('saveAlumnoButton');
        const alumnoModalTitle = document.getElementById('alumnoModalTitle');
        const successAlumnoMessage = document.getElementById('successAlumnoMessage');
        const errorAlumnoMessage = document.getElementById('errorAlumnoMessage');
        let currentAlumnos = [];
        let editingAlumnoId = null;

        function formatDateForInput(value) {
            if (!value) {
                return '';
            }

            return String(value).slice(0, 10);
        }

        function formatDateForDisplay(value) {
            if (!value) {
                return '-';
            }

            const date = new Date(value);
            if (Number.isNaN(date.getTime())) {
                return String(value).slice(0, 10);
            }

            return date.toLocaleDateString('es-ES');
        }

        aspiranteCurp.addEventListener('input', () => {
            aspiranteCurp.value = aspiranteCurp.value
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, '')
                .slice(0, 18);
        });

        alumnoCurp.addEventListener('input', () => {
            alumnoCurp.value = alumnoCurp.value
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, '')
                .slice(0, 18);
        });

        let alumnoAspiranteSource = [];

        function filterAlumnoAspiranteOptions() {
            const query = (alumnoAspiranteSearch.value || '').trim().toLowerCase();
            const filteredAspirantes = !query
                ? alumnoAspiranteSource
                : alumnoAspiranteSource.filter(aspirante => {
                    const label = `${aspirante.folio || ''} ${aspirante.nombre || ''} ${aspirante.ap_paterno || ''} ${aspirante.ap_materno || ''}`.toLowerCase();
                    return label.includes(query);
                });

            alumnoAspirante.innerHTML = '<option value="">Selecciona un aspirante</option>';
            filteredAspirantes.forEach(aspirante => {
                const label = `${aspirante.folio} - ${aspirante.nombre} ${aspirante.ap_paterno}`;
                alumnoAspirante.innerHTML += `<option value="${aspirante.id_aspirantes}">${label}</option>`;
            });
        }

        function fillAlumnoFromAspirante(aspirante) {
            if (!aspirante) {
                return;
            }

            alumnoMatricula.value = aspirante.folio || '';
            alumnoCurp.value = aspirante.curp || '';
            alumnoNombre.value = aspirante.nombre || '';
            alumnoApPaterno.value = aspirante.ap_paterno || '';
            alumnoApMaterno.value = aspirante.ap_materno || '';
            alumnoFechaNacimiento.value = formatDateForInput(aspirante.fecha_nacimiento);
            alumnoEmail.value = aspirante.email || '';
            alumnoTelefono.value = aspirante.telefono || '';
            alumnoCarrera.value = aspirante.id_carrera || '';
            alumnoEstatus.value = 'Activo';
        }

        alumnoAspiranteSearch.addEventListener('input', filterAlumnoAspiranteOptions);

        window.clearAlumnoAspiranteSelection = function () {
            alumnoAspiranteSearch.value = '';
            alumnoAspirante.value = '';
            filterAlumnoAspiranteOptions();
        };

        alumnoAspirante.addEventListener('change', () => {
            const selectedId = Number(alumnoAspirante.value);
            if (!selectedId) {
                return;
            }

            const aspirante = currentAspirantes.find(item => item.id_aspirantes === selectedId)
                || alumnoAspiranteSource.find(item => item.id_aspirantes === selectedId);

            fillAlumnoFromAspirante(aspirante);
        });

        // ========== EVENTOS DEL MENÚ ==========
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            menuDropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!menuBtn.contains(e.target) && !menuDropdown.contains(e.target)) {
                menuDropdown.classList.remove('show');
            }
        });

        // ========== EVENTOS DEL MODAL DE USUARIOS ==========
        const userTableBody = document.getElementById('userTableBody');
        const totalUsersCount = document.getElementById('totalUsers');
        const openUserFormBtn = document.getElementById('openUserFormBtn');
        const saveUserButton = document.getElementById('saveUserButton');
        const userIdInput = document.getElementById('userId');
        const userModalTitle = document.getElementById('userModalTitle');
        const initialUsers = @json($users);
        let currentUsers = [...initialUsers];
        let editingUserId = null;

        openUserFormBtn.addEventListener('click', () => {
            openUserForm();
        });

        userModal.addEventListener('click', (e) => {
            if (e.target === userModal) {
                closeUserModal();
            }
        });

        openCarreraFormBtn.addEventListener('click', () => {
            openCarreraForm();
        });

        carreraModal.addEventListener('click', (e) => {
            if (e.target === carreraModal) {
                closeCarreraModal();
            }
        });

        openAspiranteFormBtn.addEventListener('click', () => {
            openAspiranteForm();
        });

        aspiranteModal.addEventListener('click', (e) => {
            if (e.target === aspiranteModal) {
                closeAspiranteModal();
            }
        });

        openAlumnoFormBtn.addEventListener('click', () => {
            openAlumnoForm();
        });

        alumnoModal.addEventListener('click', (e) => {
            if (e.target === alumnoModal) {
                closeAlumnoModal();
            }
        });

        function openUserForm(user = null) {
            editingUserId = user ? user.id : null;
            userModal.classList.add('show');
            confirmEmailGroup.classList.add('show');
            userForm.reset();

            if (editingUserId) {
                userModalTitle.textContent = 'Editar Usuario';
                saveUserButton.textContent = 'Actualizar Usuario';
                userIdInput.value = user.id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userEmailConfirm').value = user.email;
                document.getElementById('userPassword').value = '';
                document.getElementById('userPasswordConfirm').value = '';
            } else {
                userModalTitle.textContent = 'Agregar Nuevo Usuario';
                saveUserButton.textContent = 'Guardar Usuario';
                userIdInput.value = '';
            }
        }

        function closeUserModal() {
            userModal.classList.remove('show');
            editingUserId = null;
            userForm.reset();
            confirmEmailGroup.classList.remove('show');
            document.getElementById('successMessage').classList.remove('show');
            document.getElementById('errorMessage').classList.remove('show');
            userModalTitle.textContent = 'Agregar Nuevo Usuario';
            saveUserButton.textContent = 'Guardar Usuario';
        }

        function openCarreraForm(carrera = null) {
            editingCarreraId = carrera ? carrera.id_carrera : null;
            carreraModal.classList.add('show');
            carreraForm.reset();
            successCarreraMessage.classList.remove('show');
            errorCarreraMessage.classList.remove('show');

            if (editingCarreraId) {
                carreraModalTitle.textContent = 'Editar Carrera';
                saveCarreraButton.textContent = 'Actualizar Carrera';
                carreraIdInput.value = carrera.id_carrera;
                carreraNombre.value = carrera.nombre;
            } else {
                carreraModalTitle.textContent = 'Agregar Nueva Carrera';
                saveCarreraButton.textContent = 'Guardar Carrera';
                carreraIdInput.value = '';
            }
        }

        function closeCarreraModal() {
            carreraModal.classList.remove('show');
            editingCarreraId = null;
            carreraForm.reset();
            successCarreraMessage.classList.remove('show');
            errorCarreraMessage.classList.remove('show');
            carreraModalTitle.textContent = 'Agregar Nueva Carrera';
            saveCarreraButton.textContent = 'Guardar Carrera';
        }

        async function loadCarreras() {
            try {
                const response = await fetch('/carreras/list', { headers: { 'Accept': 'application/json' } });
                const data = await response.json();
                if (response.ok && data.carreras) {
                    renderCarreras(data.carreras);
                    populateCarreraSelect(data.carreras);
                    populateAlumnoCarreraSelect(data.carreras);
                }
            } catch (error) {
                console.error('Error cargando carreras:', error);
            }
        }

        function renderCarreras(carreras) {
            currentCarreras = carreras;
            totalCarrerasCount.textContent = carreras.length;
            activeCarrerasCount.textContent = carreras.length;
            carreraTableBody.innerHTML = '';

            if (carreras.length === 0) {
                carreraTableBody.innerHTML = '<tr><td colspan="3" style="padding: 1rem; text-align: center; color: var(--text-muted);">No hay carreras registradas.</td></tr>';
                return;
            }

            carreras.forEach(carrera => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${carrera.id_carrera}</td>
                    <td>${carrera.nombre}</td>
                    <td>
                        <button type="button" class="edit-btn" onclick="editCarrera(${carrera.id_carrera})">Editar</button>
                        <button type="button" class="delete-btn" onclick="deleteCarrera(${carrera.id_carrera})">Eliminar</button>
                    </td>
                `;
                carreraTableBody.appendChild(row);
            });
        }

        window.editCarrera = function (carreraId) {
            const carrera = currentCarreras.find(item => item.id_carrera === carreraId);
            if (carrera) {
                openCarreraForm(carrera);
            }
        };

        window.deleteCarrera = async function (carreraId) {
            if (!confirm('¿Seguro que deseas eliminar esta carrera?')) {
                return;
            }

            try {
                const response = await fetch(`/carreras/${carreraId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    await loadCarreras();
                    populateCarreraSelect(currentCarreras);
                    showSuccessCarrera(data.message || 'Carrera eliminada');
                } else {
                    showErrorCarrera(data.message || 'Error al eliminar la carrera');
                }
            } catch (error) {
                console.error('Error eliminando carrera:', error);
                showErrorCarrera('Error al eliminar la carrera');
            }
        };

        carreraForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nombre = carreraNombre.value.trim();
            if (!nombre) {
                showErrorCarrera('Ingresa el nombre de la carrera');
                return;
            }

            const method = editingCarreraId ? 'PUT' : 'POST';
            const url = editingCarreraId ? `/carreras/${editingCarreraId}` : '/carreras';
            const payload = { nombre };

            try {
                // Obtener el token CSRF del meta tag o del campo hidden del formulario
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                  document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    showErrorCarrera('Error de seguridad: No se pudo obtener el token CSRF');
                    return;
                }

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await response.json();
                if (!response.ok) {
                    const message = data.errors ? Object.values(data.errors).flat()[0] : data.message || 'Error al guardar la carrera';
                    showErrorCarrera(message);
                    return;
                }

                await loadCarreras();
                showSuccessCarrera(data.message || 'Carrera guardada exitosamente');
                setTimeout(() => closeCarreraModal(), 1500);
            } catch (error) {
                console.error('Error:', error);
                showErrorCarrera('Error al guardar la carrera');
            }
        });

        function showSuccessCarrera(message) {
            successCarreraMessage.textContent = '✓ ' + message;
            successCarreraMessage.classList.add('show');
            errorCarreraMessage.classList.remove('show');
        }

        function showErrorCarrera(message) {
            errorCarreraMessage.textContent = '✗ ' + message;
            errorCarreraMessage.classList.add('show');
            successCarreraMessage.classList.remove('show');
        }

        function openAspiranteForm(aspirante = null) {
            editingAspiranteId = aspirante ? aspirante.id_aspirantes : null;
            aspiranteModal.classList.add('show');
            aspiranteForm.reset();
            successAspiranteMessage.classList.remove('show');
            errorAspiranteMessage.classList.remove('show');

            if (editingAspiranteId) {
                aspiranteModalTitle.textContent = 'Editar Aspirante';
                saveAspiranteButton.textContent = 'Actualizar Aspirante';
                aspiranteIdInput.value = aspirante.id_aspirantes;
                aspiranteFolio.value = aspirante.folio;
                aspiranteCurp.value = aspirante.curp;
                aspiranteNombre.value = aspirante.nombre;
                aspiranteApPaterno.value = aspirante.ap_paterno;
                aspiranteApMaterno.value = aspirante.ap_materno || '';
                aspiranteFechaNacimiento.value = formatDateForInput(aspirante.fecha_nacimiento);
                aspiranteEmail.value = aspirante.email || '';
                aspiranteTelefono.value = aspirante.telefono || '';
                aspiranteCarrera.value = aspirante.id_carrera || '';
                aspirantePromedio.value = aspirante.promedio_bachillerato ?? '';
                aspiranteEstatus.value = aspirante.estatus || 'Pendiente';
                aspiranteDocumentosCompletos.value = aspirante.documentos_completos ?? 0;
                aspiranteObservaciones.value = aspirante.observaciones || '';
            } else {
                aspiranteModalTitle.textContent = 'Agregar Nuevo Aspirante';
                saveAspiranteButton.textContent = 'Guardar Aspirante';
                aspiranteIdInput.value = '';
            }
        }

        function closeAspiranteModal() {
            aspiranteModal.classList.remove('show');
            editingAspiranteId = null;
            aspiranteForm.reset();
            successAspiranteMessage.classList.remove('show');
            errorAspiranteMessage.classList.remove('show');
            aspiranteModalTitle.textContent = 'Agregar Nuevo Aspirante';
            saveAspiranteButton.textContent = 'Guardar Aspirante';
        }

        function openAlumnoForm(alumno = null) {
            const isAlumno = alumno && (alumno.id_alumnos || alumno.id);
            const isAspirante = alumno && alumno.id_aspirantes;
            editingAlumnoId = isAlumno ? (alumno.id_alumnos ?? alumno.id) : null;

            alumnoModal.classList.add('show');
            alumnoForm.reset();
            alumnoAspiranteSearch.value = '';
            alumnoAspirante.value = '';
            filterAlumnoAspiranteOptions();
            successAlumnoMessage.classList.remove('show');
            errorAlumnoMessage.classList.remove('show');

            if (isAlumno) {

                alumnoModalTitle.textContent = 'Editar Alumno';
                saveAlumnoButton.textContent = 'Actualizar Alumno';
                alumnoIdInput.value = editingAlumnoId;
                alumnoMatricula.value = alumno.matricula || '';
                alumnoCurp.value = (alumno.curp || '').toUpperCase();
                alumnoNombre.value = alumno.nombre || '';
                alumnoApPaterno.value = alumno.ap_paterno || '';
                alumnoApMaterno.value = alumno.ap_materno || '';
                alumnoFechaNacimiento.value = formatDateForInput(alumno.fecha_nacimiento);
                alumnoEmail.value = alumno.email || '';
                alumnoTelefono.value = alumno.telefono || '';
                alumnoCarrera.value = alumno.id_carrera || '';
                alumnoEstatus.value = alumno.estatus || 'Activo';

            } else if (isAspirante) {

                alumnoModalTitle.textContent = 'Crear Alumno desde Aspirante';
                saveAlumnoButton.textContent = 'Guardar Alumno';
                alumnoIdInput.value = '';
                alumnoAspirante.value = alumno.id_aspirantes;
                fillAlumnoFromAspirante(alumno);
            } else {
                alumnoModalTitle.textContent = 'Agregar Nuevo Alumno';
                saveAlumnoButton.textContent = 'Guardar Alumno';
                alumnoIdInput.value = '';
            }
        }

        function closeAlumnoModal() {
            alumnoModal.classList.remove('show');
            editingAlumnoId = null;
            alumnoForm.reset();
            successAlumnoMessage.classList.remove('show');
            errorAlumnoMessage.classList.remove('show');
            alumnoModalTitle.textContent = 'Agregar Nuevo Alumno';
            saveAlumnoButton.textContent = 'Guardar Alumno';
        }

        function isAspiranteInscrito(aspirante) {
            return currentAlumnos.some(alumno => {
                if (aspirante.id_aspirantes && alumno.id_aspirante) {
                    return Number(alumno.id_aspirante) === Number(aspirante.id_aspirantes);
                }
                return (alumno.curp || '').toUpperCase() === (aspirante.curp || '').toUpperCase();
            });
        }

        function renderAspirantesForAlumnosPanel(aspirantes) {
            if (!aspiranteAlumnosTableBody) {
                return;
            }

            if (totalAspirantesEnAlumnosCount) {
                totalAspirantesEnAlumnosCount.textContent = aspirantes.length;
            }

            aspiranteAlumnosTableBody.innerHTML = '';

            if (aspirantes.length === 0) {
                aspiranteAlumnosTableBody.innerHTML = '<tr><td colspan="8" style="padding: 1rem; text-align: center; color: var(--text-muted);">No hay aspirantes registrados.</td></tr>';
                return;
            }

            aspirantes.forEach(aspirante => {
                const inscrito = isAspiranteInscrito(aspirante);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${aspirante.folio || ''}</td>
                    <td>${aspirante.nombre} ${aspirante.ap_paterno} ${aspirante.ap_materno || ''}</td>
                    <td>${aspirante.carrera ? aspirante.carrera.nombre : 'Sin carrera'}</td>
                    <td>${aspirante.curp || ''}</td>
                    <td>${formatDateForDisplay(aspirante.fecha_nacimiento)}</td>
                    <td>${aspirante.email || ''}</td>
                    <td>${aspirante.telefono || ''}</td>
                    <td>${inscrito ? 'Inscrito' : (aspirante.estatus || 'Pendiente')}</td>
                    <td>
                        ${!inscrito ? `<button type="button" class="btn btn-secondary" onclick="convertirAspiranteAAlumno(${aspirante.id_aspirantes})">Convertir a Alumno</button>` : ''}
                        <button type="button" class="edit-btn" onclick="editAspirante(${aspirante.id_aspirantes})">Editar</button>
                        <button type="button" class="delete-btn" onclick="deleteAspirante(${aspirante.id_aspirantes})">Eliminar</button>
                    </td>
                `;
                aspiranteAlumnosTableBody.appendChild(row);
            });
        }

        window.convertirAspiranteAAlumno = function (aspiranteId) {
            const aspirante = currentAspirantes.find(item => item.id_aspirantes === Number(aspiranteId));
            if (aspirante) {
                openAlumnoForm(aspirante);
            } else {
                showErrorAlumno('No se encontró el aspirante seleccionado');
            }
        };

        async function loadAspirantes() {
            try {
                const response = await fetch('/aspirantes/list', { headers: { 'Accept': 'application/json' } });
                const data = await response.json();
                if (response.ok && data.aspirantes) {
                    currentAspirantes = data.aspirantes;
                    renderAspirantes(data.aspirantes);
                    populateAlumnoAspiranteSelect(data.aspirantes);
                    renderAspirantesForAlumnosPanel(data.aspirantes);
                }
            } catch (error) {
                console.error('Error cargando aspirantes:', error);
            }
        }

        function renderAspirantes(aspirantes) {
            currentAspirantes = aspirantes;
            totalAspirantesCount.textContent = aspirantes.length;
            acceptedAspirantesCount.textContent = aspirantes.filter(a => a.estatus === 'Aceptado').length;
            aspiranteTableBody.innerHTML = '';

            if (aspirantes.length === 0) {
                aspiranteTableBody.innerHTML = '<tr><td colspan="6" style="padding: 1rem; text-align: center; color: var(--text-muted);">No hay aspirantes registrados.</td></tr>';
                return;
            }

            aspirantes.forEach(aspirante => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${aspirante.folio}</td>
                    <td>${aspirante.nombre} ${aspirante.ap_paterno} ${aspirante.ap_materno || ''}</td>
                    <td>${aspirante.carrera ? aspirante.carrera.nombre : 'Sin carrera'}</td>
                    <td>${formatDateForDisplay(aspirante.fecha_nacimiento)}</td>
                    <td>${aspirante.estatus}</td>
                    <td>
                        <button type="button" class="edit-btn" onclick="editAspirante(${aspirante.id_aspirantes})">Editar</button>
                        <button type="button" class="delete-btn" onclick="deleteAspirante(${aspirante.id_aspirantes})">Eliminar</button>
                    </td>
                `;
                aspiranteTableBody.appendChild(row);
            });
        }

        window.editAspirante = function (aspiranteId) {
            const aspirante = currentAspirantes.find(item => item.id_aspirantes === aspiranteId);
            if (aspirante) {
                openAspiranteForm(aspirante);
            }
        };

        window.deleteAspirante = async function (aspiranteId) {
            if (!confirm('¿Seguro que deseas eliminar este aspirante?')) {
                return;
            }

            try {
                const response = await fetch(`/aspirantes/${aspiranteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    await loadAspirantes();
                    showSuccessAspirante(data.message || 'Aspirante eliminado');
                } else {
                    showErrorAspirante(data.message || 'Error al eliminar el aspirante');
                }
            } catch (error) {
                console.error('Error eliminando aspirante:', error);
                showErrorAspirante('Error al eliminar el aspirante');
            }
        };

        aspiranteForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const payload = {
                folio: aspiranteFolio.value.trim(),
                curp: aspiranteCurp.value.trim().toUpperCase(),
                nombre: aspiranteNombre.value.trim(),
                ap_paterno: aspiranteApPaterno.value.trim(),
                ap_materno: aspiranteApMaterno.value.trim(),
                fecha_nacimiento: aspiranteFechaNacimiento.value || null,
                email: aspiranteEmail.value.trim() || null,
                telefono: aspiranteTelefono.value.trim() || null,
                id_carrera: aspiranteCarrera.value,
                promedio_bachillerato: aspirantePromedio.value ? Number(aspirantePromedio.value) : null,
                estatus: aspiranteEstatus.value,
                documentos_completos: aspiranteDocumentosCompletos.value !== '' ? Number(aspiranteDocumentosCompletos.value) : 0,
                observaciones: aspiranteObservaciones.value.trim() || null,
            };

            if (!payload.folio || !payload.curp || !payload.nombre || !payload.ap_paterno || !payload.id_carrera) {
                showErrorAspirante('Completa los campos obligatorios');
                return;
            }

            if (!/^[A-Z0-9]{18}$/.test(payload.curp)) {
                showErrorAspirante('La CURP debe tener exactamente 18 caracteres alfanuméricos');
                return;
            }

            const method = editingAspiranteId ? 'PUT' : 'POST';
            const url = editingAspiranteId ? `/aspirantes/${editingAspiranteId}` : '/aspirantes';

            try {
                // Obtener el token CSRF del meta tag o del campo hidden del formulario
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                  document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    showErrorAspirante('Error de seguridad: No se pudo obtener el token CSRF');
                    return;
                }

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await response.json();
                if (!response.ok) {
                    const message = data.errors ? Object.values(data.errors).flat()[0] : data.message || 'Error al guardar el aspirante';
                    showErrorAspirante(message);
                    return;
                }

                await loadAspirantes();
                showSuccessAspirante(data.message || 'Aspirante guardado exitosamente');
                setTimeout(() => closeAspiranteModal(), 1500);
            } catch (error) {
                console.error('Error:', error);
                showErrorAspirante('Error al guardar el aspirante');
            }
        });

        function showSuccessAspirante(message) {
            successAspiranteMessage.textContent = '✓ ' + message;
            successAspiranteMessage.classList.add('show');
            errorAspiranteMessage.classList.remove('show');
        }

        function showErrorAspirante(message) {
            errorAspiranteMessage.textContent = '✗ ' + message;
            errorAspiranteMessage.classList.add('show');
            successAspiranteMessage.classList.remove('show');
        }

        async function loadAlumnos() {
            try {
                // Cargar aspirantes para el select del formulario y la tabla
                if (currentAspirantes.length === 0) {
                    await loadAspirantes();
                }

                const response = await fetch('/alumnos/list', { headers: { 'Accept': 'application/json' } });
                const data = await response.json();
                if (response.ok && data.alumnos) {
                    renderAlumnos(data.alumnos);
                }
                if (response.ok && data.aspirantes) {
                    currentAspirantes = data.aspirantes;
                    populateAlumnoAspiranteSelect(data.aspirantes);
                    renderAspirantesForAlumnosPanel(data.aspirantes);
                }
            } catch (error) {
                console.error('Error cargando alumnos:', error);
            }
        }

        function renderAlumnos(alumnos) {
            currentAlumnos = alumnos;
            totalAlumnosCount.textContent = alumnos.length;
            activeAlumnosCount.textContent = alumnos.filter(a => (a.estatus || a.estado) === 'Activo').length;
            alumnoTableBody.innerHTML = '';

            // Actualizar tabla de aspirantes para mostrar estado de inscripción
            if (currentAspirantes.length) {
                renderAspirantesForAlumnosPanel(currentAspirantes);
            }

            if (alumnos.length === 0) {
                alumnoTableBody.innerHTML = '<tr><td colspan="8" style="padding: 1rem; text-align: center; color: var(--text-muted);">No hay alumnos inscritos.</td></tr>';
                return;
            }

            alumnos.forEach(alumno => {
                const alumnoId = alumno.id ?? alumno.id_alumnos ?? null;
                const nombreCompleto = [alumno.nombre, alumno.ap_paterno, alumno.ap_materno]
                    .filter(Boolean)
                    .join(' ');
                const carreraNombre = alumno.carrera?.nombre || alumno.carrera?.nombre_carrera || 'Sin carrera';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${alumno.matricula || ''}</td>
                    <td>${nombreCompleto}</td>
                    <td>${carreraNombre}</td>
                    <td>${alumno.curp || ''}</td>
                    <td>${formatDateForDisplay(alumno.fecha_nacimiento)}</td>
                    <td>${alumno.email || ''}</td>
                    <td>${alumno.telefono || ''}</td>
                    <td>${alumno.estatus || 'Activo'}</td>
                    <td>
                        <button type="button" class="edit-btn" onclick="editAlumno(${alumnoId})">Editar</button>
                        <button type="button" class="delete-btn" onclick="deleteAlumno(${alumnoId})">Eliminar</button>
                    </td>
                `;
                alumnoTableBody.appendChild(row);
            });
        }

        window.editAlumno = function (alumnoId) {

            
            const alumno = currentAlumnos.find(item => {
                const itemId = item.id_alumnos ?? item.id;
                return itemId === Number(alumnoId);
            });
            

            
            if (alumno) {
                openAlumnoForm(alumno);
            } else {

                alert('No se pudo encontrar el alumno. Por favor, recarga la página.');
            }
        };

        window.deleteAlumno = async function (alumnoId) {
            if (!confirm('¿Seguro que deseas eliminar este alumno?')) {
                return;
            }

            try {
                const response = await fetch(`/alumnos/${alumnoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    await loadAlumnos();
                    showSuccessAlumno(data.message || 'Alumno eliminado');
                } else {
                    showErrorAlumno(data.message || 'Error al eliminar el alumno');
                }
            } catch (error) {
                console.error('Error eliminando alumno:', error);
                showErrorAlumno('Error al eliminar el alumno');
            }
        };

        alumnoForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const selectedAspiranteId = Number(alumnoAspirante.value);
            const selectedAspirante = selectedAspiranteId
                ? (currentAspirantes.find(item => item.id_aspirantes === selectedAspiranteId)
                    || alumnoAspiranteSource.find(item => item.id_aspirantes === selectedAspiranteId))
                : null;

            const payload = {
                matricula: alumnoMatricula.value.trim(),
                curp: (alumnoCurp.value || (selectedAspirante?.curp || '')).trim().toUpperCase(),
                nombre: (alumnoNombre.value || (selectedAspirante?.nombre || '')).trim(),
                ap_paterno: (alumnoApPaterno.value || (selectedAspirante?.ap_paterno || '')).trim(),
                ap_materno: (alumnoApMaterno.value || (selectedAspirante?.ap_materno || '')).trim(),
                fecha_nacimiento: alumnoFechaNacimiento.value || formatDateForInput(selectedAspirante?.fecha_nacimiento) || null,
                email: (alumnoEmail.value || (selectedAspirante?.email || '')).trim() || null,
                telefono: (alumnoTelefono.value || (selectedAspirante?.telefono || '')).trim() || null,
                id_carrera: alumnoCarrera.value || selectedAspirante?.id_carrera || '',
                estatus: alumnoEstatus.value,
                aspirante_id: selectedAspiranteId || null,
            };

            if (!payload.matricula || !payload.curp || !payload.nombre || !payload.ap_paterno || !payload.id_carrera) {
                showErrorAlumno('Completa los campos obligatorios');
                return;
            }

            if (!/^[A-Z0-9]{18}$/.test(payload.curp)) {
                showErrorAlumno('La CURP debe tener exactamente 18 caracteres alfanuméricos');
                return;
            }

            const method = editingAlumnoId ? 'PUT' : 'POST';
            const url = editingAlumnoId ? `/alumnos/${editingAlumnoId}` : '/alumnos';

            try {
                // Obtener el token CSRF del meta tag o del campo hidden del formulario
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                  document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    showErrorAlumno('Error de seguridad: No se pudo obtener el token CSRF');
                    return;
                }

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await response.json();
                if (!response.ok) {
                    const message = data.errors ? Object.values(data.errors).flat()[0] : data.message || 'Error al guardar el alumno';
                    showErrorAlumno(message);
                    return;
                }

                await loadAlumnos();
                await loadAspirantes();
                showSuccessAlumno(data.message || 'Alumno guardado exitosamente');
                setTimeout(() => closeAlumnoModal(), 1500);
            } catch (error) {
                console.error('Error:', error);
                showErrorAlumno('Error al guardar el alumno');
            }
        });

        function showSuccessAlumno(message) {
            successAlumnoMessage.textContent = '✓ ' + message;
            successAlumnoMessage.classList.add('show');
            errorAlumnoMessage.classList.remove('show');
        }

        function showErrorAlumno(message) {
            errorAlumnoMessage.textContent = '✗ ' + message;
            errorAlumnoMessage.classList.add('show');
            successAlumnoMessage.classList.remove('show');
        }

        function populateCarreraSelect(carreras) {
            aspiranteCarrera.innerHTML = '<option value="">Selecciona una carrera</option>';
            carreras.forEach(carrera => {
                aspiranteCarrera.innerHTML += `<option value="${carrera.id_carrera}">${carrera.nombre}</option>`;
            });
        }

        function populateAlumnoAspiranteSelect(aspirantes) {
            alumnoAspiranteSource = aspirantes || [];
            filterAlumnoAspiranteOptions();
        }

        function populateAlumnoCarreraSelect(carreras) {
            alumnoCarrera.innerHTML = '<option value="">Selecciona una carrera</option>';
            carreras.forEach(carrera => {
                alumnoCarrera.innerHTML += `<option value="${carrera.id_carrera}">${carrera.nombre}</option>`;
            });
        }

        function onEmailChange() {
            const email = userEmail.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(email)) {
                confirmEmailGroup.classList.add('show');
            } else if (!editingUserId) {
                confirmEmailGroup.classList.remove('show');
            }
        }

        userForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('userName').value.trim();
            const email = document.getElementById('userEmail').value.trim();
            const emailConfirm = document.getElementById('userEmailConfirm').value.trim();
            const password = document.getElementById('userPassword').value;
            const passwordConfirm = document.getElementById('userPasswordConfirm').value;

            if (!name) {
                showError('Por favor, completa el nombre');
                return;
            }

            if (!email) {
                showError('Por favor, ingresa un correo electrónico');
                return;
            }

            if (email !== emailConfirm) {
                showError('Los correos electrónicos no coinciden');
                return;
            }

            if (editingUserId === null && (!password || password.length < 8)) {
                showError('La contraseña debe tener mínimo 8 caracteres');
                return;
            }

            if (password && password.length < 8) {
                showError('La contraseña debe tener mínimo 8 caracteres');
                return;
            }

            if (password !== passwordConfirm) {
                showError('Las contraseñas no coinciden');
                return;
            }

            const method = editingUserId ? 'PUT' : 'POST';
            const url = editingUserId ? `/users/${editingUserId}` : '/users';
            const payload = {
                name,
                email,
                password: password || undefined,
                password_confirmation: passwordConfirm || undefined,
            };

            try {
                // Obtener el token CSRF del meta tag o del campo hidden del formulario
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                  document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    showError('Error de seguridad: No se pudo obtener el token CSRF');
                    return;
                }

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();
                if (!response.ok) {
                    const message = data.errors ? Object.values(data.errors).flat()[0] : data.message || 'Error al guardar el usuario';
                    showError(message);
                    return;
                }

                await loadUsers();
                showSuccess(data.message || 'Usuario guardado exitosamente');
                setTimeout(() => closeUserModal(), 1500);
            } catch (error) {
                console.error('Error:', error);
                showError('Error al guardar el usuario');
            }
        });

        async function loadUsers() {
            try {
                const response = await fetch('/users/list', {
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (response.ok && data.users) {
                    renderUsers(data.users);
                }
            } catch (error) {
                console.error('Error cargando usuarios:', error);
            }
        }

        function renderUsers(users) {
            currentUsers = users;
            totalUsersCount.textContent = users.length;
            userTableBody.innerHTML = '';

            if (users.length === 0) {
                userTableBody.innerHTML = '<tr><td colspan="4" style="padding: 1rem; text-align: center; color: var(--text-muted);">No hay usuarios registrados.</td></tr>';
                return;
            }

            users.forEach(user => {
                const createdAt = user.created_at ? new Date(user.created_at).toLocaleDateString('es-ES') : '-';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${createdAt}</td>
                    <td>
                        <button type="button" class="edit-btn" onclick="editUser(${user.id})">Editar</button>
                        <button type="button" class="delete-btn" onclick="deleteUser(${user.id})">Eliminar</button>
                    </td>
                `;
                userTableBody.appendChild(row);
            });
        }

        window.editUser = function (userId) {
            const user = currentUsers.find(item => item.id === userId);
            if (user) {
                openUserForm(user);
            }
        };

        window.deleteUser = async function (userId) {
            if (!confirm('¿Seguro que deseas eliminar este usuario?')) {
                return;
            }

            try {
                const response = await fetch(`/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    await loadUsers();
                    showSuccess(data.message || 'Usuario eliminado');
                } else {
                    showError(data.message || 'Error al eliminar el usuario');
                }
            } catch (error) {
                console.error('Error eliminando usuario:', error);
                showError('Error al eliminar el usuario');
            }
        };

        function showSuccess(message) {
            const successMsg = document.getElementById('successMessage');
            successMsg.textContent = '✓ ' + message;
            successMsg.classList.add('show');
            document.getElementById('errorMessage').classList.remove('show');
        }

        function showError(message) {
            const errorMsg = document.getElementById('errorMessage');
            errorMsg.textContent = '✗ ' + message;
            errorMsg.classList.add('show');
            document.getElementById('successMessage').classList.remove('show');
        }

        function switchPanel(panelId, element) {
            const panels = document.querySelectorAll('.content-panel');
            panels.forEach(panel => panel.classList.remove('active'));

            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => item.classList.remove('active'));

            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => item.classList.remove('active'));

            const activePanel = document.getElementById(`panel-${panelId}`);
            if (activePanel) {
                activePanel.classList.add('active');
            }

            if (element) {
                element.classList.add('active');
            }

            // Ajustar tamaño del card cuando se muestra la vista de Aspirantes o Alumnos
            const card = document.querySelector('.card');
            if (panelId === 'aspirantes' || panelId === 'alumnos') {
                card.classList.add('full-screen');
            } else {
                card.classList.remove('full-screen');
            }

            menuDropdown.classList.remove('show');

            if (panelId === 'alumnos') {
                loadAlumnos();
            } else if (panelId === 'aspirantes') {
                loadAspirantes();
            }
        }

        renderUsers(initialUsers);
        loadCarreras();
        loadAspirantes();
        loadAlumnos();

        // Abrir la pestaña Alumnos por defecto
        (function openDefaultPanel() {
            const sidebarAlumnosBtn = document.getElementById('sidebarAlumnosBtn');
            if (sidebarAlumnosBtn) {
                switchPanel('alumnos', sidebarAlumnosBtn);
            } else {
                // fallback: abrir dashboard
                switchPanel('dashboard', document.querySelector('.sidebar-item'));
            }
        })();
    </script>
</body>
</html>