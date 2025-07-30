<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 37 - Sistema de E-Learning</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Estilos principales siguiendo el patrón de index.php */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        
        .header-section h3 {
            margin: 0;
            font-weight: 300;
        }
        
        .header-section small {
            opacity: 0.9;
        }
        
        /* Navegación de cursos */
        .courses-nav {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }
        
        /* Grid de cursos */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .course-item {
            cursor: pointer;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #dee2e6;
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .course-item:hover {
            background: #f8f9fa;
            border-color: #007bff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,123,255,0.15);
        }
        
        .course-item.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .course-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .course-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #007bff;
            line-height: 1.3;
        }
        
        .course-item.active .course-title {
            color: white;
        }
        
        .course-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
            flex-grow: 1;
            line-height: 1.4;
        }
        
        .course-item.active .course-description {
            color: rgba(255,255,255,0.9);
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .course-item.active .course-meta {
            color: rgba(255,255,255,0.8);
        }
        
        .course-date {
            font-size: 0.75rem;
            color: #8c9197;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .course-item.active .course-date {
            color: rgba(255,255,255,0.7);
        }
        
        .course-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }
        
        .course-item:hover .course-arrow {
            transform: translateX(5px);
        }
        
        /* Stepper para clases */
        .stepper-container {
            padding: 20px;
            background: white;
        }
        
        .stepper {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 30px;
            overflow-x: auto;
            padding: 10px 0;
            gap: 20px;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 120px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        
        .step.active .step-circle {
            background: #007bff;
            border-color: #007bff;
            color: white;
        }
        
        .step.completed .step-circle {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }
        
        .step-title {
            font-size: 12px;
            text-align: center;
            max-width: 100px;
            word-wrap: break-word;
        }
        
        .step-connector {
            flex: 1;
            height: 2px;
            background: #dee2e6;
            margin: 0 10px;
            position: relative;
            top: -15px;
        }
        
        .step.completed + .step-connector {
            background: #28a745;
        }
        
        /* Contenido de clase */
        .class-content {
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .class-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .class-title {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .class-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Área de contenido multimedia */
        .content-area {
            padding: 20px;
        }
        
        .content-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .content-header {
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .content-type-video {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .content-type-audio {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .content-type-pdf {
            background: #ffebee;
            color: #c62828;
        }
        
        .content-type-image {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .content-body {
            padding: 20px;
        }
        
        /* Media players */
        .video-player {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .audio-player {
            width: 100%;
            margin: 10px 0;
        }
        
        .pdf-viewer {
            width: 100%;
            height: 600px;
            border: none;
        }
        
        .image-viewer {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        
        /* Descripción y descarga */
        .content-description {
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        
        .download-section {
            text-align: center;
            margin-top: 15px;
        }
        
        /* Sección de comentarios */
        .comments-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .comments-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .comment-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .comment-item {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .comment-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .comment-author {
            font-weight: bold;
            color: #007bff;
        }
        
        .comment-date {
            color: #6c757d;
            font-size: 12px;
        }
        
        .comment-content {
            margin-bottom: 10px;
        }
        
        .comment-actions {
            display: flex;
            gap: 10px;
        }
        
        .reply-section {
            margin-left: 30px;
            margin-top: 15px;
            padding-left: 15px;
            border-left: 2px solid #e9ecef;
        }
        
        /* Nuevos estilos para comentarios recursivos */
        .replies-section {
            margin-top: 10px;
        }
        
        .comment-item[style*="margin-left"] {
            position: relative;
            border-left: 2px solid #e9ecef;
            padding-left: 15px;
            margin-top: 10px;
        }
        
        .comment-item[style*="margin-left"]:before {
            content: '';
            position: absolute;
            left: -2px;
            top: 0;
            height: 2px;
            width: 15px;
            background: #e9ecef;
        }
        
        /* Diferentes colores de borde para diferentes niveles */
        .comment-item[style*="margin-left: 30px"] {
            border-left-color: #007bff;
        }
        
        .comment-item[style*="margin-left: 60px"] {
            border-left-color: #28a745;
        }
        
        .comment-item[style*="margin-left: 90px"] {
            border-left-color: #ffc107;
        }
        
        .comment-item[style*="margin-left: 120px"] {
            border-left-color: #dc3545;
        }
        
        .comment-item[style*="margin-left: 150px"] {
            border-left-color: #6f42c1;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .courses-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .courses-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .stepper {
                flex-direction: column;
                align-items: stretch;
            }
            
            .step {
                margin-bottom: 10px;
            }
            
            .step-connector {
                display: none;
            }
            
            .class-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .courses-nav {
                padding: 10px 15px;
            }
            
            .course-item {
                padding: 15px;
            }
            
            .course-title {
                font-size: 1rem;
            }
        }
        
        /* Indicadores de estado */
        .loading-indicator {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* PDF Controls */
        .pdf-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header Principal -->
        <div class="main-container">
            <div class="header-section">
                <h3><i class="fas fa-graduation-cap mr-2"></i>Sistema de E-Learning</h3>
            </div>
            
            <!-- Navegación de cursos -->
            <div class="courses-nav">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Cursos Disponibles</h6>
                    <button class="btn btn-primary btn-sm" onclick="cargarCursos()">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                </div>
                <div id="courses-list">
                    <!-- Los cursos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
        
        <!-- Contenido principal del curso -->
        <div class="main-container" id="course-content" style="display: none;">
            <!-- Stepper de navegación de clases -->
            <div class="stepper-container mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 id="course-title">Clases del Curso</h5>
                    <button class="btn btn-outline-secondary btn-sm" onclick="volverACursos()">
                        <i class="fas fa-arrow-left"></i> Volver a Cursos
                    </button>
                </div>
                <div class="stepper" id="classes-stepper">
                    <!-- Los steppers se cargarán aquí -->
                </div>
            </div>
            
            <!-- Contenido de la clase actual -->
            <div id="class-content-area">
                <!-- El contenido se cargará aquí -->
            </div>
        </div>
    </div>

<!-- Bootstrap 4 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Variables globales
    let cursoActual = null;
    let claseActual = null;
    let clasesDelCurso = [];
    let ejecutivoActual = 1; // Usuario logueado por defecto

    // Cargar cursos al iniciar
    $(document).ready(function() {
        cargarCursos();
    });

    // =====================================
    // FUNCIONES DE COMUNICACIÓN CON EL SERVIDOR
    // =====================================
    
    function llamarServidor(action, data = {}, callback = null, errorCallback = null) {
        data.action = action;
        
        $.ajax({
            url: 'server/controlador_elearning.php',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                if (response.success) {
                    if (callback) callback(response.data, response.message);
                } else {
                    console.error('Error del servidor:', response.message);
                    if (errorCallback) {
                        errorCallback(response.message);
                    } else {
                        mostrarNotificacion('Error: ' + response.message, 'error');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la comunicación:', error);
                const mensaje = 'Error de comunicación con el servidor';
                if (errorCallback) {
                    errorCallback(mensaje);
                } else {
                    mostrarNotificacion(mensaje, 'error');
                }
            }
        });
    }

    // =====================================
    // FUNCIONES PRINCIPALES
    // =====================================

    function cargarCursos() {
        mostrarCargando('#courses-list');
        
        llamarServidor('obtener_cursos', {}, function(cursos) {
            mostrarCursos(cursos);
        }, function(error) {
            $('#courses-list').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar cursos: ${error}
                </div>
            `);
        });
    }

    function mostrarCursos(cursos) {
        const container = $('#courses-list');
        
        if (cursos.length === 0) {
            container.html(`
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h5>No hay cursos disponibles</h5>
                    <p>Aún no se han creado cursos en la plataforma.</p>
                </div>
            `);
            return;
        }
        
        let html = '<div class="courses-grid">';
        cursos.forEach(curso => {
            html += `
                <div class="course-item" onclick="seleccionarCurso(${curso.id_curso}, '${curso.nom_curso}')">
                    <div class="course-content">
                        <h6 class="course-title">${curso.nom_curso}</h6>
                        <p class="course-description">${curso.des_curso}</p>
                        <div class="course-meta">
                            <span><i class="fas fa-book"></i> ${curso.total_clases} clases</span>
                            <span><i class="fas fa-user"></i> ${curso.creador_curso}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="course-date">
                                <i class="fas fa-calendar"></i> ${formatearFecha(curso.fec_creacion_curso)}
                            </small>
                            <i class="fas fa-chevron-right course-arrow"></i>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        container.html(html);
    }

    function seleccionarCurso(idCurso, nombreCurso) {
        cursoActual = { id_curso: idCurso, nom_curso: nombreCurso };
        cargarClasesCurso(idCurso);
    }

    function cargarClasesCurso(idCurso) {
        mostrarCargando('#classes-stepper');
        
        llamarServidor('obtener_clases_curso', { id_curso: idCurso }, function(clases) {
            clasesDelCurso = clases || [];
            mostrarStepper(clases);
            
            // Mostrar el contenido del curso y ocultar la lista de cursos
            $('#course-content').show();
            $('#course-title').text(`Clases de: ${cursoActual.nom_curso}`);
            
            // Seleccionar la primera clase automáticamente
            if (clases && clases.length > 0) {
                seleccionarClase(clases[0].id_clase, 0);
            }
        }, function(error) {
            mostrarNotificacion('Error al cargar clases: ' + error, 'error');
        });
    }

    function mostrarStepper(clases) {
        const container = $('#classes-stepper');
        
        if (!clases || clases.length === 0) {
            container.html(`
                <div class="empty-state">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <p>Este curso aún no tiene clases disponibles.</p>
                </div>
            `);
            return;
        }

        let html = '';
        clases.forEach((clase, index) => {
            html += `
                <div class="step ${index === 0 ? 'active' : ''}" onclick="seleccionarClase(${clase.id_clase}, ${index})">
                    <div class="step-circle">${index + 1}</div>
                    <div class="step-title">${clase.tit_clase}</div>
                </div>
            `;
            
            // Agregar conector excepto en el último elemento
            if (index < clases.length - 1) {
                html += '<div class="step-connector"></div>';
            }
        });
        
        container.html(html);
    }

    function seleccionarClase(idClase, indice) {
        claseActual = idClase;
        
        // Actualizar stepper activo
        $('#classes-stepper .step').removeClass('active');
        $('#classes-stepper .step').eq(indice).addClass('active');
        
        // Cargar contenido de la clase
        cargarContenidoClase(idClase);
    }

    function cargarContenidoClase(idClase) {
        mostrarCargando('#class-content-area');
        
        llamarServidor('obtener_contenido_clase', { id_clase: idClase }, function(data) {
            mostrarContenidoClase(data);
        }, function(error) {
            $('#class-content-area').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar contenido: ${error}
                </div>
            `);
        });
    }

    function mostrarContenidoClase(data) {
        const clase = data.clase;
        const contenidos = data.contenidos || [];
        
        if (contenidos.length === 0) {
            $('#class-content-area').html(`
                <div class="main-container">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5>No hay contenido disponible</h5>
                        <p>Esta clase aún no tiene contenido agregado.</p>
                    </div>
                </div>
            `);
            return;
        }

        let html = '';
        
        contenidos.forEach(contenido => {
            html += generarContenidoItem(contenido);
        });
        
        $('#class-content-area').html(html);
        
        // Cargar comentarios para cada contenido
        contenidos.forEach(contenido => {
            cargarComentarios(contenido.id_contenido);
        });
    }

    function generarContenidoItem(contenido) {
        const tipoClase = getTipoClase(contenido.tip_contenido);
        const rutaArchivo = `uploads/${contenido.arc_contenido}`;
        
        return `
            <div class="main-container content-item" data-contenido-id="${contenido.id_contenido}">
                <!-- Header del contenido -->
                <div class="class-header">
                    <div class="class-title">${contenido.tit_contenido}</div>
                    <div class="class-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Creado por: ${contenido.creador_contenido}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>${formatearFecha(contenido.fec_creacion_contenido)}</span>
                        </div>
                        <div class="meta-item">
                            <span class="content-type-badge ${tipoClase}">${getTipoTexto(contenido.tip_contenido)}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Contenido multimedia -->
                <div class="content-area">
                    ${generarVisualizadorContenido(contenido)}
                    
                    <!-- Descripción -->
                    ${contenido.des_contenido ? `
                        <div class="content-description">
                            <strong>Descripción:</strong> ${contenido.des_contenido}
                        </div>
                    ` : ''}
                    
                    <!-- Opción de descarga -->
                    ${contenido.arc_contenido ? `
                        <div class="download-section">
                            <a href="${rutaArchivo}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i> Descargar archivo
                            </a>
                        </div>
                    ` : ''}
                </div>
                
                <!-- Sección de comentarios -->
                <div class="comments-section">
                    <div class="comments-header">
                        <h6><i class="fas fa-comments mr-2"></i>Comentarios</h6>
                    </div>
                    
                    <!-- Formulario para nuevo comentario -->
                    <div class="comment-form">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Escribe un comentario..." 
                                   id="input-comentario-${contenido.id_contenido}"
                                   onkeypress="if(event.key === 'Enter') agregarComentario(${contenido.id_contenido})">
                            <div class="input-group-append">
                                <button class="btn btn-primary" onclick="agregarComentario(${contenido.id_contenido})">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lista de comentarios -->
                    <div id="comments-${contenido.id_contenido}">
                        <div class="loading-indicator">
                            <i class="fas fa-spinner fa-spin"></i> Cargando comentarios...
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // =====================================
    // FUNCIONES DE COMENTARIOS
    // =====================================

    function cargarComentarios(idContenido) {
        llamarServidor('obtener_comentarios_contenido', { id_contenido: idContenido }, function(comentarios) {
            mostrarComentarios(idContenido, comentarios);
        }, function(error) {
            $(`#comments-${idContenido}`).html(`
                <div class="alert alert-warning">
                    <small>Error al cargar comentarios: ${error}</small>
                </div>
            `);
        });
    }

    function mostrarComentarios(idContenido, comentarios) {
        const container = $(`#comments-${idContenido}`);
        
        if (!comentarios || comentarios.length === 0) {
            container.html(`
                <div class="text-muted text-center py-3">
                    <small><i class="fas fa-comments"></i> No hay comentarios aún. ¡Sé el primero en comentar!</small>
                </div>
            `);
            return;
        }

        let html = '';
        comentarios.forEach(comentario => {
            html += generarHtmlComentario(comentario);
        });
        
        container.html(html);
    }

    function generarHtmlComentario(comentario, nivel = 0) {
        const margenIzquierdo = nivel * 30; // 30px por nivel de anidación
        const maxNivel = 5; // Máximo 5 niveles de anidación para no sobrecargar visualmente
        const mostrarMargen = nivel > 0 ? `style="margin-left: ${margenIzquierdo}px;"` : '';
        
        let html = `
            <div class="comment-item" data-comentario-id="${comentario.id_comentario}" ${mostrarMargen}>
                <div class="comment-header">
                    <span class="comment-author">${comentario.autor_comentario}</span>
                    <span class="comment-date">${formatearFechaHora(comentario.fec_comentario)}</span>
                </div>
                <div class="comment-content">${comentario.tex_comentario}</div>
                <div class="comment-actions">
                    ${nivel < maxNivel ? `
                        <button class="btn btn-link btn-sm p-0" onclick="responderComentario(${comentario.id_comentario})">
                            <i class="fas fa-reply"></i> Responder
                        </button>
                    ` : ''}
                </div>
                
                <!-- Formulario de respuesta (oculto inicialmente) -->
                <div class="comment-form mt-2" id="respuesta-form-${comentario.id_comentario}" style="display: none;">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Escribe tu respuesta..." 
                               id="input-respuesta-${comentario.id_comentario}"
                               onkeypress="if(event.key === 'Enter') enviarRespuesta(${comentario.id_comentario})">
                        <div class="input-group-append">
                            <button class="btn btn-primary" onclick="enviarRespuesta(${comentario.id_comentario})">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button class="btn btn-secondary" onclick="cancelarRespuesta(${comentario.id_comentario})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
        `;

        // Agregar respuestas recursivamente
        if (comentario.respuestas && comentario.respuestas.length > 0) {
            html += '<div class="replies-section mt-2">';
            comentario.respuestas.forEach(respuesta => {
                html += generarHtmlComentario(respuesta, nivel + 1);
            });
            html += '</div>';
        }

        html += '</div>';
        return html;
    }

    function agregarComentario(idContenido) {
        const input = $(`#input-comentario-${idContenido}`);
        const comentario = input.val().trim();
        
        if (comentario) {
            llamarServidor('agregar_comentario', {
                id_contenido: idContenido,
                comentario: comentario,
                id_ejecutivo: ejecutivoActual
            }, function(data, mensaje) {
                mostrarNotificacion(mensaje, 'success');
                input.val('');
                cargarComentarios(idContenido);
            });
        }
    }

    function responderComentario(idComentario) {
        // Ocultar otros formularios de respuesta
        $('[id^="respuesta-form-"]').hide();
        
        // Mostrar el formulario de respuesta para este comentario
        $(`#respuesta-form-${idComentario}`).show();
        $(`#input-respuesta-${idComentario}`).focus();
    }

    function enviarRespuesta(idComentarioPadre) {
        const input = $(`#input-respuesta-${idComentarioPadre}`);
        const respuesta = input.val().trim();
        
        if (respuesta) {
            // Encontrar el contenido al que pertenece este comentario
            const contenidoContainer = $(`#respuesta-form-${idComentarioPadre}`).closest('[data-contenido-id]');
            const idContenido = contenidoContainer.data('contenido-id');
            
            llamarServidor('agregar_comentario', {
                id_contenido: idContenido,
                comentario: respuesta,
                id_comentario_padre: idComentarioPadre,
                id_ejecutivo: ejecutivoActual
            }, function(data, mensaje) {
                mostrarNotificacion(mensaje, 'success');
                input.val('');
                $(`#respuesta-form-${idComentarioPadre}`).hide();
                cargarComentarios(idContenido);
            });
        }
    }

    function cancelarRespuesta(idComentario) {
        $(`#respuesta-form-${idComentario}`).hide();
        $(`#input-respuesta-${idComentario}`).val('');
    }

    // =====================================
    // FUNCIONES DE NAVEGACIÓN
    // =====================================

    function volverACursos() {
        $('#course-content').hide();
        cursoActual = null;
        claseActual = null;
        clasesDelCurso = [];
    }

    // =====================================
    // FUNCIONES AUXILIARES
    // =====================================

    function mostrarCargando(selector) {
        $(selector).html(`
            <div class="loading-indicator">
                <i class="fas fa-spinner fa-spin"></i> Cargando...
            </div>
        `);
    }

    function mostrarNotificacion(mensaje, tipo = 'info') {
        const claseColor = tipo === 'error' ? 'alert-danger' : tipo === 'success' ? 'alert-success' : 'alert-info';
        const icono = tipo === 'error' ? 'fa-exclamation-triangle' : tipo === 'success' ? 'fa-check-circle' : 'fa-info-circle';
        
        const notificacion = $(`
            <div class="alert ${claseColor} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
                <i class="fas ${icono}"></i> ${mensaje}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `);
        
        $('body').append(notificacion);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            notificacion.alert('close');
        }, 5000);
    }

    function getTipoClase(tipo) {
        const tipos = {
            'pdf': 'content-type-pdf',
            'video_archivo': 'content-type-video',
            'video_youtube': 'content-type-video',
            'audio': 'content-type-audio',
            'imagen': 'content-type-image'
        };
        return tipos[tipo] || '';
    }

    function getTipoTexto(tipo) {
        const tipos = {
            'pdf': 'PDF',
            'video_archivo': 'Video',
            'video_youtube': 'Video YouTube',
            'audio': 'Audio',
            'imagen': 'Imagen'
        };
        return tipos[tipo] || 'Archivo';
    }

    function generarVisualizadorContenido(contenido) {
        const rutaArchivo = `uploads/${contenido.arc_contenido}`;
        
        switch(contenido.tip_contenido) {
            case 'pdf':
                return `
                    <div class="pdf-viewer-container mb-3">
                        <div class="pdf-toolbar d-flex justify-content-between align-items-center mb-2 p-2 bg-light border rounded">
                            <div class="pdf-info">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span class="ml-2 font-weight-bold">${contenido.tit_contenido}</span>
                            </div>
                            <div class="pdf-actions">
                                <button class="btn btn-sm btn-outline-primary" onclick="abrirPDFVentana('${rutaArchivo}')">
                                    <i class="fas fa-external-link-alt"></i> Abrir en nueva ventana
                                </button>
                                <a href="${rutaArchivo}" download class="btn btn-sm btn-outline-success ml-2">
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                        <div class="pdf-iframe-container" style="position: relative; width: 100%; height: 900px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                            <iframe 
                                src="${rutaArchivo}#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH" 
                                width="100%" 
                                height="100%" 
                                style="border: none;"
                                title="Visor PDF: ${contenido.tit_contenido}">
                                <p>Tu navegador no soporta la visualización de PDFs. 
                                   <a href="${rutaArchivo}" target="_blank">Haz clic aquí para descargar el archivo</a>
                                </p>
                            </iframe>
                        </div>
                    </div>
                `;
                
            case 'video_archivo':
                return `
                    <div class="video-player mb-3">
                        <video controls class="w-100" style="max-height: 400px;">
                            <source src="${rutaArchivo}" type="video/mp4">
                            Tu navegador no soporta la reproducción de video.
                        </video>
                    </div>
                `;
                
            case 'video_youtube':
                const videoId = extraerIdYoutube(contenido.url_contenido);
                return `
                    <div class="video-player mb-3">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/${videoId}" allowfullscreen></iframe>
                        </div>
                    </div>
                `;
                
            case 'audio':
                return `
                    <div class="audio-player mb-3">
                        <audio controls class="w-100">
                            <source src="${rutaArchivo}" type="audio/mpeg">
                            Tu navegador no soporta la reproducción de audio.
                        </audio>
                    </div>
                `;
                
            case 'imagen':
                return `
                    <div class="mb-3 text-center">
                        <img src="${rutaArchivo}" class="image-viewer img-fluid" alt="${contenido.tit_contenido}" 
                             style="max-height: 400px; cursor: pointer;" 
                             onclick="window.open('${rutaArchivo}', '_blank')">
                    </div>
                `;
                
            default:
                return `
                    <div class="alert alert-info">
                        <i class="fas fa-download"></i> 
                        <a href="${rutaArchivo}" target="_blank" class="alert-link">
                            Descargar archivo: ${contenido.arc_contenido}
                        </a>
                    </div>
                `;
        }
    }

    function extraerIdYoutube(url) {
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const match = url.match(regex);
        return match ? match[1] : '';
    }

    function formatearFecha(fecha) {
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function formatearFechaHora(fecha) {
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // =====================================
    // FUNCIONES PARA PDF
    // =====================================

    // Función para abrir PDF en nueva ventana
    function abrirPDFVentana(url) {
        window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
    }
</script>

</body>
</html>
