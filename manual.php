<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title> Manual de Usuario - Analizador Léxico-Sintáctico</title>
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

        .navbar {
            background: #b1546b;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .logo-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .menu {
            list-style: none;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .menu li {
            position: relative;
        }

        .menu a {
            display: flex;
            align-items: center;
            height: 45px;
            padding: 0 18px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            transition: .3s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, .08);
        }

        .menu .active a {
            color: #f77bde;
        }

        .menu .active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #f77bde;
        }

        .app-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            background: #fff8fa;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(160, 80, 100, 0.25);
            overflow: hidden;
            border: 1px solid #f5d0d9;
        }

        .top-menu {
            background: linear-gradient(135deg, #fff0f4 0%, #ffe8ee 100%);
            border-bottom: 2px solid #f3c2ce;
            padding: 0 32px;
            display: flex;
            gap: 8px;
        }

        .menu-option {
            padding: 18px 32px;
            font-size: 1rem;
            font-weight: 600;
            color: #b86a80;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            font-family: inherit;
            position: relative;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu-option i {
            font-size: 1.1rem;
            color: #c27a8e;
        }

        .menu-option:hover {
            color: #d4587a;
            background: rgba(255, 220, 230, 0.5);
        }

        .menu-option:hover i {
            color: #d4587a;
        }

        .menu-option.active {
            color: #c9456a;
        }

        .menu-option.active i {
            color: #c9456a;
        }

        .menu-option.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 3px;
            background: linear-gradient(90deg, #e891a5, #f0a5bb);
            border-radius: 3px;
        }

        .menu-content {
            display: none;
            padding: 28px 32px;
            background: #fffafc;
            border-bottom: 1px solid #f8dae2;
        }

        .menu-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

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

        .info-title i {
            font-size: 2rem;
            color: #b1546b;
        }

        .manual-content {
            padding: 10px 0;
        }

        .manual-section {
            margin-bottom: 30px;
            border-bottom: 1px solid #f8dae2;
            padding-bottom: 20px;
        }

        .manual-section:last-child {
            border-bottom: none;
        }

        .manual-section h2 {
            color: #b1546b;
            font-size: 1.5rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .manual-section h2 i {
            color: #b1546b;
            font-size: 1.4rem;
        }

        .manual-section h3 {
            color: #c45c74;
            font-size: 1.2rem;
            margin: 15px 0 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .manual-section h3 i {
            color: #c45c74;
            font-size: 1.1rem;
        }

        .manual-section p {
            color: #5c3f48;
            line-height: 1.7;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .manual-section ul {
            padding-left: 25px;
            margin-bottom: 15px;
        }

        .manual-section ul li {
            color: #5c3f48;
            line-height: 1.7;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .manual-section .highlight-box {
            background: #fff0f4;
            border-radius: 16px;
            padding: 16px 20px;
            margin: 15px 0;
            border-left: 4px solid #e891a5;
        }

        .requirement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin: 10px 0;
        }

        .requirement-item {
            background: #fff5f8;
            padding: 14px 18px;
            border-radius: 12px;
            border-left: 3px solid #e891a5;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .requirement-item i {
            color: #b1546b;
            font-size: 1.2rem;
            margin-top: 2px;
            min-width: 22px;
        }

        .requirement-item strong {
            color: #b1546b;
        }

        .requirement-item .req-text {
            color: #5c3f48;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .step-list {
            counter-reset: step;
            list-style: none;
            padding-left: 0;
        }

        .step-list li {
            counter-increment: step;
            padding: 12px 15px 12px 55px;
            position: relative;
            background: #fff5f8;
            margin-bottom: 10px;
            border-radius: 12px;
            font-size: 0.95rem;
            color: #5c3f48;
            line-height: 1.6;
        }

        .step-list li::before {
            content: counter(step);
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            background: #e891a5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .step-list li strong {
            color: #b1546b;
        }

        .step-list li .step-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
        }

        .step-list li .step-detail {
            display: block;
            margin-top: 4px;
            padding-left: 5px;
        }

        .example-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 15px 0;
        }

        .example-card {
            background: #fff5f8;
            border-radius: 16px;
            padding: 18px;
            border: 1px solid #fce4ea;
        }

        .example-card h4 {
            color: #c45c74;
            margin-bottom: 8px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .example-card h4 i {
            font-size: 1rem;
        }

        .example-card .code-block {
            background: #fff0f3;
            padding: 12px;
            border-radius: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            white-space: pre-wrap;
            color: #3a2a30;
            border: 1px solid #f8dae2;
            margin: 8px 0;
        }

        .example-card .result-block {
            background: #f5f0f2;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #5c3f48;
            margin-top: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            line-height: 1.6;
        }

        .example-card .result-block.success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
        }

        .example-card .result-block.error {
            background: #fff5f7;
            border-left: 4px solid #e86f8f;
        }

        .example-card .result-block.info {
            background: #fff0f3;
            border-left: 4px solid #e891a5;
        }

        .example-subgrid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }

        .example-subgrid .label {
            color: #b1546b;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .example-subgrid .label i {
            font-size: 0.9rem;
        }

        .full-example {
            margin-bottom: 25px;
        }

        .full-example:last-child {
            margin-bottom: 0;
        }

        .url-link {
            color: #b1546b;
            font-weight: 600;
            text-decoration: none;
        }

        .url-link:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 18px;
            background: #fff0f4;
            font-size: 0.75rem;
            color: #be7f92;
        }

        @media (max-width: 800px) {
            .top-menu { padding: 0 12px; flex-wrap: wrap; }
            .menu-option { padding: 12px 16px; font-size: 0.8rem; }
            .example-subgrid { grid-template-columns: 1fr; }
            .requirement-grid { grid-template-columns: 1fr; }
            .manual-content { padding: 0; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <div class="logo-icon"><i class="fa-brands fa-centos"></i></div>
            <span>Léxico-Sintáctico</span>
        </div>
        <ul class="menu">
            <li><a href="guia.php"> Guía rápida</a></li>
            <li><a href="index.php"> Analizador</a></li>
            <li><a href="sirve.php"> Para Qué Sirve</a></li>
            <li class="active"><a href="manual.php"> Manual de Uso</a></li>
        </ul>
    </nav>

    <div class="info-panel">
        <div class="info-title">
             MANUAL DE USUARIO
        </div>
    </div>

    <div class="app-wrapper">
        <div class="top-menu">
            <button class="menu-option active" data-menu="install"><i class="fas fa-download"></i> Instalación</button>
            <button class="menu-option" data-menu="use"><i class="fas fa-laptop"></i> Uso del Sistema</button>
            <button class="menu-option" data-menu="examples"><i class="fas fa-list-alt"></i> Ejemplos</button>
        </div>

        <!-- MENÚ 1: INSTALACIÓN -->
        <div id="menu-install" class="menu-content active">
            <div class="manual-content">
                <div class="manual-section">
                    <h2><i class="fas fa-download"></i> Instalación</h2>
                    <p>Al ser un sitio web no requiere ninguna instalación de software, descargas de ejecutables ni configuraciones de variables de entorno en el sistema operativo del usuario.</p>
                    
                    <h3><i class="fas fa-list-check"></i> Requisitos Mínimos del Sistema</h3>
                    <div class="requirement-grid">
                        <div class="requirement-item">
                            <i class="fas fa-desktop"></i>
                            <div class="req-text"><strong>Dispositivos:</strong> Computadora de escritorio, laptop o dispositivo móvil con conexión a internet.</div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-globe"></i>
                            <div class="req-text"><strong>Software:</strong> Un navegador web actualizado (Ejemplo: Google Chrome, Mozilla Firefox, Microsoft Edge o Safari).</div>
                        </div>
                        <div class="requirement-item">
                            <i class="fas fa-link"></i>
                            <div class="req-text"><strong>Acceso:</strong> Introducir la URL del proyecto en la barra de direcciones del navegador <a href="https://jenn-val.github.io/AnalizadorLexico/" target="_blank" class="url-link"><i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i> https://jenn-val.github.io/AnalizadorLexico/</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MENÚ 2: USO DEL SISTEMA -->
        <div id="menu-use" class="menu-content">
            <div class="manual-content">
                <div class="manual-section">
                    <h2><i class="fas fa-laptop"></i> Uso del Sistema</h2>
                    <p>La interfaz está diseñada de forma intuitiva para procesar el código en estos pasos:</p>
                    
                    <ol class="step-list">
                        <li>
                            <i class="fas fa-file-code step-icon"></i>
                            <strong>Paso 1: Entrada del Código Fuente.</strong>
                            <span class="step-detail">El usuario tiene dos opciones en el panel izquierdo:</span>
                            <span class="step-detail" style="padding-left:15px;">1. Escribir o pegar manualmente el código directamente en el editor de texto integrado.</span>
                            <span class="step-detail" style="padding-left:15px;">2. Presionar el botón <strong>"Cargar archivo .txt"</strong> para seleccionar un archivo de texto plano desde su almacenamiento local.</span>
                        </li>
                        <li>
                            <i class="fas fa-play step-icon"></i>
                            <strong>Paso 2: Ejecución del Análisis.</strong>
                            <span class="step-detail">Una vez que el código se encuentra en el editor, se debe dar clic en el botón principal <strong>"Analizar Código"</strong>.</span>
                        </li>
                        <li>
                            <i class="fas fa-chart-bar step-icon"></i>
                            <strong>Paso 3: Visualización de Resultados.</strong>
                            <span class="step-detail">De forma inmediata, el sistema actualizará los paneles de la derecha e inferiores mostrando:</span>
                            <span class="step-detail" style="padding-left:15px;">1. La <strong>Tabla de Tokens</strong> detallada.</span>
                            <span class="step-detail" style="padding-left:15px;">2. El <strong>Árbol Sintáctico</strong> gráfico (si el código es correcto).</span>
                            <span class="step-detail" style="padding-left:15px;">3. La <strong>Consola de Resultados</strong> con mensajes de éxito o reportes de errores léxicos/sintácticos.</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- MENÚ 3: EJEMPLOS -->
        <div id="menu-examples" class="menu-content">
            <div class="manual-content">
                <div class="manual-section">
                    <h2><i class="fas fa-list-alt"></i> Ejemplos</h2>
                    
                    <!-- Ejemplo 1: Código Correcto -->
                    <div class="full-example">
                        <div class="example-card" style="border-left:4px solid #4caf50;">
                            <h4><i class="fas fa-check-circle" style="color:#4caf50;"></i> Ejemplo 1: Análisis de Código Correcto</h4>
                            <p style="font-size:0.9rem;color:#5c3f48;">Muestra el comportamiento del sistema cuando el código cumple perfectamente con las reglas del lenguaje.</p>
                            
                            <div class="example-subgrid">
                                <div>
                                    <div class="label"><i class="fas fa-code"></i> Código:</div>
                                    <div class="code-block">inicio
    entero edad = 20;
    real nota = 85.5;
    si (edad >= 18) {
        imprimir("Mayor de edad");
    }
fin</div>
                                </div>
                                <div>
                                    <div class="label"><i class="fas fa-table"></i> Tabla de Tokens:</div>
                                    <div class="result-block info">
                                        PALABRA_RESERVADA: inicio<br>
                                        PALABRA_RESERVADA: entero<br>
                                        IDENTIFICADOR: edad<br>
                                        OPERADOR: =<br>
                                        NUMERO: 20<br>
                                        DELIMITADOR: ;<br>
                                        PALABRA_RESERVADA: real<br>
                                        IDENTIFICADOR: nota<br>
                                        OPERADOR: =<br>
                                        NUMERO_REAL: 85.5<br>
                                        DELIMITADOR: ;<br>
                                        PALABRA_RESERVADA: si<br>
                                        DELIMITADOR: (<br>
                                        IDENTIFICADOR: edad<br>
                                        OPERADOR_COMPARACION: >=<br>
                                        NUMERO: 18<br>
                                        DELIMITADOR: )<br>
                                        DELIMITADOR: {<br>
                                        PALABRA_RESERVADA: imprimir<br>
                                        DELIMITADOR: (<br>
                                        CADENA: "Mayor de edad"<br>
                                        DELIMITADOR: )<br>
                                        DELIMITADOR: ;<br>
                                        DELIMITADOR: }<br>
                                        PALABRA_RESERVADA: fin
                                    </div>
                                </div>
                            </div>
                            <div class="example-subgrid" style="margin-top:10px;">
                                <div>
                                    <div class="label"><i class="fas fa-tree"></i> Árbol Sintáctico:</div>
                                    <div class="result-block info">
                                        Programa<br>
                                        ├── Declaración: entero edad = 20<br>
                                        ├── Declaración: real nota = 85.5<br>
                                        └── Estructura Condicional: si<br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;└── Condición: edad >= 18<br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;└── Bloque: imprimir("Mayor de edad")
                                    </div>
                                </div>
                                <div>
                                    <div class="label"><i class="fas fa-check-circle" style="color:#4caf50;"></i> Errores:</div>
                                    <div class="result-block success">
                                        <i class="fas fa-check" style="color:#4caf50;"></i> Análisis completado exitosamente. No se encontraron errores.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ejemplo 2: Código con Error Léxico -->
                    <div class="full-example">
                        <div class="example-card" style="border-left:4px solid #e86f8f;">
                            <h4><i class="fas fa-times-circle" style="color:#e86f8f;"></i> Ejemplo 2: Código con Error Léxico</h4>
                            <p style="font-size:0.9rem;color:#5c3f48;">Muestra cómo reacciona la interfaz cuando el usuario introduce un carácter inválido.</p>
                            
                            <div class="example-subgrid">
                                <div>
                                    <div class="label"><i class="fas fa-code"></i> Código con error:</div>
                                    <div class="code-block">inicio
    entero @edad = 20;
    real nota = 85.5;
fin</div>
                                </div>
                                <div>
                                    <div class="label"><i class="fas fa-table"></i> Tabla de Tokens:</div>
                                    <div class="result-block error">
                                        PALABRA_RESERVADA: inicio<br>
                                        PALABRA_RESERVADA: entero<br>
                                        <span style="color:#e86f8f;"><i class="fas fa-exclamation-triangle"></i> ERROR LÉXICO: '@' no reconocido</span><br>
                                        IDENTIFICADOR: edad<br>
                                        OPERADOR: =<br>
                                        NUMERO: 20<br>
                                        DELIMITADOR: ;<br>
                                        PALABRA_RESERVADA: real<br>
                                        IDENTIFICADOR: nota<br>
                                        OPERADOR: =<br>
                                        NUMERO_REAL: 85.5<br>
                                        DELIMITADOR: ;<br>
                                        PALABRA_RESERVADA: fin
                                    </div>
                                </div>
                            </div>
                            <div class="example-subgrid" style="margin-top:10px;">
                                <div>
                                    <div class="label"><i class="fas fa-tree"></i> Árbol Sintáctico:</div>
                                    <div class="result-block" style="background:#f5f0f2;color:#999;">
                                        <i class="fas fa-times-circle" style="color:#e86f8f;"></i> No se pudo construir el árbol sintáctico debido a errores léxicos.
                                    </div>
                                </div>
                                <div>
                                    <div class="label"><i class="fas fa-exclamation-triangle" style="color:#e86f8f;"></i> Errores:</div>
                                    <div class="result-block error">
                                        <span style="color:#e86f8f;font-weight:600;"><i class="fas fa-exclamation-circle"></i> Error Léxico:</span><br>
                                        Carácter no reconocido: '@' en la línea 2, columna 8.<br>
                                        <span style="color:#e86f8f;"><i class="fas fa-lightbulb"></i> Sugerencia:</span> Utilice solo letras, números o guión bajo para identificadores.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            Proyecto Lenguajes y Autómatas I | Creado por Jenny Valverde
        </footer>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentUrl = window.location.href;
            const menuItems = document.querySelectorAll('.menu li');
            menuItems.forEach(item => item.classList.remove('active'));
            let matched = false;
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                if (link && currentUrl.includes(link.getAttribute('href'))) {
                    item.classList.add('active');
                    matched = true;
                }
            });
            if (!matched) {
                const defaultActive = Array.from(menuItems).find(item => item.querySelector('a').getAttribute('href') === 'manual.html');
                if (defaultActive) defaultActive.classList.add('active');
            }
        });

        document.querySelectorAll('.menu-option').forEach(opt => {
            opt.addEventListener('click', () => {
                document.querySelectorAll('.menu-option').forEach(o => o.classList.remove('active'));
                opt.classList.add('active');
                const menuId = opt.getAttribute('data-menu');
                document.querySelectorAll('.menu-content').forEach(c => c.classList.remove('active'));
                document.getElementById(`menu-${menuId}`).classList.add('active');
            });
        });
    </script>
</body>
</html>