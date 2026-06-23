<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title> Analizador Léxico-Sintáctico</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5e6ea 0%, #f0dde2 100%);
            font-family: 'Segoe UI', 'Inter', system-ui, -apple-system, 'Poppins', sans-serif;
            min-height: 100vh;
            padding: 20px;
        }

        /* CONTENEDOR DEL MENÚ */
        .navbar{
            background: #b1546b;
            height: 45px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 20px;
        }
        /* LOGO */
        .logo{
            display:flex;
            align-items:center;
            gap:10px;
            color:white;
            font-size:14px;
            font-weight:bold;
        }

        .logo-icon{
            width:28px;
            height:28px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:16px;
        }

        /* MENÚ */
        .menu{
            list-style:none;
            display:flex;
            align-items:center;
            height:100%;
        }

        .menu li{
            position:relative;
        }

        .menu a{
            display:flex;
            align-items:center;
            height:45px;
            padding:0 18px;
            color:white;
            text-decoration:none;
            font-size:13px;
            transition:.3s;
        }

        .menu a:hover{
            background:rgba(255,255,255,.08);
        }

        /* PESTAÑA ACTIVA */
        .menu .active a{
            color:#f77bde;
        }

        .menu .active::after{
            content:'';
            position:absolute;
            bottom:0;
            left:0;
            width:100%;
            height:3px;
            background:#f77bde;
        }

        /* Contenedor principal */
        .app-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            background: #fff8fa;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(160, 80, 100, 0.25);
            overflow: hidden;
            border: 1px solid #f5d0d9;
        }

        /* Info grid dentro del menú */
        .info-grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .info-card-menu {
            background: #fff5f8;
            padding: 20px;
            border-radius: 24px;
            border-left: 4px solid #e891a5;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .info-card-menu h4 {
            color: #c45c74;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .info-card-menu p, .info-card-menu li {
            color: #8e5e6b;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .grammar-list {
            list-style: none;
            padding-left: 0;
        }

        .grammar-list li {
            padding: 6px 0;
            border-bottom: 1px solid #fce4ea;
        }

        /* ========== NUEVAS VIÑETAS MEJORADAS ========== */
        /* Estilo para listas con viñetas personalizadas y efecto de profundidad */
        .custom-list {
            list-style: none;
            padding-left: 0;
            margin: 15px 0;
        }
        .custom-list li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 16px;
            line-height: 1.5;
            color: #6b3f4b;
            font-size: 0.9rem;
            transition: transform 0.2s ease, padding-left 0.2s ease;
        }
        /* Efecto hover en cada ítem de lista */
        .custom-list li:hover {
            transform: translateX(5px);
            padding-left: 32px;
        }
        /* Viñeta con icono + gradiente y sombra */
        .custom-list li::before {
            content: "▹";
            position: absolute;
            left: 0;
            top: 0;
            color: #e891a5;
            font-size: 1.1rem;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, color 0.2s;
        }
        .custom-list li:hover::before {
            transform: scale(1.2);
            color: #c45c74;
        }

        /* Subtítulo decorado para secciones */
        .section-subtitle {
            font-size: 1.2rem;
            font-weight: 600;
            color: #b1546b;
            background: linear-gradient(120deg, #ffe0e6, #fff0f4);
            display: inline-block;
            padding: 6px 18px;
            border-radius: 40px;
            margin: 16px 0 12px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            letter-spacing: -0.3px;
        }

        /* Tarjetas con relieve interior (efecto neumórfico sutil) */
        .depth-card {
            background: #fffafc;
            border-radius: 28px;
            box-shadow: inset 0 1px 2px rgba(255,255,255,0.8), 0 8px 20px rgba(160,80,100,0.12);
            transition: all 0.2s ease;
        }
        .depth-card:hover {
            box-shadow: inset 0 1px 2px rgba(255,255,255,0.9), 0 12px 28px rgba(160,80,100,0.2);
            transform: translateY(-2px);
        }

        /* Panel de información (ARRIBA) */
        .info-panel {
            background: linear-gradient(135deg, #ffe0e6 0%, #ffd0d8 100%);
            padding: 22px 30px;
            border-bottom: 3px solid #f5a9b8;
        }

        .info-title {
            text-align: center;
            font-size: 1.9rem;
            font-weight: 700;
            color: #b1546b;
            margin-bottom: 18px;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .info-title span {
            font-size: 2rem;
        }

        /* Panel principal editor */
        .main-panel {
            padding: 28px 32px;
        }

        .editor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .editor-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .editor-title h2 {
            color: #cc7a8f;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .badge-pro {
            background: linear-gradient(135deg, #e8b4c2, #e2a1b2);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            color: white;
            font-weight: 600;
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.25s ease;
            font-family: inherit;
            background: #ffd9e1;
            color: #aa5e74;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #e8b4c2;
            color: #5e2e3a;
            box-shadow: 0 4px 12px rgba(200, 100, 120, 0.2);
        }

        .btn-primary:hover {
            background: #e2a1b2;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #f3c2ce;
        }

        .btn-outline:hover {
            background: #ffeef2;
            transform: translateY(-1px);
        }

        /* Editor con números de línea - MEJORADO */
        .editor-modern {
            border-radius: 24px;
            border: 2px solid #ffcdd8;
            background: #fff9fb;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .code-header-stats {
            background: #fff0f4;
            padding: 8px 20px;
            border-bottom: 1px solid #ffd4de;
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: #c27a8e;
            font-family: monospace;
        }

        .code-area-wrapper {
            display: flex;
            min-height: 420px;
        }

        .line-numbers {
            background: #fff0f4;
            padding: 18px 12px;
            text-align: right;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 13px;
            color: #d68ea2;
            border-right: 2px solid #ffd4de;
            user-select: none;
            line-height: 1.6;
            white-space: pre;
            min-width: 55px;
        }

        .code-textarea {
            flex: 1;
            background: #fff9fb;
        }

        .code-textarea textarea {
            width: 100%;
            height: 100%;
            min-height: 420px;
            padding: 18px 20px;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 13px;
            line-height: 1.6;
            background: #fff9fb;
            border: none;
            color: #3a2a30;
            resize: vertical;
            outline: none;
        }

        /* Resultados mejorados */
        .results-section {
            margin-top: 20px;
        }

        .tabs-modern {
            display: flex;
            gap: 8px;
            background: #fff0f4;
            padding: 12px 24px 0 24px;
            border-radius: 20px 20px 0 0;
        }

        .tab-modern {
            padding: 12px 28px;
            background: transparent;
            border: none;
            font-weight: 600;
            cursor: pointer;
            border-radius: 20px 20px 0 0;
            color: #ad7a88;
            font-family: inherit;
            transition: 0.2s;
            font-size: 0.9rem;
        }

        .tab-modern.active {
            background: #fffafc;
            color: #c45c74;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.02);
        }

        .content-modern {
            background: #fffafc;
            border-radius: 0 0 24px 24px;
            padding: 24px;
            min-height: 400px;
            max-height: 500px;
            overflow: auto;
            border-top: 2px solid #ffd4de;
        }

        .token-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .token-table th {
            background: #ffe2e9;
            padding: 12px;
            text-align: left;
            color: #b6536e;
            position: sticky;
            top: 0;
            font-weight: 600;
        }

        .token-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ffe0e6;
            color: #5c3f48;
        }

        .token-table tr:hover {
            background: #fff0f5;
        }

        .error-item {
            background: #fff5f7;
            border-left: 4px solid #e86f8f;
            padding: 14px 18px;
            margin-bottom: 12px;
            border-radius: 14px;
            font-family: monospace;
            font-size: 13px;
        }

        .ast-visual {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: #fffbfc;
            padding: 20px;
            border-radius: 16px;
            white-space: pre;
            overflow-x: auto;
        }

        .stats-footer {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f8dae2;
            font-size: 0.7rem;
            color: #c27a8e;
        }

        footer {
            text-align: center;
            padding: 18px;
            background: #fff0f4;
            font-size: 0.75rem;
            color: #be7f92;
        }

        @media (max-width: 800px) {
            .top-menu { padding: 0 16px; }
            .menu-option { padding: 12px 20px; font-size: 0.85rem; }
            .main-panel { padding: 20px; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
    <div class="logo">
        <div class="logo-icon"> <i class="fa-brands fa-centos"></i> </div>
        <span>Léxico-Sintáctico</span>
    </div>

    <ul class="menu">
        <li>
            <a href="guia.php">Guía rapida</a>
        </li>

        <li class="active">
            <a href="index.php">Analizador</a>
        </li>

        <li>
            <a href="sirve.php">Para Qué Sirve</a>
        </li>

        <li>
            <a href="manual.php">Manual de Uso</a>
        </li>
    </ul>
</nav>

<!-- INFORMACIÓN ANEXADA ARRIBA -->
    <div class="info-panel">
        <div class="info-title">
            <span> </span> ¿PARA QUE SIRVE? <span> </span>
        </div>
    </div>

<div class="app-wrapper">

    <!-- Contenido 1: Información principal (mejorado con viñetas modernas) -->
    <div id="menu-info" class="menu-content active">
        <div class="info-grid-menu">
            <div class="info-card-menu depth-card">
                <h1 style="font-size: 1.7rem; margin-bottom: 6px;"> ¿Para qué sirve este analizador? </h1>
                <p style="margin-bottom: 10px; color:#9e6577;"> <br> </p>
                <hr style="border-color:#fad4df; margin: 10px 0 20px;">

                <br>
                <p style="font-size: 1rem; color:#5e3643; font-weight: 500;">El propósito de este proyecto es ilustrar las dos primeras fases de un compilador moderno: <strong>el análisis léxico y el análisis sintáctico</strong>.
                    Al juntar estas dos etapas, actúan como un traductor entre el código que escribe una persona y la estructura lógica que una computadora puede procesar y ejecutar.
                </p>
                <div style="margin-top: 20px;">
                    <div class="section-subtitle"><i class="fas fa-microchip"></i>  Beneficios clave</div>
                    <ul class="custom-list">
                        <li><strong>Validación automática</strong> — Detecta errores de escritura y estructura en tu código.</li>
                        <li><strong>Base para compiladores</strong> — Comprende el flujo real que siguen lenguajes como Python o Java.</li>
                        <li><strong>Visualización didáctica</strong> — Muestra tokens, errores y árbol AST para aprender jugando.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Contenido 2: Fases del Sistema -->
    <div id="menu-syntax" class="menu-content">
        <div class="info-grid-menu">
            <div class="info-card-menu depth-card">
                <h1 style="font-size: 1.7rem; margin-bottom: 6px;"> Fases del Sistema </h1>
                <p style="margin-bottom: 10px; color:#9e6577;"> <br> </p>
                <hr style="border-color:#fad4df; margin: 10px 0 20px;">
                
                <div class="section-subtitle"><i class="fas fa-search"></i>  1. Análisis Léxico</div>
                <ul class="custom-list">
                    <li><strong>¿Cuál es su función?</strong><br> Fase principal del compilador que lee el código fuente carácter por carácter y los agrupa en unidades llamadas <strong>tokens</strong>.</li>
                    <li><strong>¿Cómo funciona?</strong><br> Usa expresiones regulares para identificar patrones específicos: palabras reservadas, identificadores, números, operadores.</li>
                    <li><strong>Control de errores</strong><br> Si encuentra un carácter no reconocido (como '@' o '$'), lo marca como <span style="background:#ffe2e9; padding:2px 8px; border-radius:20px;">Error Léxico</span>.</li>
                </ul>

                <div class="section-subtitle"><i class="fas fa-code-branch"></i>  2. Análisis Sintáctico</div>
                <ul class="custom-list">
                    <li><strong>¿Cuál es su función?</strong><br> Toma los tokens generados y verifica que cumplan con el orden lógico y las reglas gramaticales del lenguaje.</li>
                    <li><strong>¿Cómo funciona?</strong><br> Se basa en Gramáticas Libres de Contexto. Comprueba que después de un tipo de dato venga un nombre, operador y valor coherente.</li>
                    <li><strong>Resultado Visual</strong><br> Si la estructura es correcta construye un <strong>Árbol de Sintaxis Abstracta (AST)</strong>; si falta un delimitador (como ;) dispara un error detallado.</li>
                </ul>
            </div>
        </div>
    </div>

    <br>

    <!-- Contenido 3: Importancia académica (viñetas con iconos dinámicos) -->
    <div id="menu-help" class="menu-content">
        <div class="info-grid-menu">
            <div class="info-card-menu depth-card">
                <h1 style="font-size: 1.7rem; margin-bottom: 6px;"> Importancia en el ámbito académico</h1>
                <p style="margin-bottom: 10px; color:#9e6577;"> <br> </p>
                <hr style="border-color:#fad4df; margin: 10px 0 20px;">

                <br>
                <ul class="custom-list">
                    <li><strong>Base del Software</strong><br> Permite entender cómo funcionan compiladores de Java o intérpretes de Python/JavaScript, para detectar errores antes de ejecutar.</li>
                    <li><strong>Diseño del Software</strong><br> Desarrollamos habilidades en automatización, procesamiento de lenguaje natural, análisis de archivos y creación de formatos de datos propios.</li>
                    <li><strong>Aplicación de la Teoría</strong><br> Practicamos cómo los Autómatas Finitos y la Gramática formal se convierten en software funcional.</li>
                </ul>
                
            </div>
        </div>
    </div>
    
    <footer> Proyecto Lenguajes y Autómatas I | Creado por Jenny Valverde</footer>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
        // Obtener la URL actual del navegador
        const currentUrl = window.location.href;
        
        // Seleccionar todos los elementos 'li' del menú
        const menuItems = document.querySelectorAll('.menu li');
        
        // Limpiar cualquier clase activa previa
        menuItems.forEach(item => item.classList.remove('active'));
        
        // Buscar coincidencia entre el href del enlace y la URL actual
        let matched = false;
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && currentUrl.includes(link.getAttribute('href'))) {
                item.classList.add('active');
                matched = true;
            }
        });

        // Si no se encuentra coincidencia (ej: raíz del servidor como http://localhost/), activar Analizador por defecto
        if (!matched) {
            const defaultActive = Array.from(menuItems).find(item => item.querySelector('a').getAttribute('href') === 'index.html');
            if (defaultActive) defaultActive.classList.add('active');
        }
    });

    // Menú superior 3 apartados
    document.querySelectorAll('.menu-option').forEach(opt=>{
        opt.addEventListener('click',()=>{
            document.querySelectorAll('.menu-option').forEach(o=>o.classList.remove('active'));
            opt.classList.add('active');
            const menuId=opt.getAttribute('data-menu');
            document.querySelectorAll('.menu-content').forEach(c=>c.classList.remove('active'));
            document.getElementById(`menu-${menuId}`).classList.add('active');
        });
    });

</script>
</body>
</html>