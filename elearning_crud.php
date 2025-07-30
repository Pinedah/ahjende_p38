<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD E-Learning - Gestión de Cursos</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
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
        
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: #007bff;
            color: #007bff;
        }
        
        .table-container {
            padding: 20px;
        }
        
        .btn-action {
            margin: 0 2px;
        }
        
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .card-hierarchy {
            border-left: 4px solid #007bff;
            margin-left: 20px;
        }
        
        .card-hierarchy-clase {
            border-left: 4px solid #28a745;
            margin-left: 40px;
        }
        
        .card-hierarchy-contenido {
            border-left: 4px solid #ffc107;
            margin-left: 60px;
        }
        
        .badge-tipo {
            font-size: 0.8em;
        }
        
        .tree-container {
            max-height: 600px;
            overflow-y: auto;
        }
        
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
        
        .preview-container {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9fa;
            margin-top: 10px;
        }
        
        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .file-upload-area:hover {
            border-color: #007bff;
            background: #e3f2fd;
        }
        
        .file-upload-area.dragover {
            border-color: #007bff;
            background: #e3f2fd;
            transform: scale(1.02);
        }
        
        /* Estilos para indicadores de edición */
        .edit-mode-indicator {
            border-left: 4px solid #ffc107;
            background: #fff3cd;
            animation: slideIn 0.3s ease-out;
        }
        
        .form-section.bg-warning {
            background-color: #fff3cd !important;
            border: 2px solid #ffc107;
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.3);
        }
        
        .card-header.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%) !important;
            color: #212529 !important;
        }
        
        .alert-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mejora visual para botones de edición */
        .btn-warning:focus,
        .btn-warning.focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.5);
        }
        
        /* Indicador de formulario activo */
        .form-active {
            border: 2px solid #007bff;
            box-shadow: 0 0 20px rgba(0, 123, 255, 0.1);
        }
        
        .form-editing {
            border: 2px solid #ffc107;
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.1);
        }
        
        /* Animación para el cambio de modo */
        .form-section {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header Principal -->
        <div class="main-container">
            <div class="header-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><i class="fas fa-cogs mr-2"></i>CRUD E-Learning</h3>
                        <small>Gestión de Cursos, Clases y Contenido</small>
                    </div>
                    <div>
                        <a href="elearning.php" class="btn btn-light">
                            <i class="fas fa-eye"></i> Ver E-Learning
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Navegación por tabs -->
            <div class="p-3">
                <ul class="nav nav-tabs" id="crudTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">
                            <i class="fas fa-sitemap"></i> Vista General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cursos-tab" data-toggle="tab" href="#cursos" role="tab">
                            <i class="fas fa-book"></i> Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="clases-tab" data-toggle="tab" href="#clases" role="tab">
                            <i class="fas fa-chalkboard-teacher"></i> Clases
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contenidos-tab" data-toggle="tab" href="#contenidos" role="tab">
                            <i class="fas fa-file-alt"></i> Contenidos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenido de tabs -->
        <div class="tab-content" id="crudTabsContent">
            
            <!-- Vista General -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="main-container">
                    <div class="table-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-sitemap"></i> Estructura Jerárquica del E-Learning</h5>
                            <button class="btn btn-primary" onclick="cargarVistaGeneral()">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                        </div>
                        <div class="tree-container" id="tree-container">
                            <!-- La estructura se cargará aquí -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Cursos -->
            <div class="tab-pane fade" id="cursos" role="tabpanel">
                <div class="main-container">
                    <div class="table-container">
                        <!-- Formulario de curso -->
                        <div class="form-section">
                            <h5><i class="fas fa-plus-circle"></i> <span id="curso-form-title">Agregar Nuevo Curso</span></h5>
                            <form id="curso-form">
                                <input type="hidden" id="curso-id" name="id_curso">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="curso-nombre">Nombre del Curso *</label>
                                            <input type="text" class="form-control" id="curso-nombre" name="nom_curso" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="curso-creador">Ejecutivo Creador *</label>
                                            <select class="form-control" id="curso-creador" name="id_eje_creador" required>
                                                <option value="">Seleccionar ejecutivo...</option>
                                                <!-- Los ejecutivos se cargarán dinámicamente -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="curso-descripcion">Descripción</label>
                                    <textarea class="form-control" id="curso-descripcion" name="des_curso" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> <span id="curso-btn-text">Guardar Curso</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary ml-2" onclick="limpiarFormularioCurso()">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Lista de cursos -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-list"></i> Lista de Cursos</h5>
                            <button class="btn btn-info" onclick="cargarCursos()">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Creador</th>
                                        <th>Fecha</th>
                                        <th>Clases</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cursos-list">
                                    <!-- Los cursos se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Clases -->
            <div class="tab-pane fade" id="clases" role="tabpanel">
                <div class="main-container">
                    <div class="table-container">
                        <!-- Formulario de clase -->
                        <div class="form-section">
                            <h5><i class="fas fa-plus-circle"></i> <span id="clase-form-title">Agregar Nueva Clase</span></h5>
                            <form id="clase-form">
                                <input type="hidden" id="clase-id" name="id_clase">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clase-curso">Curso *</label>
                                            <select class="form-control" id="clase-curso" name="id_curso" required>
                                                <option value="">Seleccionar curso...</option>
                                                <!-- Los cursos se cargarán dinámicamente -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clase-titulo">Título de la Clase *</label>
                                            <input type="text" class="form-control" id="clase-titulo" name="tit_clase" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clase-orden">Orden *</label>
                                            <input type="number" class="form-control" id="clase-orden" name="ord_clase" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clase-creador">Ejecutivo Creador *</label>
                                            <select class="form-control" id="clase-creador" name="id_eje_creador" required>
                                                <option value="">Seleccionar ejecutivo...</option>
                                                <!-- Los ejecutivos se cargarán dinámicamente -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="clase-descripcion">Descripción</label>
                                    <textarea class="form-control" id="clase-descripcion" name="des_clase" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> <span id="clase-btn-text">Guardar Clase</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary ml-2" onclick="limpiarFormularioClase()">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Lista de clases -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-list"></i> Lista de Clases</h5>
                            <div>
                                <select class="form-control d-inline-block w-auto mr-2" id="filtro-curso-clases" onchange="cargarClases()">
                                    <option value="">Todos los cursos</option>
                                    <!-- Los cursos se cargarán dinámicamente -->
                                </select>
                                <button class="btn btn-info" onclick="cargarClases()">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Curso</th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Orden</th>
                                        <th>Creador</th>
                                        <th>Contenidos</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="clases-list">
                                    <!-- Las clases se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Contenidos -->
            <div class="tab-pane fade" id="contenidos" role="tabpanel">
                <div class="main-container">
                    <div class="table-container">
                        <!-- Formulario de contenido -->
                        <div class="form-section">
                            <h5><i class="fas fa-plus-circle"></i> <span id="contenido-form-title">Agregar Nuevo Contenido</span></h5>
                            <form id="contenido-form" enctype="multipart/form-data">
                                <input type="hidden" id="contenido-id" name="id_contenido">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido-clase">Clase *</label>
                                            <select class="form-control" id="contenido-clase" name="id_clase" required>
                                                <option value="">Seleccionar clase...</option>
                                                <!-- Las clases se cargarán dinámicamente -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido-titulo">Título del Contenido *</label>
                                            <input type="text" class="form-control" id="contenido-titulo" name="tit_contenido" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contenido-tipo">Tipo de Contenido *</label>
                                            <select class="form-control" id="contenido-tipo" name="tip_contenido" required onchange="toggleContenidoFields()">
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="video_archivo">Video (Archivo)</option>
                                                <option value="video_youtube">Video (YouTube)</option>
                                                <option value="audio">Audio</option>
                                                <option value="pdf">PDF</option>
                                                <option value="imagen">Imagen</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contenido-orden">Orden *</label>
                                            <input type="number" class="form-control" id="contenido-orden" name="ord_contenido" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contenido-creador">Ejecutivo Creador *</label>
                                            <select class="form-control" id="contenido-creador" name="id_eje_creador" required>
                                                <option value="">Seleccionar ejecutivo...</option>
                                                <!-- Los ejecutivos se cargarán dinámicamente -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Campo para archivo -->
                                <div class="form-group" id="contenido-archivo-group" style="display: none;">
                                    <label for="contenido-archivo">Archivo</label>
                                    <div class="file-upload-area" onclick="document.getElementById('contenido-archivo').click()">
                                        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                        <p class="mb-0">Haz clic para seleccionar archivo o arrastra aquí</p>
                                        <small class="text-muted">Formatos permitidos: PDF, MP4, MP3, JPG, PNG, GIF</small>
                                    </div>
                                    <input type="file" class="form-control-file d-none" id="contenido-archivo" name="archivo" accept=".pdf,.mp4,.mp3,.jpg,.jpeg,.png,.gif">
                                    <div id="archivo-preview" class="preview-container" style="display: none;"></div>
                                </div>
                                
                                <!-- Campo para URL de YouTube -->
                                <div class="form-group" id="contenido-url-group" style="display: none;">
                                    <label for="contenido-url">URL de YouTube *</label>
                                    <input type="url" class="form-control" id="contenido-url" name="url_contenido" placeholder="https://www.youtube.com/watch?v=...">
                                    <small class="form-text text-muted">Ingresa la URL completa del video de YouTube</small>
                                    <div id="youtube-preview" class="preview-container" style="display: none;"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="contenido-descripcion">Descripción</label>
                                    <textarea class="form-control" id="contenido-descripcion" name="des_contenido" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> <span id="contenido-btn-text">Guardar Contenido</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary ml-2" onclick="limpiarFormularioContenido()">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Lista de contenidos -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-list"></i> Lista de Contenidos</h5>
                            <div>
                                <select class="form-control d-inline-block w-auto mr-2" id="filtro-clase-contenidos" onchange="cargarContenidos()">
                                    <option value="">Todas las clases</option>
                                    <!-- Las clases se cargarán dinámicamente -->
                                </select>
                                <button class="btn btn-info" onclick="cargarContenidos()">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Clase</th>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Archivo/URL</th>
                                        <th>Orden</th>
                                        <th>Creador</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="contenidos-list">
                                    <!-- Los contenidos se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="delete-message">¿Estás seguro de que deseas eliminar este elemento?</p>
                <div class="alert alert-warning">
                    <small><i class="fas fa-info-circle"></i> Esta acción no se puede deshacer.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let ejecutivoActual = 1; // Usuario logueado por defecto
    let deleteCallback = null;

    // Inicializar al cargar la página
    $(document).ready(function() {
        cargarEjecutivos();
        cargarVistaGeneral();
        cargarCursos();
        
        // Configurar event listeners
        setupEventListeners();
    });

    // =====================================
    // FUNCIONES DE COMUNICACIÓN CON EL SERVIDOR
    // =====================================
    
    function llamarServidor(action, data = {}, callback = null, errorCallback = null) {
        // Verificar si data es FormData
        if (data instanceof FormData) {
            data.append('action', action);
            
            $.ajax({
                url: 'server/controlador_elearning.php',
                type: 'POST',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
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
                    console.error('Error en la comunicación:', error, xhr.responseText);
                    const mensaje = 'Error de comunicación con el servidor';
                    if (errorCallback) {
                        errorCallback(mensaje);
                    } else {
                        mostrarNotificacion(mensaje, 'error');
                    }
                }
            });
        } else {
            // Para objetos normales
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
                    console.error('Error en la comunicación:', error, xhr.responseText);
                    const mensaje = 'Error de comunicación con el servidor';
                    if (errorCallback) {
                        errorCallback(mensaje);
                    } else {
                        mostrarNotificacion(mensaje, 'error');
                    }
                }
            });
        }
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

    function formatearFecha(fecha) {
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function setupEventListeners() {
        // Event listeners para formularios
        $('#curso-form').on('submit', guardarCurso);
        $('#clase-form').on('submit', guardarClase);
        $('#contenido-form').on('submit', guardarContenido);
        
        // Event listener para tabs
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).attr("href");
            switch(target) {
                case '#cursos':
                    cargarCursos();
                    break;
                case '#clases':
                    cargarClases();
                    break;
                case '#contenidos':
                    cargarContenidos();
                    break;
                case '#overview':
                    cargarVistaGeneral();
                    break;
            }
        });
        
        // Event listener para archivo
        $('#contenido-archivo').on('change', function() {
            previewArchivo(this.files[0]);
        });
        
        // Event listener para URL de YouTube
        $('#contenido-url').on('blur', function() {
            previewYoutube($(this).val());
        });
    }

    // =====================================
    // FUNCIONES DE CARGA DE DATOS
    // =====================================

    function cargarEjecutivos() {
        llamarServidor('obtener_ejecutivos', {}, function(ejecutivos) {
            const selects = ['#curso-creador', '#clase-creador', '#contenido-creador'];
            selects.forEach(selector => {
                const select = $(selector);
                select.empty().append('<option value="">Seleccionar ejecutivo...</option>');
                ejecutivos.forEach(ejecutivo => {
                    select.append(`<option value="${ejecutivo.id_eje}">${ejecutivo.nom_eje}</option>`);
                });
            });
        });
    }

    function cargarVistaGeneral() {
        mostrarCargando('#tree-container');
        
        llamarServidor('obtener_estructura_completa', {}, function(estructura) {
            mostrarEstructuraJerarquica(estructura);
        }, function(error) {
            $('#tree-container').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar estructura: ${error}
                </div>
            `);
        });
    }

    function mostrarEstructuraJerarquica(cursos) {
        const container = $('#tree-container');
        
        if (!cursos || cursos.length === 0) {
            container.html(`
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h5>No hay estructura disponible</h5>
                    <p>Comienza creando tu primer curso.</p>
                </div>
            `);
            return;
        }

        let html = '';
        cursos.forEach(curso => {
            html += `
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-book"></i> 
                            <strong>${curso.nom_curso}</strong>
                            <span class="badge badge-light ml-2">${curso.clases ? curso.clases.length : 0} clases</span>
                        </div>
                        <div>
                            <small>${formatearFecha(curso.fec_creacion_curso)}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        ${curso.des_curso ? `<p class="text-muted">${curso.des_curso}</p>` : ''}
                        ${curso.clases && curso.clases.length > 0 ? generarClasesHtml(curso.clases) : '<p class="text-muted">No hay clases en este curso.</p>'}
                    </div>
                </div>
            `;
        });
        
        container.html(html);
    }

    function generarClasesHtml(clases) {
        let html = '';
        clases.forEach(clase => {
            html += `
                <div class="card card-hierarchy mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title mb-1">
                                    <i class="fas fa-chalkboard-teacher"></i> ${clase.tit_clase}
                                    <span class="badge badge-success ml-2">Orden ${clase.ord_clase}</span>
                                    <span class="badge badge-info ml-1">${clase.contenidos ? clase.contenidos.length : 0} contenidos</span>
                                </h6>
                                ${clase.des_clase ? `<p class="card-text text-muted small">${clase.des_clase}</p>` : ''}
                            </div>
                        </div>
                        ${clase.contenidos && clase.contenidos.length > 0 ? generarContenidosHtml(clase.contenidos) : ''}
                    </div>
                </div>
            `;
        });
        return html;
    }

    function generarContenidosHtml(contenidos) {
        let html = '<div class="mt-2">';
        contenidos.forEach(contenido => {
            const tipoIcon = getTipoIcon(contenido.tip_contenido);
            const tipoBadge = getTipoBadge(contenido.tip_contenido);
            
            html += `
                <div class="card card-hierarchy-contenido mb-1">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge ${tipoBadge} badge-tipo mr-2">${tipoIcon} ${contenido.tip_contenido}</span>
                                <strong>${contenido.tit_contenido}</strong>
                                <span class="badge badge-secondary ml-2">Orden ${contenido.ord_contenido}</span>
                            </div>
                            <small class="text-muted">${formatearFecha(contenido.fec_creacion_contenido)}</small>
                        </div>
                        ${contenido.des_contenido ? `<p class="text-muted small mt-1 mb-0">${contenido.des_contenido}</p>` : ''}
                    </div>
                </div>
            `;
        });
        html += '</div>';
        return html;
    }

    function getTipoIcon(tipo) {
        const iconos = {
            'video_archivo': '<i class="fas fa-video"></i>',
            'video_youtube': '<i class="fab fa-youtube"></i>',
            'audio': '<i class="fas fa-volume-up"></i>',
            'pdf': '<i class="fas fa-file-pdf"></i>',
            'imagen': '<i class="fas fa-image"></i>'
        };
        return iconos[tipo] || '<i class="fas fa-file"></i>';
    }

    function getTipoBadge(tipo) {
        const badges = {
            'video_archivo': 'badge-primary',
            'video_youtube': 'badge-danger',
            'audio': 'badge-info',
            'pdf': 'badge-warning',
            'imagen': 'badge-success'
        };
        return badges[tipo] || 'badge-secondary';
    }

    // =====================================
    // FUNCIONES DE CURSOS
    // =====================================

    function cargarCursos() {
        mostrarCargando('#cursos-list');
        
        // Cargar cursos para selects
        llamarServidor('obtener_cursos', {}, function(cursos) {
            mostrarCursos(cursos);
            
            // Actualizar selects de cursos
            const selects = ['#clase-curso', '#filtro-curso-clases'];
            selects.forEach(selector => {
                const select = $(selector);
                const valorActual = select.val();
                select.empty();
                
                if (selector.includes('filtro')) {
                    select.append('<option value="">Todos los cursos</option>');
                } else {
                    select.append('<option value="">Seleccionar curso...</option>');
                }
                
                cursos.forEach(curso => {
                    select.append(`<option value="${curso.id_curso}">${curso.nom_curso}</option>`);
                });
                
                if (valorActual) select.val(valorActual);
            });
        });
    }

    function mostrarCursos(cursos) {
        const tbody = $('#cursos-list');
        
        if (!cursos || cursos.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-book-open fa-2x mb-2"></i><br>
                        No hay cursos disponibles
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        cursos.forEach(curso => {
            html += `
                <tr>
                    <td>${curso.id_curso}</td>
                    <td><strong>${curso.nom_curso}</strong></td>
                    <td>${curso.des_curso || '-'}</td>
                    <td>${curso.nom_eje || 'Sin asignar'}</td>
                    <td>${formatearFecha(curso.fec_creacion_curso)}</td>
                    <td>
                        <span class="badge badge-info">${curso.total_clases || 0}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" onclick="editarCurso(${curso.id_curso})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" onclick="eliminarCurso(${curso.id_curso}, '${curso.nom_curso}')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.html(html);
    }

    function guardarCurso(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const esEdicion = !!formData.get('id_curso');
        
        // Agregar ejecutivo actual si no se especifica
        if (!formData.get('id_eje_creador')) {
            formData.set('id_eje_creador', ejecutivoActual);
        }
        
        const action = esEdicion ? 'actualizar_curso' : 'crear_curso';
        
        // Debug: mostrar datos del formulario
        console.log('Datos del formulario curso:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        llamarServidor(action, formData, function(response, mensaje) {
            mostrarNotificacion(mensaje, 'success');
            limpiarFormularioCurso();
            cargarCursos();
            cargarVistaGeneral();
        });
    }

    function editarCurso(id) {
        llamarServidor('obtener_curso', { id_curso: id }, function(curso) {
            $('#curso-id').val(curso.id_curso);
            $('#curso-nombre').val(curso.nom_curso);
            $('#curso-descripcion').val(curso.des_curso);
            $('#curso-creador').val(curso.id_eje_creador);
            
            // Cambiar UI para modo edición
            $('#curso-form-title').html('<i class="fas fa-edit text-warning"></i> Editar Curso');
            $('#curso-btn-text').text('Actualizar Curso');
            $('#curso-form').closest('.form-section').removeClass('bg-light').addClass('bg-warning').css('background-color', '#fff3cd');
            $('#curso-form button[type="submit"]').removeClass('btn-primary').addClass('btn-warning');
            
            // Mostrar indicador de modo edición
            if (!$('#curso-edit-indicator').length) {
                $('#curso-form').closest('.form-section').prepend(`
                    <div id="curso-edit-indicator" class="alert alert-warning alert-sm mb-3">
                        <i class="fas fa-edit"></i> <strong>Modo Edición:</strong> Modificando curso existente
                        <button type="button" class="btn btn-sm btn-outline-warning ml-2" onclick="limpiarFormularioCurso()">
                            <i class="fas fa-times"></i> Cancelar edición
                        </button>
                    </div>
                `);
            }
            
            // Scroll al formulario
            $('html, body').animate({
                scrollTop: $('#curso-form').offset().top - 100
            }, 500);
        });
    }

    function eliminarCurso(id, nombre) {
        mostrarModalEliminar(
            `¿Estás seguro de eliminar el curso "<strong>${nombre}</strong>"?<br><small>Esto también eliminará todas sus clases y contenidos.</small>`,
            function() {
                llamarServidor('eliminar_curso', { id_curso: id }, function(response, mensaje) {
                    mostrarNotificacion(mensaje, 'success');
                    cargarCursos();
                    cargarVistaGeneral();
                    $('#deleteModal').modal('hide');
                });
            }
        );
    }

    function limpiarFormularioCurso() {
        $('#curso-form')[0].reset();
        $('#curso-id').val('');
        
        // Restaurar UI para modo creación
        $('#curso-form-title').html('<i class="fas fa-plus-circle text-primary"></i> Agregar Nuevo Curso');
        $('#curso-btn-text').text('Guardar Curso');
        $('#curso-form').closest('.form-section').removeClass('bg-warning').addClass('bg-light').css('background-color', '#f8f9fa');
        $('#curso-form button[type="submit"]').removeClass('btn-warning').addClass('btn-primary');
        
        // Remover indicador de edición
        $('#curso-edit-indicator').remove();
    }

    // =====================================
    // FUNCIONES DE CLASES
    // =====================================

    function cargarClases() {
        mostrarCargando('#clases-list');
        
        const filtro = $('#filtro-curso-clases').val();
        const data = filtro ? { id_curso: filtro } : {};
        
        llamarServidor('obtener_clases', data, function(clases) {
            mostrarClases(clases);
            
            // Actualizar select de clases para contenidos
            const select = $('#contenido-clase');
            const valorActual = select.val();
            select.empty().append('<option value="">Seleccionar clase...</option>');
            
            clases.forEach(clase => {
                select.append(`<option value="${clase.id_clase}">${clase.nom_curso} - ${clase.tit_clase}</option>`);
            });
            
            if (valorActual) select.val(valorActual);
            
            // Actualizar filtro de contenidos
            const filtroContenidos = $('#filtro-clase-contenidos');
            const valorFiltro = filtroContenidos.val();
            filtroContenidos.empty().append('<option value="">Todas las clases</option>');
            
            clases.forEach(clase => {
                filtroContenidos.append(`<option value="${clase.id_clase}">${clase.nom_curso} - ${clase.tit_clase}</option>`);
            });
            
            if (valorFiltro) filtroContenidos.val(valorFiltro);
        });
    }

    function mostrarClases(clases) {
        const tbody = $('#clases-list');
        
        if (!clases || clases.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i><br>
                        No hay clases disponibles
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        clases.forEach(clase => {
            html += `
                <tr>
                    <td>${clase.id_clase}</td>
                    <td><span class="badge badge-primary">${clase.nom_curso}</span></td>
                    <td><strong>${clase.tit_clase}</strong></td>
                    <td>${clase.des_clase || '-'}</td>
                    <td>
                        <span class="badge badge-secondary">${clase.ord_clase}</span>
                    </td>
                    <td>${clase.nom_eje || 'Sin asignar'}</td>
                    <td>
                        <span class="badge badge-info">${clase.total_contenidos || 0}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" onclick="editarClase(${clase.id_clase})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" onclick="eliminarClase(${clase.id_clase}, '${clase.tit_clase}')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.html(html);
    }

    function guardarClase(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const esEdicion = !!formData.get('id_clase');
        
        // Agregar ejecutivo actual si no se especifica
        if (!formData.get('id_eje_creador')) {
            formData.set('id_eje_creador', ejecutivoActual);
        }
        
        const action = esEdicion ? 'actualizar_clase' : 'crear_clase';
        
        // Debug: mostrar datos del formulario
        console.log('Datos del formulario clase:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        llamarServidor(action, formData, function(response, mensaje) {
            mostrarNotificacion(mensaje, 'success');
            limpiarFormularioClase();
            cargarClases();
            cargarVistaGeneral();
        });
    }

    function editarClase(id) {
        llamarServidor('obtener_clase', { id_clase: id }, function(clase) {
            $('#clase-id').val(clase.id_clase);
            $('#clase-curso').val(clase.id_curso);
            $('#clase-titulo').val(clase.tit_clase);
            $('#clase-descripcion').val(clase.des_clase);
            $('#clase-orden').val(clase.ord_clase);
            $('#clase-creador').val(clase.id_eje_creador);
            
            // Cambiar UI para modo edición
            $('#clase-form-title').html('<i class="fas fa-edit text-warning"></i> Editar Clase');
            $('#clase-btn-text').text('Actualizar Clase');
            $('#clase-form').closest('.form-section').removeClass('bg-light').addClass('bg-warning').css('background-color', '#fff3cd');
            $('#clase-form button[type="submit"]').removeClass('btn-primary').addClass('btn-warning');
            
            // Mostrar indicador de modo edición
            if (!$('#clase-edit-indicator').length) {
                $('#clase-form').closest('.form-section').prepend(`
                    <div id="clase-edit-indicator" class="alert alert-warning alert-sm mb-3">
                        <i class="fas fa-edit"></i> <strong>Modo Edición:</strong> Modificando clase existente
                        <button type="button" class="btn btn-sm btn-outline-warning ml-2" onclick="limpiarFormularioClase()">
                            <i class="fas fa-times"></i> Cancelar edición
                        </button>
                    </div>
                `);
            }
            
            // Activar tab de clases y scroll al formulario
            $('#clases-tab').tab('show');
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: $('#clase-form').offset().top - 100
                }, 500);
            }, 100);
        });
    }

    function eliminarClase(id, titulo) {
        mostrarModalEliminar(
            `¿Estás seguro de eliminar la clase "<strong>${titulo}</strong>"?<br><small>Esto también eliminará todos sus contenidos.</small>`,
            function() {
                llamarServidor('eliminar_clase', { id_clase: id }, function(response, mensaje) {
                    mostrarNotificacion(mensaje, 'success');
                    cargarClases();
                    cargarVistaGeneral();
                    $('#deleteModal').modal('hide');
                });
            }
        );
    }

    function limpiarFormularioClase() {
        $('#clase-form')[0].reset();
        $('#clase-id').val('');
        
        // Restaurar UI para modo creación
        $('#clase-form-title').html('<i class="fas fa-plus-circle text-primary"></i> Agregar Nueva Clase');
        $('#clase-btn-text').text('Guardar Clase');
        $('#clase-form').closest('.form-section').removeClass('bg-warning').addClass('bg-light').css('background-color', '#f8f9fa');
        $('#clase-form button[type="submit"]').removeClass('btn-warning').addClass('btn-primary');
        
        // Remover indicador de edición
        $('#clase-edit-indicator').remove();
    }

    // =====================================
    // FUNCIONES DE CONTENIDOS
    // =====================================

    function cargarContenidos() {
        mostrarCargando('#contenidos-list');
        
        const filtro = $('#filtro-clase-contenidos').val();
        const data = filtro ? { id_clase: filtro } : {};
        
        llamarServidor('obtener_contenidos', data, function(contenidos) {
            mostrarContenidos(contenidos);
        });
    }

    function mostrarContenidos(contenidos) {
        const tbody = $('#contenidos-list');
        
        if (!contenidos || contenidos.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i><br>
                        No hay contenidos disponibles
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        contenidos.forEach(contenido => {
            const tipoIcon = getTipoIcon(contenido.tip_contenido);
            const tipoBadge = getTipoBadge(contenido.tip_contenido);
            
            html += `
                <tr>
                    <td>${contenido.id_contenido}</td>
                    <td>
                        <small class="text-muted">${contenido.nom_curso}</small><br>
                        <strong>${contenido.tit_clase}</strong>
                    </td>
                    <td><strong>${contenido.tit_contenido}</strong></td>
                    <td>
                        <span class="badge ${tipoBadge}">${tipoIcon} ${contenido.tip_contenido}</span>
                    </td>
                    <td>
                        ${contenido.arc_contenido ? `<a href="uploads/${contenido.arc_contenido}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>` : ''}
                        ${contenido.url_contenido ? `<a href="${contenido.url_contenido}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="fab fa-youtube"></i></a>` : ''}
                    </td>
                    <td>
                        <span class="badge badge-secondary">${contenido.ord_contenido}</span>
                    </td>
                    <td>${contenido.nom_eje || 'Sin asignar'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" onclick="editarContenido(${contenido.id_contenido})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" onclick="eliminarContenido(${contenido.id_contenido}, '${contenido.tit_contenido}')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.html(html);
    }

    function toggleContenidoFields() {
        const tipo = $('#contenido-tipo').val();
        const archivoGroup = $('#contenido-archivo-group');
        const urlGroup = $('#contenido-url-group');
        const archivoInput = $('#contenido-archivo');
        const urlInput = $('#contenido-url');
        
        // Ocultar todos los campos
        archivoGroup.hide();
        urlGroup.hide();
        
        // Limpiar required
        archivoInput.removeAttr('required');
        urlInput.removeAttr('required');
        
        // Mostrar campo apropiado
        if (tipo === 'video_youtube') {
            urlGroup.show();
            urlInput.attr('required', true);
        } else if (tipo && tipo !== '') {
            archivoGroup.show();
            // Note: File input no puede ser required si estamos editando
        }
    }

    function guardarContenido(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const esEdicion = !!formData.get('id_contenido');
        
        // Agregar ejecutivo actual si no se especifica
        if (!formData.get('id_eje_creador')) {
            formData.set('id_eje_creador', ejecutivoActual);
        }
        
        const action = esEdicion ? 'actualizar_contenido' : 'crear_contenido';
        formData.set('action', action);
        
        $.ajax({
            url: 'server/controlador_elearning.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    mostrarNotificacion(response.message, 'success');
                    limpiarFormularioContenido();
                    cargarContenidos();
                    cargarVistaGeneral();
                } else {
                    mostrarNotificacion('Error: ' + response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                mostrarNotificacion('Error de comunicación con el servidor', 'error');
            }
        });
    }

    function editarContenido(id) {
        llamarServidor('obtener_contenido', { id_contenido: id }, function(contenido) {
            $('#contenido-id').val(contenido.id_contenido);
            $('#contenido-clase').val(contenido.id_clase);
            $('#contenido-titulo').val(contenido.tit_contenido);
            $('#contenido-tipo').val(contenido.tip_contenido);
            $('#contenido-descripcion').val(contenido.des_contenido);
            $('#contenido-orden').val(contenido.ord_contenido);
            $('#contenido-creador').val(contenido.id_eje_creador);
            $('#contenido-url').val(contenido.url_contenido);
            
            // Mostrar campos apropiados
            toggleContenidoFields();
            
            // Si hay archivo existente, mostrar info
            if (contenido.arc_contenido) {
                $('#archivo-preview').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-file"></i> Archivo actual: <strong>${contenido.arc_contenido}</strong>
                        <br><small>Selecciona un nuevo archivo solo si deseas reemplazarlo.</small>
                    </div>
                `).show();
            }
            
            // Cambiar UI para modo edición
            $('#contenido-form-title').html('<i class="fas fa-edit text-warning"></i> Editar Contenido');
            $('#contenido-btn-text').text('Actualizar Contenido');
            $('#contenido-form').closest('.form-section').removeClass('bg-light').addClass('bg-warning').css('background-color', '#fff3cd');
            $('#contenido-form button[type="submit"]').removeClass('btn-primary').addClass('btn-warning');
            
            // Mostrar indicador de modo edición
            if (!$('#contenido-edit-indicator').length) {
                $('#contenido-form').closest('.form-section').prepend(`
                    <div id="contenido-edit-indicator" class="alert alert-warning alert-sm mb-3">
                        <i class="fas fa-edit"></i> <strong>Modo Edición:</strong> Modificando contenido existente
                        <button type="button" class="btn btn-sm btn-outline-warning ml-2" onclick="limpiarFormularioContenido()">
                            <i class="fas fa-times"></i> Cancelar edición
                        </button>
                    </div>
                `);
            }
            
            // Activar tab de contenidos y scroll al formulario
            $('#contenidos-tab').tab('show');
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: $('#contenido-form').offset().top - 100
                }, 500);
            }, 100);
        });
    }

    function eliminarContenido(id, titulo) {
        mostrarModalEliminar(
            `¿Estás seguro de eliminar el contenido "<strong>${titulo}</strong>"?`,
            function() {
                llamarServidor('eliminar_contenido', { id_contenido: id }, function(response, mensaje) {
                    mostrarNotificacion(mensaje, 'success');
                    cargarContenidos();
                    cargarVistaGeneral();
                    $('#deleteModal').modal('hide');
                });
            }
        );
    }

    function limpiarFormularioContenido() {
        $('#contenido-form')[0].reset();
        $('#contenido-id').val('');
        
        // Restaurar UI para modo creación
        $('#contenido-form-title').html('<i class="fas fa-plus-circle text-primary"></i> Agregar Nuevo Contenido');
        $('#contenido-btn-text').text('Guardar Contenido');
        $('#contenido-form').closest('.form-section').removeClass('bg-warning').addClass('bg-light').css('background-color', '#f8f9fa');
        $('#contenido-form button[type="submit"]').removeClass('btn-warning').addClass('btn-primary');
        
        // Remover indicador de edición
        $('#contenido-edit-indicator').remove();
        
        $('#archivo-preview').hide();
        $('#youtube-preview').hide();
        toggleContenidoFields();
    }

    // =====================================
    // FUNCIONES DE PREVIEW
    // =====================================

    function previewArchivo(file) {
        if (!file) return;
        
        const preview = $('#archivo-preview');
        const maxSize = 50 * 1024 * 1024; // 50MB
        
        if (file.size > maxSize) {
            preview.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> El archivo es demasiado grande. Máximo 50MB.
                </div>
            `).show();
            $('#contenido-archivo').val('');
            return;
        }
        
        const allowedTypes = {
            'video/mp4': 'Video MP4',
            'audio/mpeg': 'Audio MP3',
            'audio/mp3': 'Audio MP3',
            'application/pdf': 'Documento PDF',
            'image/jpeg': 'Imagen JPEG',
            'image/png': 'Imagen PNG',
            'image/gif': 'Imagen GIF'
        };
        
        if (!allowedTypes[file.type]) {
            preview.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Tipo de archivo no permitido.
                </div>
            `).show();
            $('#contenido-archivo').val('');
            return;
        }
        
        preview.html(`
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <strong>${file.name}</strong> (${allowedTypes[file.type]})
                <br><small>Tamaño: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
            </div>
        `).show();
    }

    function previewYoutube(url) {
        const preview = $('#youtube-preview');
        
        if (!url) {
            preview.hide();
            return;
        }
        
        // Extraer ID del video de YouTube
        const videoId = extractYouTubeID(url);
        
        if (!videoId) {
            preview.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> URL de YouTube no válida.
                </div>
            `).show();
            return;
        }
        
        preview.html(`
            <div class="alert alert-success">
                <i class="fab fa-youtube"></i> Video de YouTube detectado
                <br><small>ID: ${videoId}</small>
            </div>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
            </div>
        `).show();
    }

    function extractYouTubeID(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    // =====================================
    // FUNCIONES DE MODAL
    // =====================================

    function mostrarModalEliminar(mensaje, callback) {
        $('#delete-message').html(mensaje);
        deleteCallback = callback;
        $('#deleteModal').modal('show');
    }

    // Event listener para confirmación de eliminación
    $('#confirm-delete').on('click', function() {
        if (deleteCallback) {
            deleteCallback();
            deleteCallback = null;
        }
    });

    // Limpiar callback cuando se cierra el modal
    $('#deleteModal').on('hidden.bs.modal', function() {
        deleteCallback = null;
    });

</script>

</body>
</html>
