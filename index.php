<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analizador Léxico-Sintáctico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Contenedor principal */
        .app-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            background: #fff8fa;

            border-radius: 0px;
            box-shadow: 0 25px 50px -12px rgba(160, 80, 100, 0.25);
            overflow: hidden;
            border: 1px solid #f5d0d9;
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

        /* Pestañas de resultados */
        .tabs-container {
            margin-top: 25px;
            background: #fff5f7;
            border-radius: 28px;
            overflow: hidden;
        }

        .tabs {
            display: flex;
            gap: 5px;
            background: #ffeef2;
            padding: 12px 20px 0 20px;
        }

        .tab {
            padding: 12px 28px;
            background: transparent;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 30px 30px 0 0;
            transition: 0.2s;
            color: #ad7a88;
            font-family: inherit;
        }

        .tab.active {
            background: #fff5f7;
            color: #c45c74;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.02);
        }

        .tab-content {
            display: none;
            padding: 22px;
            background: #fff5f7;
            border-top: 2px solid #ffd4de;
            max-height: 450px;
            overflow: auto;
        }

        .tab-content.active {
            display: block;
        }

        .result-area {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            background: #fffbfc;
            padding: 16px;
            border-radius: 20px;
            border: 1px solid #ffd0da;
            white-space: pre-wrap;
            line-height: 1.5;
            color: #4a353e;
        }

        .token-table {
            width: 100%;
            border-collapse: collapse;
            font-family: monospace;
            font-size: 13px;
        }

        .token-table th {
            background: #ffe2e9;
            padding: 12px;
            text-align: left;
            color: #b6536e;
            border-bottom: 2px solid #f5b7c5;
        }

        .token-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #ffe0e6;
            color: #5c3f48;
        }

        .error-line {
            color: #d14b6a;
            background: #ffeef2;
            padding: 10px;
            border-left: 4px solid #e86f8f;
            margin-bottom: 8px;
            border-radius: 12px;
        }

        .ast-tree {
            font-family: monospace;
            font-size: 13px;
            color: #8b5b6b;
        }

        footer {
            text-align: center;
            padding: 18px;
            background: #ffe5eb;
            font-size: 0.8rem;
            color: #be7f92;
        }

        @media (max-width: 700px) {
            body { padding: 15px; }
            .info-title { font-size: 1.3rem; }
            .tab { padding: 8px 16px; }
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
    
    <!-- INFORMACIÓN ANEXADA ARRIBA (antes del editor) -->
    <div class="info-panel">
        <div class="info-title">
            <span> </span> ANALIZADOR LÉXICO-SINTÁCTICO <span> </span>
        </div>
    </div>

<div class="app-wrapper">   

    <!-- EDITOR DE CÓDIGO -->
    <div class="main-panel">
        <div class="editor-header">
            <div class="editor-title">
            <h2> <i class="fa-regular fa-file-code"></i> Escribe o Carga tu Código</h2>
        </div>    
            <div class="button-group">
                <button class="btn btn-outline" id="analyzeBtn"> <i class="fa-brands fa-sistrix"></i> Analizar Código</button>
                <button class="btn btn-outline" id="newBtn"> <i class="fa-regular fa-file-code"></i>  Nuevo código</button>
                <button class="btn btn-outline" id="clearResultsBtn"> <i class="fa-solid fa-broom"></i> Limpiar resultados</button>
                <button class="btn btn-outline" id="loadBtn"> <i class="fa-regular fa-file"></i> Cargar archivo.txt</button>
            </div>
        </div>

        <!-- Editor con números de línea -->
        <div class="editor-modern">
            <div class="code-header-stats">
                <span> <i class="fa-solid fa-code"></i> Código </span>
                <span id="charCounter">0 caracteres</span>
            </div>
            <div class="code-area-wrapper">
                <div class="line-numbers" id="lineNumbers">1</div>
                <div class="code-textarea">
                    <textarea id="codeInput" placeholder="Escribe tu código aquí..."></textarea>
                </div>
            </div>
        </div>


        <!-- PESTAÑAS RESULTADOS -->
        <div class="tabs-container">
            <div class="tabs">
                <button class="tab active" data-tab="tokens"> <i class="fa-solid fa-table"></i> Tabla de Tokens</button>
                <button class="tab" data-tab="ast"> <i class="fa-solid fa-folder-tree"></i> Árbol Sintáctico</button>
                <button class="tab" data-tab="errors"> <i class="fa-solid fa-xmark"></i> Errores</button>
            </div>
            <div id="tokens" class="tab-content active">
                <div id="tokenResult" class="result-area"> <i class="fa-solid fa-clock"></i> Presiona "Analizar Código" para ver los tokens.</div>
            </div>
            <div id="ast" class="tab-content">
                <div id="astResult" class="result-area ast-tree"> <i class="fa-solid fa-tree-city"></i> El árbol sintáctico aparecerá aquí tras el análisis.</div>
            </div>
            <div id="errors" class="tab-content">
                <div id="errorResult" class="result-area"> <i class="fa-solid fa-rectangle-xmark"></i> Los errores léxicos y sintácticos se mostrarán aquí.</div>
            </div>
        </div>

    </div>
    <footer> 
        Proyecto Lenguajes y Autómatas I | Creado por Jenny Valverde
    </footer>

</div>

<script>
    // ---------- SINCRONIZACIÓN DE NÚMEROS DE LÍNEA ----------
    const textarea = document.getElementById('codeInput');
    const lineNumbersDiv = document.getElementById('lineNumbers');
    const charCounter = document.getElementById('charCounter');

    function updateLineNumbers() {
        const lines = textarea.value.split('\n');
        const lineCount = lines.length;
        let numbersHtml = '';
        for (let i = 1; i <= lineCount; i++) {
            numbersHtml += i + '<br>';
        }
        lineNumbersDiv.innerHTML = numbersHtml;
        charCounter.innerText = textarea.value.length + ' caracteres';
    }

    textarea.addEventListener('input', updateLineNumbers);
    textarea.addEventListener('scroll', function() {
        lineNumbersDiv.scrollTop = textarea.scrollTop;
    });
    
    // ---------- PALABRAS RESERVADAS Y CONFIGURACIÓN DEL LENGUAJE ----------
    const RESERVED = {
        "inicio": "PR_INICIO",
        "fin": "PR_FIN",
        "entero": "PR_ENTERO",
        "real": "PR_REAL",
        "cadena": "PR_CADENA",
        "si": "PR_SI",
        "sino": "PR_SINO",
        "mientras": "PR_MIENTRAS",
        "imprimir": "PR_IMPRIMIR"
    };

    const OPERATORS = {
        "+": "OP_SUMA", "-": "OP_RESTA", "*": "OP_MULTIPLICACION", "/": "OP_DIVISION",
        "=": "OP_ASIGNACION", "==": "OP_IGUAL", "!=": "OP_DIFERENTE",
        ">": "OP_MAYOR", "<": "OP_MENOR", ">=": "OP_MAYOR_IGUAL", "<=": "OP_MENOR_IGUAL"
    };

    const DELIMITERS = {
        '(': "PARENTESIS_ABRE", ')': "PARENTESIS_CIERRA",
        '{': "LLAVE_ABRE", '}': "LLAVE_CIERRA",
        ';': "PUNTO_COMA", ',': "COMA"
    };

    // ---------- NOMBRES DESCRIPTIVOS PARA TOKENS ----------
    const TOKEN_NAMES = {
        "OP_SUMA": "Operador de Suma",
        "OP_RESTA": "Operador de Resta",
        "OP_MULTIPLICACION": "Operador de Multiplicación",
        "OP_DIVISION": "Operador de División",
        "OP_ASIGNACION": "Operador de Asignación",
        "OP_IGUAL": "Operador de Igualdad",
        "OP_DIFERENTE": "Operador Diferente",
        "OP_MAYOR": "Operador Mayor Que",
        "OP_MENOR": "Operador Menor Que",
        "OP_MAYOR_IGUAL": "Operador Mayor o Igual",
        "OP_MENOR_IGUAL": "Operador Menor o Igual",
        "PARENTESIS_ABRE": "Paréntesis que Abre",
        "PARENTESIS_CIERRA": "Paréntesis que Cierra",
        "LLAVE_ABRE": "Llave que Abre",
        "LLAVE_CIERRA": "Llave que Cierra",
        "PUNTO_COMA": "Punto y Coma",
        "COMA": "Coma",
        "PR_INICIO": "Palabra Reservada Inicio",
        "PR_FIN": "Palabra Reservada Fin",
        "PR_ENTERO": "Palabra Reservada Entero",
        "PR_REAL": "Palabra Reservada Real",
        "PR_CADENA": "Palabra Reservada Cadena",
        "PR_SI": "Palabra Reservada Si",
        "PR_SINO": "Palabra Reservada Sino",
        "PR_MIENTRAS": "Palabra Reservada Mientras",
        "PR_IMPRIMIR": "Palabra Reservada Imprimir",
        "NUMERO_ENTERO": "Número Entero",
        "NUMERO_REAL": "Número Real",
        "CADENA": "Cadena de Texto",
        "ID": "Identificador"
    };

    // ---------- ANALIZADOR LÉXICO ----------
    function analisisLexico(codigo) {
        const lines = codigo.split(/\r?\n/);
        const tokens = [];
        const erroresLexicos = [];

        for (let i = 0; i < lines.length; i++) {
            const line = lines[i];
            let pos = 0;
            const len = line.length;
            
            while (pos < len) {
                const ch = line[pos];
                // Saltar whitespace
                if (/\s/.test(ch)) { pos++; continue; }
                
                // Comentario // ignorar resto línea
                if (ch === '/' && pos+1 < len && line[pos+1] === '/') break;
                
                // Operadores multi-char (>=, <=, ==, !=)
                let matchedOp = false;
                for (let op of Object.keys(OPERATORS).sort((a,b)=>b.length-a.length)) {
                    if (line.substr(pos, op.length) === op) {
                        tokens.push({ lexema: op, tipo: OPERATORS[op], nombre: TOKEN_NAMES[OPERATORS[op]], linea: i+1 });
                        pos += op.length;
                        matchedOp = true;
                        break;
                    }
                }
                if (matchedOp) continue;
                
                // Delimitadores
                if (DELIMITERS[ch]) {
                    tokens.push({ lexema: ch, tipo: DELIMITERS[ch], nombre: TOKEN_NAMES[DELIMITERS[ch]], linea: i+1 });
                    pos++;
                    continue;
                }
                
                // Cadenas ""
                if (ch === '"') {
                    let start = pos;
                    pos++;
                    while (pos < len && line[pos] !== '"') pos++;
                    if (pos < len && line[pos] === '"') {
                        const stringLit = line.substring(start, pos+1);
                        tokens.push({ lexema: stringLit, tipo: "CADENA", nombre: TOKEN_NAMES["CADENA"], linea: i+1 });
                        pos++;
                    } else {
                        erroresLexicos.push({ linea: i+1, mensaje: "Cadena no cerrada", caracter: line.substring(start) });
                        pos = len; // Salir del bucle
                    }
                    continue;
                }
                
                // Números (enteros y reales)
                if (/[0-9]/.test(ch)) {
                    let start = pos;
                    let hasDecimal = false;
                    while (pos < len && (/[0-9]/.test(line[pos]) || line[pos] === '.')) {
                        if (line[pos] === '.') {
                            if (hasDecimal) break;
                            hasDecimal = true;
                        }
                        pos++;
                    }
                    const numero = line.substring(start, pos);
                    const tipoNum = hasDecimal ? "NUMERO_REAL" : "NUMERO_ENTERO";
                    tokens.push({ lexema: numero, tipo: tipoNum, nombre: TOKEN_NAMES[tipoNum], linea: i+1 });
                    continue;
                }
                
                // Identificadores o palabras reservadas
                if (/[a-zA-Z_]/.test(ch)) {
                    let start = pos;
                    while (pos < len && /[a-zA-Z0-9_]/.test(line[pos])) pos++;
                    const word = line.substring(start, pos);
                    if (RESERVED[word]) {
                        tokens.push({ lexema: word, tipo: RESERVED[word], nombre: TOKEN_NAMES[RESERVED[word]], linea: i+1 });
                    } else {
                        // Validar identificador
                        if (/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(word)) {
                            tokens.push({ lexema: word, tipo: "ID", nombre: TOKEN_NAMES["ID"], linea: i+1 });
                        } else {
                            erroresLexicos.push({ linea: i+1, mensaje: "Identificador inválido", caracter: word });
                        }
                    }
                    continue;
                }
                
                // Caracter no reconocido
                erroresLexicos.push({ linea: i+1, mensaje: "Símbolo no reconocido", caracter: ch });
                pos++;
            }
        }
        return { tokens, erroresLexicos };
    }

    // ---------- ANALIZADOR SINTÁCTICO CORREGIDO ----------
    class ParserSintactico {
        constructor(tokens) {
            this.tokens = tokens;
            this.pos = 0;
            this.errores = [];
            this.astRoot = { type: "Programa", children: [] };
        }
        
        current() { 
            return this.pos < this.tokens.length ? this.tokens[this.pos] : null; 
        }
        
        advance() { 
            this.pos++; 
        }
        
        match(...tipos) {
            const tok = this.current();
            if (tok && tipos.includes(tok.tipo)) {
                this.advance();
                return true;
            }
            return false;
        }
        
        expect(tipo) {
            const tok = this.current();
            if (!tok) {
                this.errores.push({ linea: -1, esperado: tipo, encontrado: "EOF" });
                return false;
            } else if (tok.tipo !== tipo) {
                this.errores.push({ linea: tok.linea, esperado: tipo, encontrado: tok.lexema });
                return false;
            }
            this.advance();
            return true;
        }
        
        parse() {
            // Verificar inicio
            if (!this.match("PR_INICIO")) {
                const tok = this.current();
                this.errores.push({ 
                    linea: tok ? tok.linea : 1, 
                    esperado: "inicio", 
                    encontrado: tok ? tok.lexema : "null" 
                });
            }
            
            const programaNode = { type: "Programa", children: [] };
            
            // Parsear todas las instrucciones hasta encontrar "fin"
            while (this.current() && this.current().tipo !== "PR_FIN") {
                const tok = this.current();
                
                if (tok.tipo === "PR_ENTERO" || tok.tipo === "PR_REAL" || tok.tipo === "PR_CADENA") {
                    const decl = this.parseDeclaracion();
                    if (decl) programaNode.children.push(decl);
                } else if (tok.tipo === "ID") {
                    const asign = this.parseAsignacion();
                    if (asign) programaNode.children.push(asign);
                } else if (tok.tipo === "PR_SI") {
                    const cond = this.parseCondicional();
                    if (cond) programaNode.children.push(cond);
                } else if (tok.tipo === "PR_MIENTRAS") {
                    const ciclo = this.parseCiclo();
                    if (ciclo) programaNode.children.push(ciclo);
                } else if (tok.tipo === "PR_IMPRIMIR") {
                    const print = this.parseImprimir();
                    if (print) programaNode.children.push(print);
                } else {
                    this.errores.push({ 
                        linea: tok.linea, 
                        esperado: "declaración, asignación, si, mientras o imprimir", 
                        encontrado: tok.lexema 
                    });
                    this.advance(); // Avanzar para evitar bucle infinito
                }
            }
            
            // Verificar fin
            if (!this.match("PR_FIN")) {
                const tok = this.current();
                this.errores.push({ 
                    linea: tok ? tok.linea : this.tokens.length > 0 ? this.tokens[this.tokens.length-1].linea : 1, 
                    esperado: "fin", 
                    encontrado: tok ? tok.lexema : "EOF" 
                });
            }
            
            this.astRoot = programaNode;
        }
        
        parseDeclaracion() {
            const tipoTok = this.current();
            if (!tipoTok) return null;
            this.advance(); // Consumir tipo
            
            const idTok = this.current();
            if (!this.match("ID")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : tipoTok.linea, 
                    esperado: "identificador", 
                    encontrado: this.current() ? this.current().lexema : "?" 
                });
                return null;
            }
            
            const node = { 
                type: "Declaracion", 
                tipo: tipoTok.lexema, 
                variable: idTok.lexema, 
                inicializacion: null 
            };
            
            // Inicialización opcional
            if (this.current() && this.current().tipo === "OP_ASIGNACION") {
                this.advance(); // Consumir =
                const exprNode = this.parseExpresion();
                node.inicializacion = exprNode;
            }
            
            if (!this.match("PUNTO_COMA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : tipoTok.linea, 
                    esperado: ";", 
                    encontrado: this.current() ? this.current().lexema : "??" 
                });
            }
            
            return node;
        }
        
        parseAsignacion() {
            const idToken = this.current();
            if (!this.match("ID")) return null;
            
            if (!this.match("OP_ASIGNACION")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : idToken.linea, 
                    esperado: "=", 
                    encontrado: this.current() ? this.current().lexema : "?" 
                });
                return null;
            }
            
            const expr = this.parseExpresion();
            
            if (!this.match("PUNTO_COMA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : idToken.linea, 
                    esperado: ";", 
                    encontrado: this.current() ? this.current().lexema : "fin" 
                });
            }
            
            return { type: "Asignacion", variable: idToken.lexema, expresion: expr };
        }
        
        parseCondicional() {
            this.advance(); // Consumir "si"
            
            if (!this.match("PARENTESIS_ABRE")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "(" 
                });
                return null;
            }
            
            const condicion = this.parseExpresion();
            
            if (!this.match("PARENTESIS_CIERRA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: ")" 
                });
            }
            
            if (!this.match("LLAVE_ABRE")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "{" 
                });
                return null;
            }
            
            const cuerpo = this.parseBloque();
            
            if (!this.match("LLAVE_CIERRA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "}" 
                });
            }
            
            let sinoBloque = null;
            if (this.current() && this.current().tipo === "PR_SINO") {
                this.advance(); // Consumir "sino"
                if (this.match("LLAVE_ABRE")) {
                    sinoBloque = this.parseBloque();
                    this.match("LLAVE_CIERRA");
                }
            }
            
            return { type: "Condicional", condicion, cuerpo, sino: sinoBloque };
        }
        
        parseCiclo() {
            this.advance(); // Consumir "mientras"
            
            if (!this.match("PARENTESIS_ABRE")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "(" 
                });
                return null;
            }
            
            const condicion = this.parseExpresion();
            
            if (!this.match("PARENTESIS_CIERRA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: ")" 
                });
            }
            
            if (!this.match("LLAVE_ABRE")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "{" 
                });
                return null;
            }
            
            const cuerpo = this.parseBloque();
            
            if (!this.match("LLAVE_CIERRA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "}" 
                });
            }
            
            return { type: "Ciclo", condicion, cuerpo };
        }
        
        parseImprimir() {
            this.advance(); // Consumir "imprimir"
            
            if (!this.match("PARENTESIS_ABRE")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: "(" 
                });
                return null;
            }
            
            let valor = null;
            const tok = this.current();
            if (tok && (tok.tipo === "CADENA" || tok.tipo === "ID" || tok.tipo === "NUMERO_ENTERO" || tok.tipo === "NUMERO_REAL")) {
                valor = { tipo: tok.tipo, lexema: tok.lexema };
                this.advance();
            }
            
            if (!this.match("PARENTESIS_CIERRA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: ")" 
                });
            }
            
            if (!this.match("PUNTO_COMA")) {
                this.errores.push({ 
                    linea: this.current() ? this.current().linea : 0, 
                    esperado: ";" 
                });
            }
            
            return { type: "Imprimir", valor };
        }
        
        parseBloque() {
            const instrucciones = [];
            while (this.current() && 
                   this.current().tipo !== "LLAVE_CIERRA" && 
                   this.current().tipo !== "PR_FIN") {
                
                const tok = this.current();
                
                if (tok.tipo === "ID") {
                    const asign = this.parseAsignacion();
                    if (asign) instrucciones.push(asign);
                } else if (tok.tipo === "PR_IMPRIMIR") {
                    const print = this.parseImprimir();
                    if (print) instrucciones.push(print);
                } else {
                    // Si encontramos algo que no esperamos en el bloque, salimos
                    break;
                }
            }
            return instrucciones;
        }
        
        parseExpresion() {
            const left = this.parseSimpleExpresion();
            if (left && this.current() && 
                ["OP_MAYOR","OP_MENOR","OP_MAYOR_IGUAL","OP_MENOR_IGUAL","OP_IGUAL","OP_DIFERENTE"].includes(this.current().tipo)) {
                const op = this.current();
                this.advance();
                const right = this.parseSimpleExpresion();
                return { type: "BinOp", operador: op.lexema, izquierda: left, derecha: right };
            }
            return left;
        }
        
        parseSimpleExpresion() {
            let left = this.parseTermino();
            while (left && this.current() && (this.current().tipo === "OP_SUMA" || this.current().tipo === "OP_RESTA")) {
                const op = this.current();
                this.advance();
                const right = this.parseTermino();
                left = { type: "BinOp", operador: op.lexema, izquierda: left, derecha: right };
            }
            return left;
        }
        
        parseTermino() {
            let left = this.parseFactor();
            while (left && this.current() && (this.current().tipo === "OP_MULTIPLICACION" || this.current().tipo === "OP_DIVISION")) {
                const op = this.current();
                this.advance();
                const right = this.parseFactor();
                left = { type: "BinOp", operador: op.lexema, izquierda: left, derecha: right };
            }
            return left;
        }
        
        parseFactor() {
            const tok = this.current();
            if (!tok) return null;
            
            if (tok.tipo === "NUMERO_ENTERO" || tok.tipo === "NUMERO_REAL" || tok.tipo === "ID") {
                this.advance();
                return { type: "Literal", valor: tok.lexema, tipo: tok.tipo };
            } else if (tok.tipo === "PARENTESIS_ABRE") {
                this.advance();
                const expr = this.parseExpresion();
                this.match("PARENTESIS_CIERRA");
                return expr;
            } else {
                this.errores.push({ 
                    linea: tok.linea, 
                    esperado: "numero, id o (", 
                    encontrado: tok.lexema 
                });
                return null;
            }
        }
        
        getErrors() { return this.errores; }
        getAST() { return this.astRoot; }
    }
    
    // función para mostrar el árbol de forma bonita
    function astToString(node, indent = "") {
        if (!node) return "";
        let str = indent + "├── " + node.type;
        if (node.variable) str += " → " + node.variable;
        if (node.tipo && node.type !== "BinOp") str += " [" + node.tipo + "]";
        if (node.valor) str += " : " + node.valor;
        str += "\n";
        
        const childIndent = indent + "│   ";
        
        if (node.children) {
            for (let child of node.children) {
                str += astToString(child, childIndent);
            }
        }
        if (node.inicializacion) {
            str += childIndent + "└── Inicialización\n";
            str += astToString(node.inicializacion, childIndent + "    ");
        }
        if (node.expresion) {
            str += childIndent + "└── Expresión\n";
            str += astToString(node.expresion, childIndent + "    ");
        }
        if (node.condicion) {
            str += childIndent + "├── Condición\n";
            str += astToString(node.condicion, childIndent + "│   ");
        }
        if (node.cuerpo) {
            str += childIndent + "├── Cuerpo\n";
            if (Array.isArray(node.cuerpo)) {
                for (let instr of node.cuerpo) {
                    str += astToString(instr, childIndent + "│   ");
                }
            } else {
                str += astToString(node.cuerpo, childIndent + "│   ");
            }
        }
        if (node.sino) {
            str += childIndent + "└── Sino\n";
            str += astToString(node.sino, childIndent + "    ");
        }
        if (node.type === "BinOp") {
            if (node.izquierda) str += astToString(node.izquierda, childIndent + "├── ");
            if (node.derecha) str += astToString(node.derecha, childIndent + "└── ");
        }
        
        return str;
    }
    
    // ---------- CONTROL UI ----------
    const codeArea = document.getElementById('codeInput');
    const tokenDiv = document.getElementById('tokenResult');
    const astDiv = document.getElementById('astResult');
    const errorDiv = document.getElementById('errorResult');
    
    // setear código de ejemplo por defecto
    codeArea.value = `inicio
entero edad = 20;
real promedio = 95.5;

si (edad >= 18) {
    imprimir("Mayor de edad");
}

mientras (edad < 100) {
    edad = edad + 1;
}
fin`;
    
    updateLineNumbers();
    
    function analizarTodo() {
        const codigo = codeArea.value;
        
        // 1) léxico
        const { tokens, erroresLexicos } = analisisLexico(codigo);
        
        // SI HAY ERRORES LÉXICOS: No mostrar tabla de tokens
        if (erroresLexicos.length > 0) {
            let errHtml = '<div style="color:#c74b6a;"><strong> <i class="fa-solid fa-rectangle-xmark"></i> ERRORES LÉXICOS ENCONTRADOS</strong></div>';
            errHtml += '<div style="margin:10px 0; padding:10px; background:#fff5f5; border-left:4px solid #c74b6a; border-radius:8px;">';
            errHtml += '<p style="margin:0; color:#666;">Corrige los siguientes errores para generar la tabla de tokens:</p></div>';
            for (let e of erroresLexicos) {
                errHtml += `<div class="error-line"> <i class="fa-solid fa-rectangle-xmark"></i> Línea ${e.linea}: ${e.mensaje} — Carácter '${escapeHtml(e.caracter)}'</div>`;
            }
            errorDiv.innerHTML = errHtml;
            
            // Limpiar tabla de tokens y árbol
            tokenDiv.innerHTML = '<div style="background:#fff5f5; padding:20px; border-radius:18px; border:2px dashed #c74b6a; text-align:center;"> <i class="fa-solid fa-rectangle-xmark"></i> <strong>Tabla de tokens no disponible</strong><br><span style="color:#666;">Corrige los errores léxicos para visualizar los tokens</span></div>';
            astDiv.innerHTML = '<div style="background:#fff5f5; padding:20px; border-radius:18px; border:2px dashed #c74b6a; text-align:center;"> <i class="fa-solid fa-rectangle-xmark"></i> <strong>Árbol sintáctico no disponible</strong><br><span style="color:#666;">Corrige los errores léxicos y sintácticos para visualizar el árbol</span></div>';
            return;
        }
        
        // 2) Si NO hay errores léxicos pero no hay tokens (código vacío)
        if (tokens.length === 0) {
            tokenDiv.innerHTML = '<div style="background:#fffbf0; padding:20px; border-radius:18px; border:2px dashed #ffa500; text-align:center;"> <i class="fa-solid fa-rectangle-xmark"></i> <strong>No se encontraron tokens</strong><br><span style="color:#666;">El código está vacío o solo contiene comentarios</span></div>';
            errorDiv.innerHTML = "";
            astDiv.innerHTML = "";
            return;
        }
        
        // 3) SI NO HAY ERRORES LÉXICOS: Mostrar tabla de tokens
        let html = '<div style="background:#e2f7e4; padding:10px; margin-bottom:15px; border-radius:12px; text-align:center;"> <i class="fa-solid fa-circle-check"></i> <strong>Análisis léxico exitoso</strong> - No se encontraron errores léxicos</div>';
        html += '<table class="token-table"><tr><th>Lexema</th><th>Tipo Token</th><th>Nombre Completo</th><th>Línea</th></tr>';
        for (let t of tokens) {
            html += `<tr><td>${escapeHtml(t.lexema)}</td><td>${t.tipo}</td><td>${t.nombre}</td><td>${t.linea}</td></tr>`;
        }
        html += '</table>';
        tokenDiv.innerHTML = html;
        
        // 4) análisis sintáctico
        const parser = new ParserSintactico(tokens);
        parser.parse();
        const erroresSintacticos = parser.getErrors();
        
        if (erroresSintacticos.length === 0) {
            errorDiv.innerHTML = '<div style="background:#e2f7e4; padding:20px; border-radius:18px; text-align:center;"> <i class="fa-solid fa-circle-check"></i> <strong>ANÁLISIS COMPLETADO CON ÉXITO</strong><br>No se encontraron errores sintácticos, tu código es válido.</div>';
            const astRoot = parser.getAST();
            let astString =  " ÁRBOL SINTÁCTICO:\n\n" + astToString(astRoot);
            astDiv.innerHTML = `<pre style="font-family:monospace; font-size:12px; background:#f9f9f9; padding:15px; border-radius:12px;">${escapeHtml(astString)}</pre>`;
        } else {
            let errHtml = '<div style="background:#fff5f5; padding:15px; border-radius:18px; border-left:4px solid #b1425e;">';
            errHtml += '<strong style="color:#b1425e;"> <i class="fa-solid fa-rectangle-xmark"></i> ERRORES SINTÁCTICOS ENCONTRADOS</strong></div>';
            for (let e of erroresSintacticos) {
                errHtml += `<div class="error-line"> <i class="fa-solid fa-rectangle-xmark"></i> Línea ${e.linea !== -1 ? e.linea : '?'}: Se esperaba '${e.esperado}', se encontró '${escapeHtml(e.encontrado)}'</div>`;
            }
            errorDiv.innerHTML = errHtml;
            astDiv.innerHTML = '<div style="background:#fff5f5; padding:20px; border-radius:18px; border:2px dashed #b1425e; text-align:center;"> <i class="fa-solid fa-folder-tree"></i> <strong>Árbol sintáctico no disponible</strong><br><span style="color:#666;">Corrige los errores sintácticos para visualizar el árbol</span></div>';
        }
    }
    
    function limpiarResultados() {
        tokenDiv.innerHTML = " Resultados limpiados. Presiona 'Analizar' nuevamente.";
        astDiv.innerHTML = " El árbol sintáctico aparecerá tras el análisis.";
        errorDiv.innerHTML = " Los errores léxicos y sintácticos se mostrarán aquí.";
    }
    
    function cargarArchivo() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.txt';
        input.onchange = e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(evt) {
                codeArea.value = evt.target.result;
                updateLineNumbers();
            };
            reader.readAsText(file);
        };
        input.click();
    }
    
    function escapeHtml(str) { 
        return String(str).replace(/[&<>]/g, function(m){
            if(m==='&') return '&amp;'; 
            if(m==='<') return '&lt;'; 
            if(m==='>') return '&gt;'; 
            return m;
        });
    }
    
    // Eventos
    document.getElementById('loadBtn').addEventListener('click', cargarArchivo);
    document.getElementById('newBtn').addEventListener('click', () => { 
        codeArea.value = ''; 
        updateLineNumbers();
        limpiarResultados();
    });
    document.getElementById('analyzeBtn').addEventListener('click', analizarTodo);
    document.getElementById('clearResultsBtn').addEventListener('click', limpiarResultados);
    
    // Pestañas
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const tabId = tab.getAttribute('data-tab');
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        });
    });
</script>
</body>
</html>