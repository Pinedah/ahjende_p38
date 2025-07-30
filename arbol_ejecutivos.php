<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 10 - Árbol Recursivo de Ejecutivos</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- jsTree CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/themes/default/style.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jsTree JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/jstree.min.js"></script>
    
    <style>
        /* Estilos para mantener sinergia con index.php */
        .horario-column {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        
        .filter-section {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .filter-section label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .search-section {
            background-color: #f1f3f4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        /* Contenedores principales */
        .tree-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            min-height: 500px;
        }
        
        .actions-panel {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }
        
        .info-panel {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        /* Panel de estadísticas similar a cards en index.php */
        .stats-container {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Estilos del árbol jsTree */
        #jstree {
            background-color: white;
            border-radius: 5px;
            padding: 15px;
            min-height: 400px;
            border: 1px solid #dee2e6;
        }
        
        .btn-action {
            margin: 5px;
            min-width: 120px;
        }
        
        .status-badge {
            font-size: 0.8em;
            margin-left: 5px;
        }
        
        .node-info {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
            border-left: 4px solid #007bff;
        }
        
        .breadcrumb-custom {
            background-color: #e9ecef;
            border-radius: 5px;
            padding: 8px 15px;
            margin-bottom: 15px;
        }
        
        /* Estilos para drag & drop mejorados */
        .jstree-dnd-helper {
            background: #007bff !important;
            color: white !important;
            border-radius: 3px !important;
            padding: 5px 10px !important;
            font-weight: bold !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
        }
        
        .jstree-dnd-helper .jstree-icon {
            color: white !important;
        }
        
        /* Indicador visual para drop zones */
        .jstree-drop {
            background-color: rgba(0, 123, 255, 0.15) !important;
            border: 2px dashed #007bff !important;
            border-radius: 3px !important;
        }
        
        /* Estilos para nodos siendo arrastrados */
        .jstree-dragged {
            opacity: 0.6 !important;
        }
        
        /* Mejores estilos para los badges similar a index.php */
        .badge {
            font-size: 0.8em;
            padding: 0.4em 0.6em;
        }
        
        /* Estilos para modales similar a index.php */
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        
        .modal-header .close {
            color: white;
            opacity: 0.8;
        }
        
        .modal-header .close:hover {
            opacity: 1;
        }
        
        /* Mensaje de estado para drag & drop */
        .drag-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .drag-status.error {
            background: #dc3545;
        }
        
        .drag-status.success {
            background: #28a745;
        }
        
        /* Estilos adicionales para mejorar jsTree similar a index.php */
        .jstree-default .jstree-node {
            min-height: 30px;
            line-height: 30px;
            margin-left: 0px;
            min-width: 24px;
        }
        
        .jstree-default .jstree-anchor {
            line-height: 30px;
            height: 30px;
            padding: 0 4px 0 1px;
        }
        
        .jstree-default .jstree-icon {
            width: 18px;
            height: 18px;
            line-height: 18px;
            margin-top: 6px;
        }
        
        /* Estilos para imágenes de ejecutivos como iconos */
        .jstree-default .jstree-anchor .jstree-icon {
            background-size: cover;
            background-position: center;
            border-radius: 50%;
            border: 1px solid #ddd;
        }
        
        /* Estilos específicos para imágenes reemplazadas */
        .jstree-default .jstree-anchor img.ejecutivo-imagen {
            vertical-align: middle;
            display: inline-block;
            cursor: pointer;
        }
        
        /* Prevenir herencia de imágenes completamente - cada nodo debe ser independiente */
        .jstree-anchor {
            background-image: none !important;
        }
        
        /* Solo ocultar íconos Font Awesome específicos cuando hay imagen personalizada en ESE nodo específico */
        .jstree-anchor:has(.ejecutivo-imagen) .jstree-icon.fas {
            display: none !important;
        }
        
        /* Asegurar que las imágenes de ejecutivos siempre sean visibles y no se hereden */
        .jstree-anchor .ejecutivo-imagen {
            display: inline-block !important;
            visibility: visible !important;
            position: relative !important;
            z-index: 10 !important;
        }
        
        /* Prevenir que los estilos de background-image se apliquen a nodos sin imagen propia */
        .jstree-default .jstree-anchor .jstree-icon {
            background-image: none !important;
        }
        
        /* Asegurar prioridad de las imágenes sobre íconos en nodos raíz */
        .jstree-root-level .ejecutivo-imagen {
            display: inline-block !important;
            visibility: visible !important;
            z-index: 15 !important;
        }
        
        /* Ocultar íconos cuando hay imagen en nodos raíz específicamente */
        .jstree-root-level:has(.ejecutivo-imagen) .jstree-icon {
            display: none !important;
        }
        
        /* Estilos para el header similar a index.php */
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .card-header h4 {
            margin-bottom: 5px;
            color: #495057;
        }
        
        .card-header small {
            color: #6c757d;
        }
        
        /* Botones de acción con colores consistentes */
        .btn-outline-primary:hover,
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .btn-outline-secondary:hover,
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-outline-success:hover,
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .btn-outline-warning:hover,
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        
        .btn-outline-info:hover,
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        
        /* Estilos adicionales para mejorar la sangría del árbol */
        .jstree-default .jstree-children {
            margin-left: 40px;
            position: relative;
        }
        
        /* Línea vertical principal */
        .jstree-default .jstree-children:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 0;
            bottom: 18px;
            width: 2px;
            background: linear-gradient(to bottom, #007bff, #0056b3);
            opacity: 0.7;
            border-radius: 1px;
        }
        
        /* Líneas horizontales para cada nodo */
        .jstree-default .jstree-children .jstree-node {
            position: relative;
        }
        
        .jstree-default .jstree-children .jstree-node:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 18px;
            width: 22px;
            height: 2px;
            background: linear-gradient(to right, #007bff, #0056b3);
            opacity: 0.7;
            border-radius: 1px;
        }
        
        /* Ocultar línea vertical en el último nodo */
        .jstree-default .jstree-children .jstree-node:last-child:after {
            content: '';
            position: absolute;
            left: -26px;
            top: 20px;
            bottom: -18px;
            width: 4px;
            background: white;
            z-index: 1;
        }
        
        /* Estilos específicos por nivel de profundidad mejorados */
        .jstree-root-level > .jstree-anchor {
            font-weight: bold;
            border-left: 4px solid #007bff;
            padding-left: 12px;
            background: linear-gradient(to right, rgba(0, 123, 255, 0.1), transparent);
            font-size: 15px;
        }
        
        .jstree-level-2 > .jstree-anchor {
            border-left: 3px solid #28a745;
            padding-left: 10px;
            background: linear-gradient(to right, rgba(40, 167, 69, 0.08), transparent);
            font-size: 14px;
        }
        
        .jstree-level-3 > .jstree-anchor {
            border-left: 2px solid #ffc107;
            padding-left: 8px;
            background: linear-gradient(to right, rgba(255, 193, 7, 0.08), transparent);
            font-style: italic;
            font-size: 13px;
        }
        
        /* Estilos adicionales para niveles más profundos */
        .jstree-level-4 > .jstree-anchor {
            border-left: 2px solid #dc3545;
            padding-left: 8px;
            background: linear-gradient(to right, rgba(220, 53, 69, 0.08), transparent);
            font-size: 13px;
            opacity: 0.9;
        }
        
        .jstree-level-5 > .jstree-anchor {
            border-left: 2px solid #6f42c1;
            padding-left: 8px;
            background: linear-gradient(to right, rgba(111, 66, 193, 0.08), transparent);
            font-size: 12px;
            opacity: 0.9;
        }
        
        /* Efectos visuales para nodos inactivos */
        .jstree-default .jstree-node[data-type="inactive"] > .jstree-anchor {
            opacity: 0.7;
            background-color: #f8f9fa;
        }
        
        /* Mejorar la visualización jerárquica */
        .jstree-default .jstree-node {
            margin: 2px 0;
            min-height: 36px;
            line-height: 36px;
        }
        
        .jstree-default .jstree-anchor {
            padding: 0 10px 0 8px;
            border-radius: 6px;
            line-height: 36px;
            height: 36px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .jstree-default .jstree-icon {
            width: 18px;
            height: 18px;
            margin-top: 9px;
            margin-right: 8px;
        }
        
        /* Hover effects para nodos mejorados */
        .jstree-default .jstree-hovered {
            background: rgba(0, 123, 255, 0.15);
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
        }
        
        .jstree-default .jstree-clicked {
            background: rgba(0, 123, 255, 0.25);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }
        
        .jstree-default .jstree-anchor:hover {
            background: rgba(0, 123, 255, 0.1);
            text-decoration: none;
        }
        
        /* Estilos para badges de conteo de citas */
        .badge-citas-propias {
            background-color: #ffffff;
            color: #333333;
            border: 1px solid #dee2e6;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .badge-citas-propias:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
        }
        
        .badge-citas-recursivas {
            background-color: #6f42c1;
            color: #ffffff;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .badge-citas-recursivas:hover {
            background-color: #563d7c;
            transform: scale(1.05);
        }
        
        /* P32 - Card de resumen de ejecutivo */
        .ejecutivo-card {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            min-width: 280px;
            max-width: 320px;
            display: none;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .ejecutivo-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .ejecutivo-card-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #007bff;
            background-color: #f8f9fa;
            background-size: cover;
            background-position: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .ejecutivo-card-avatar.default {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #007bff;
            font-size: 20px;
        }
        
        .ejecutivo-card-info h6 {
            margin: 0 0 4px 0;
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }
        
        .ejecutivo-card-info small {
            color: #666;
            font-size: 12px;
        }
        
        .ejecutivo-card-body {
            font-size: 13px;
            line-height: 1.4;
        }
        
        .ejecutivo-card-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            align-items: center;
        }
        
        .ejecutivo-card-label {
            font-weight: 500;
            color: #555;
        }
        
        .ejecutivo-card-value {
            color: #333;
            text-align: right;
        }
        
        .semaforo-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .semaforo-verde { background-color: #d4edda; color: #155724; }
        .semaforo-amarillo { background-color: #fff3cd; color: #856404; }
        .semaforo-rojo { background-color: #f8d7da; color: #721c24; }
        .semaforo-sin-sesion { background-color: #f8f9fa; color: #6c757d; }
        
        .ejecutivo-card::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 20px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid white;
        }
        
        .ejecutivo-card::after {
            content: '';
            position: absolute;
            top: -9px;
            left: 20px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <h1 class="text-center mb-4">Práctica 10 - Árbol Recursivo de Ejecutivos</h1>
        
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4>
                            <i class="fas fa-sitemap"></i>
                            Gestión Jerárquica de Ejecutivos
                        </h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-outline-secondary" onclick="window.location.href='index.php'">
                            <i class="fas fa-arrow-left"></i> Volver a Citas
                        </button>
                        <button class="btn btn-info" onclick="recargarArbol()">
                            <i class="fas fa-sync-alt"></i> Recargar
                        </button>
                        <button class="btn btn-success" onclick="mostrarModalCrear()">
                            <i class="fas fa-plus"></i> Nuevo
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Panel de búsqueda y filtros -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="buscarTexto"><strong>Buscar Ejecutivos:</strong></label>
                            <input type="text" id="buscarTexto" class="form-control" placeholder="Buscar por nombre...">
                        </div>
                        <div class="col-md-2">
                            <label for="fechaInicio"><strong>Fecha Inicio:</strong></label>
                            <input type="date" id="fechaInicio" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="fechaFin"><strong>Fecha Fin:</strong></label>
                            <input type="date" id="fechaFin" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mostrarOcultos" checked>
                                <label class="form-check-label" for="mostrarOcultos">
                                    Mostrar ocultos
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary" onclick="aplicarFiltros()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button class="btn btn-secondary ml-2" onclick="expandirTodo()">
                                <i class="fas fa-expand-arrows-alt"></i> Expandir
                            </button>
                            <button class="btn btn-secondary ml-2" onclick="colapsarTodo()">
                                <i class="fas fa-compress-arrows-alt"></i> Colapsar
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Panel de estadísticas (estilo similar a index.php) -->
                <div class="stats-container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-primary mb-1">
                                    <i class="fas fa-users"></i>
                                    <span id="totalEjecutivos" class="badge badge-primary">-</span>
                                </h5>
                                <small class="text-muted">Total de Ejecutivos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-success mb-1">
                                    <i class="fas fa-eye"></i>
                                    <span id="ejecutivosActivos" class="badge badge-success">-</span>
                                </h5>
                                <small class="text-muted">Ejecutivos Activos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-secondary mb-1">
                                    <i class="fas fa-eye-slash"></i>
                                    <span id="ejecutivosOcultos" class="badge badge-secondary">-</span>
                                </h5>
                                <small class="text-muted">Ejecutivos Ocultos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-info mb-1">
                                    <i class="fas fa-crown"></i>
                                    <span id="nodosRaiz" class="badge badge-info">-</span>
                                </h5>
                                <small class="text-muted">Nodos Raíz</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contenedor principal con dos columnas -->
                <div class="row">
                    <!-- Columna izquierda: Árbol jerárquico -->
                    <div class="col-md-8">
                        <div class="tree-container">
                            <h4><i class="fas fa-tree"></i> Estructura Jerárquica</h4>
                            <div class="breadcrumb-custom">
                                <span id="rutaSeleccionada">Selecciona un nodo para ver su ruta</span>
                            </div>
                            <div id="jstree"></div>
                        </div>
                    </div>
                    
                    <!-- Columna derecha: Panel de acciones -->
                    <div class="col-md-4">
                        <div class="actions-panel">
                            <h4><i class="fas fa-tools"></i> Acciones</h4>
                            
                            <!-- Información del nodo seleccionado -->
                            <div id="nodoSeleccionadoInfo" style="display: none;" class="node-info">
                                <h6><i class="fas fa-user"></i> Nodo Seleccionado</h6>
                                <p><strong>ID:</strong> <span id="selectedId">-</span></p>
                                <p><strong>Nombre:</strong> <span id="selectedNombre">-</span></p>
                                <p><strong>Teléfono:</strong> <span id="selectedTelefono">-</span></p>
                                <p><strong>Estado:</strong> <span id="selectedEstado">-</span></p>
                                <p><strong>Padre:</strong> <span id="selectedPadre">-</span></p>
                            </div>
                            
                            <hr>
                            
                            <!-- Botones de acción -->
                            <div class="text-center">
                                <button class="btn btn-success btn-action" onclick="mostrarModalCrear()">
                                    <i class="fas fa-plus"></i> Crear Hijo
                                </button>
                                
                                <button class="btn btn-primary btn-action" onclick="mostrarModalEditar()" id="btnEditar" disabled>
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                
                                <button class="btn btn-warning btn-action" onclick="toggleEstado()" id="btnToggle" disabled>
                                    <i class="fas fa-eye-slash"></i> Ocultar
                                </button>
                                
                                <button class="btn btn-info btn-action" onclick="moverNodo()" id="btnMover" disabled>
                                    <i class="fas fa-arrows-alt"></i> Mover
                                </button>
                                
                                <button class="btn btn-secondary btn-action" onclick="expandirTodo()">
                                    <i class="fas fa-expand-arrows-alt"></i> Expandir Todo
                                </button>
                                
                                <button class="btn btn-secondary btn-action" onclick="colapsarTodo()">
                                    <i class="fas fa-compress-arrows-alt"></i> Colapsar Todo
                                </button>
                            </div>
                            
                            <hr>
                            
                            <!-- Filtros -->
                            <h6><i class="fas fa-filter"></i> Filtros</h6>
                            <div class="form-group">
                                <label>Buscar:</label>
                                <input type="text" class="form-control" id="buscarTextoAcciones" placeholder="Nombre del ejecutivo...">
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mostrarOcultosAcciones" checked>
                                <label class="form-check-label" for="mostrarOcultosAcciones">
                                    Mostrar ejecutivos ocultos
                                </label>
                            </div>
                            
                            <button class="btn btn-outline-primary btn-sm mt-2" onclick="aplicarFiltros()">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Modal para Crear/Editar -->
    <div class="modal fade" id="modalEjecutivo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Crear Ejecutivo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formEjecutivo" enctype="multipart/form-data">
                        <input type="hidden" id="ejecutivoId">
                        <input type="hidden" id="ejecutivoPadreId">
                        
                        <!-- Preview de imagen -->
                        <div id="preview" style="display:none; margin-bottom:15px; text-align:center;">
                            <img id="img-preview" src="" style="max-width: 150px; border: 1px solid #ddd; border-radius: 8px;">
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivoNombre"><i class="fas fa-user"></i> Nombre *</label>
                            <input type="text" class="form-control" id="ejecutivoNombre" required placeholder="Nombre completo del ejecutivo">
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivoTelefono"><i class="fas fa-phone"></i> Teléfono *</label>
                            <input type="text" class="form-control" id="ejecutivoTelefono" required placeholder="Ej: 555-1234">
                        </div>
                        
                        <div class="form-group">
                            <label for="fot_eje"><i class="fas fa-image"></i> Foto (opcional):</label>
                            <input type="file" id="fot_eje" name="fot_eje" class="form-control" accept="image/*">
                            <small class="text-muted">JPG, PNG. Máximo 50MB</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivoPadre"><i class="fas fa-sitemap"></i> Ejecutivo Padre</label>
                            <select class="form-control" id="ejecutivoPadre">
                                <option value="">Sin padre (Nodo raíz)</option>
                            </select>
                            <small class="form-text text-muted">Selecciona el ejecutivo superior en la jerarquía</small>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ejecutivoActivo" checked>
                                <label class="form-check-label" for="ejecutivoActivo">
                                    <i class="fas fa-eye"></i> Ejecutivo visible
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEjecutivo()">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para Mover Nodo -->
    <div class="modal fade" id="modalMover" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mover Ejecutivo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Mover <strong id="nombreMover"></strong> a:</p>
                    <select class="form-control" id="nuevoPadre">
                        <option value="">Sin padre (Nodo raíz)</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="confirmarMover()">
                        <i class="fas fa-arrows-alt"></i> Mover
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Variables globales
        var ejecutivos = [];
        var nodosTree = [];
        var nodoSeleccionado = null;
        var modoEdicion = false;
        
        // Variables WebSocket
        var socket = null;
        var websocketUrl = 'wss://socket.ahjende.com/wss/?encoding=text';
        var miIdEjecutivo = 1; // ID del ejecutivo actual
        var reconectarIntento = 0;
        var maxReconectarIntentos = 5;
        
        // P34 - Variables para notificación sonora
        var audioNotificacion = null;
        
        // Inicialización
        $(document).ready(function() {
            // P34 - Inicializar audio de notificación
            inicializarAudioNotificacion();
            
            // Inicializar WebSocket
            inicializarWebSocket();
            
            aplicarFiltrosDesdeURL();
            cargarEjecutivos(true); // Limpiar selección en la carga inicial
            configurarEventos();
        });
        
        function aplicarFiltrosDesdeURL() {
            // Obtener parámetros de la URL
            var params = obtenerParametrosURL();
            
            // Aplicar filtros de fecha si existen
            if (params.fechaInicio) {
                $('#fechaInicio').val(params.fechaInicio);
            }
            if (params.fechaFin) {
                $('#fechaFin').val(params.fechaFin);
            }
        }
        
        function obtenerParametrosURL() {
            var params = {};
            var queryString = window.location.search.substring(1);
            var vars = queryString.split('&');
            
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                if (pair.length === 2) {
                    params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
                }
            }
            return params;
        }
        
        // =====================================
        // FUNCIONES DE CARGA DE DATOS
        // =====================================
        
        function cargarEjecutivos(limpiarSeleccion = false) {
            // Solo limpiar estado de selección si se solicita explícitamente
            if (limpiarSeleccion) {
                nodoSeleccionado = null;
                $('#nodoSeleccionadoInfo').hide();
                $('#btnEditar, #btnToggle, #btnMover').prop('disabled', true);
            }
            
            // Obtener fechas de filtro
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            var datosEnvio = { 
                action: 'obtener_ejecutivos_con_citas'
            };
            
            // Agregar filtros de fecha si están definidos
            if (fechaInicio) {
                datosEnvio.fecha_inicio = fechaInicio;
            }
            if (fechaFin) {
                datosEnvio.fecha_fin = fechaFin;
            }
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: datosEnvio,
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    if(response.success) {
                        ejecutivos = response.data;
                        console.log('Ejecutivos cargados:', ejecutivos.length);
                        console.log('Ejecutivos:', ejecutivos);
                        actualizarEstadisticas();
                        generarArbolJsTree();
                        
                        // Si hay un nodo seleccionado, actualizar sus datos
                        if (nodoSeleccionado) {
                            var ejecutivoActualizado = ejecutivos.find(e => e.id_eje == nodoSeleccionado.id);
                            if (ejecutivoActualizado) {
                                nodoSeleccionado.data = ejecutivoActualizado;
                                mostrarInformacionNodo(ejecutivoActualizado);
                            }
                        }
                    } else {
                        alert('Error al cargar ejecutivos: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error de conexión al servidor');
                }
            });
        }
        
        // =====================================
        // FUNCIONES DE JSTREE
        // =====================================
        
        // Función para aplicar imágenes de ejecutivos (reutilizable)
        function aplicarImagenesEjecutivos() {
            console.log('Aplicando imágenes de ejecutivos...');
            
            ejecutivos.forEach(function(ejecutivo) {
                if (ejecutivo.fot_eje) {
                    var nodo = $('#jstree').find('#' + ejecutivo.id_eje);
                    
                    if (nodo.length > 0) {
                        var anchor = nodo.find('.jstree-anchor');
                        
                        // Verificar que no tenga ya una imagen aplicada
                        if (!anchor.find('.ejecutivo-imagen').length) {
                            // Crear elemento de imagen específico para este ejecutivo
                            var imgElement = $('<img>', {
                                src: 'uploads/' + ejecutivo.fot_eje,
                                css: {
                                    'width': '24px',
                                    'height': '24px',
                                    'border-radius': '50%',
                                    'border': '2px solid #007bff',
                                    'object-fit': 'cover',
                                    'margin-top': '3px',
                                    'margin-right': '8px',
                                    'cursor': 'pointer',
                                    'display': 'inline-block',
                                    'vertical-align': 'middle',
                                    'position': 'relative',
                                    'z-index': '10'
                                },
                                alt: ejecutivo.nom_eje,
                                title: 'Foto de ' + ejecutivo.nom_eje,
                                class: 'ejecutivo-imagen',
                                'data-ejecutivo-id': ejecutivo.id_eje
                            });
                            
                            // Ocultar SOLO el ícono Font Awesome de este nodo específico
                            var icono = anchor.find('.jstree-icon').first();
                            if (icono.length > 0) {
                                icono.hide();
                            }
                            
                            // Insertar imagen al inicio del anchor
                            anchor.prepend(imgElement);
                            
                            console.log('Imagen aplicada a ejecutivo:', ejecutivo.nom_eje, 'ID:', ejecutivo.id_eje, 'Nodo raíz:', !ejecutivo.id_padre);
                        } else {
                            // Ya tiene imagen, asegurar que esté visible y el ícono oculto
                            var img = anchor.find('.ejecutivo-imagen');
                            img.show();
                            anchor.find('.jstree-icon').hide();
                            console.log('Imagen ya existente mantenida para:', ejecutivo.nom_eje);
                        }
                    }
                } else {
                    // Si el ejecutivo NO tiene foto, asegurar que su ícono esté visible
                    var nodo = $('#jstree').find('#' + ejecutivo.id_eje);
                    if (nodo.length > 0) {
                        var anchor = nodo.find('.jstree-anchor');
                        // Remover cualquier imagen que pudiera haber heredado
                        anchor.find('.ejecutivo-imagen').remove();
                        // Asegurar que el ícono Font Awesome esté visible
                        anchor.find('.jstree-icon').show();
                        
                        console.log('Ícono restaurado para ejecutivo sin foto:', ejecutivo.nom_eje, 'ID:', ejecutivo.id_eje);
                    }
                }
            });
        }
        
        function generarArbolJsTree() {
            // Generar estructura de nodos para jsTree
            nodosTree = [];
            
            console.log('Generando árbol con', ejecutivos.length, 'ejecutivos');
            
            ejecutivos.forEach(function(ejecutivo) {
                var estado = ejecutivo.eli_eje == 1 ? 'visible' : 'oculto';
                var icono = ejecutivo.eli_eje == 1 ? 'fas fa-user text-success' : 'fas fa-user-slash text-muted';
                var tipo = 'default';
                
                // Determinar tipo de nodo para mejor visualización
                if (!ejecutivo.id_padre) {
                    tipo = 'root';
                    icono = 'fas fa-crown text-warning';
                } else if (ejecutivo.eli_eje == 1) {
                    tipo = 'active';
                } else {
                    tipo = 'inactive';
                }
                
                // NO establecer imagen como ícono aquí - se hará en ready.jstree
                // if (ejecutivo.fot_eje) {
                //     icono = 'uploads/' + ejecutivo.fot_eje;
                // }
                
                // Construir badges de conteo de citas
                var badgesPropias = ejecutivo.citas_propias || 0;
                var badgesRecursivas = ejecutivo.citas_recursivas || 0;
                
                var badgesCitas = '';
                if (badgesPropias > 0) {
                    badgesCitas += '<span class="badge badge-citas-propias" onclick="verDetallesCitas(' + ejecutivo.id_eje + ', \'propias\')" title="Citas propias: ' + badgesPropias + '">' + badgesPropias + '</span>';
                }
                if (badgesRecursivas > 0) {
                    badgesCitas += '<span class="badge badge-citas-recursivas" onclick="verDetallesCitas(' + ejecutivo.id_eje + ', \'recursivas\')" title="Citas totales (recursivas): ' + badgesRecursivas + '">' + badgesRecursivas + '</span>';
                }
                
                var nodo = {
                    id: ejecutivo.id_eje,
                    parent: ejecutivo.id_padre || '#',
                    text: ejecutivo.nom_eje + ' <span class="badge badge-' + (ejecutivo.eli_eje == 1 ? 'success' : 'secondary') + ' status-badge">' + estado + '</span>' + badgesCitas,
                    icon: icono,
                    type: tipo,
                    data: ejecutivo
                };
                
                nodosTree.push(nodo);
            });
            
            console.log('Nodos generados:', nodosTree.length);
            console.log('Nodos:', nodosTree);
            
            // Inicializar jsTree con drag & drop y mejor visualización jerárquica
            $('#jstree').jstree('destroy');
            
            if (nodosTree.length === 0) {
                $('#jstree').html('<p class="text-center text-muted">No hay ejecutivos para mostrar</p>');
                return;
            }
            
            $('#jstree').jstree({
                'core': {
                    'data': nodosTree,
                    'check_callback': function(operation, node, parent, position, more) {
                        // Permitir todas las operaciones de drag & drop
                        if(operation === 'move_node') {
                            // Verificar que no se mueva un nodo a sí mismo o a sus descendientes
                            return !esDescendiente(node.id, parent.id);
                        }
                        return true;
                    },
                    'themes': {
                        'responsive': true,
                        'variant': 'large',
                        'stripes': false,  // Desactivar líneas alternas para mejor legibilidad
                        'dots': true,      // Mostrar puntos de conexión
                        'icons': true
                    }
                },
                'dnd': {
                    'is_draggable': function(nodes) {
                        // Solo permitir arrastrar un nodo a la vez
                        return nodes.length === 1;
                    },
                    'copy': false
                },
                'plugins': ['search', 'state', 'wholerow', 'dnd', 'types'],
                'state': {
                    'key': 'jstree_ejecutivos_jerarquia'
                },
                'types': {
                    'default': {
                        'icon': 'fas fa-user'
                    },
                    'root': {
                        'icon': 'fas fa-crown text-warning'
                    },
                    'active': {
                        'icon': 'fas fa-user text-success'
                    },
                    'inactive': {
                        'icon': 'fas fa-user-slash text-muted'
                    }
                }
            }).on('ready.jstree', function() {
                // Expandir todo automáticamente para mostrar la jerarquía completa
                $('#jstree').jstree('open_all');
                
                // Aplicar clases específicas para mejorar la visualización primero
                setTimeout(function() {
                    $('#jstree').find('.jstree-node[aria-level="1"]').addClass('jstree-root-level');
                    $('#jstree').find('.jstree-node[aria-level="2"]').addClass('jstree-level-2');
                    $('#jstree').find('.jstree-node[aria-level="3"]').addClass('jstree-level-3');
                    $('#jstree').find('.jstree-node[aria-level="4"]').addClass('jstree-level-4');
                    $('#jstree').find('.jstree-node[aria-level="5"]').addClass('jstree-level-5');
                    
                    console.log('✅ Clases de nivel aplicadas correctamente');
                }, 100);
                
                // Aplicar imágenes de ejecutivos después de las clases
                setTimeout(function() {
                    aplicarImagenesEjecutivos();
                }, 200);
                
                // Aplicar íconos específicos por nivel SOLO para nodos sin imagen después de las imágenes
                setTimeout(function() {
                    // Añadir iconos específicos por nivel (solo para nodos sin imagen personalizada)
                    $('#jstree').find('.jstree-root-level').each(function() {
                        if (!$(this).find('.ejecutivo-imagen').length) {
                            $(this).find('.jstree-icon').removeClass('fas fa-user').addClass('fas fa-crown');
                        }
                    });
                    $('#jstree').find('.jstree-level-2').each(function() {
                        if (!$(this).find('.ejecutivo-imagen').length) {
                            $(this).find('.jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-tie');
                        }
                    });
                    $('#jstree').find('.jstree-level-3').each(function() {
                        if (!$(this).find('.ejecutivo-imagen').length) {
                            $(this).find('.jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-friends');
                        }
                    });
                    $('#jstree').find('.jstree-level-4').each(function() {
                        if (!$(this).find('.ejecutivo-imagen').length) {
                            $(this).find('.jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-check');
                        }
                    });
                    $('#jstree').find('.jstree-level-5').each(function() {
                        if (!$(this).find('.ejecutivo-imagen').length) {
                            $(this).find('.jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-plus');
                        }
                    });
                    
                    console.log('✅ Íconos por nivel aplicados correctamente al árbol de ejecutivos');
                }, 300);
            });
        }
        
        function configurarEventos() {
            // Preview al seleccionar imagen
            $("#fot_eje").change(function() {
                mostrarPreview(this);
            });
            
            // Limpiar formulario cuando se cierre el modal
            $('#modalEjecutivo').on('hidden.bs.modal', function () {
                limpiarFormulario();
            });
            
            // Evento de selección de nodo
            $('#jstree').on('select_node.jstree', function (e, data) {
                nodoSeleccionado = data.node;
                mostrarInformacionNodo(data.node.data);
                mostrarRutaNodo(data.node);
                habilitarBotones();
            });
            
            // P32 - Evento de clic en icono/imagen de ejecutivo para mostrar card
            $('#jstree').on('click', '.jstree-icon, .ejecutivo-imagen', function(e) {
                e.preventDefault(); // Prevenir comportamiento por defecto del enlace
                e.stopPropagation();
                var nodeElement = $(this).closest('.jstree-node');
                var nodeId = nodeElement.attr('id');
                var ejecutivo = ejecutivos.find(ej => ej.id_eje == nodeId);
                
                if (ejecutivo) {
                    mostrarCardEjecutivo(ejecutivo, e);
                }
            });
            
            // Cerrar card al hacer clic fuera
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.ejecutivo-card, .jstree-icon, .ejecutivo-imagen').length) {
                    $('.ejecutivo-card').hide();
                }
            });
            
            // Evento de drag & drop completado
            $('#jstree').on('move_node.jstree', function (e, data) {
                var nodoMovido = data.node;
                var nuevoPadre = data.parent;
                var ejecutivoId = nodoMovido.id;
                var nuevoPadreId = nuevoPadre === '#' ? null : nuevoPadre;
                
                // Mostrar mensaje de estado
                mostrarMensajeDragDrop('Moviendo ejecutivo...', false);
                
                // Realizar actualización en el backend
                $.ajax({
                    url: 'server/controlador_ejecutivos.php',
                    type: 'POST',
                    data: {
                        action: 'mover_ejecutivo',
                        id_eje: ejecutivoId,
                        id_padre: nuevoPadreId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            mostrarMensajeDragDrop('✓ Ejecutivo movido correctamente', true);
                            // Actualizar datos locales
                            var ejecutivo = ejecutivos.find(e => e.id_eje == ejecutivoId);
                            if(ejecutivo) {
                                ejecutivo.id_padre = nuevoPadreId;
                            }
                            actualizarEstadisticas();
                            
                            // Reaplicar imágenes después del movimiento con más tiempo
                            setTimeout(function() {
                                aplicarImagenesEjecutivos();
                                console.log('Imágenes reaplicadas después del movimiento');
                            }, 300);
                        } else {
                            mostrarMensajeDragDrop('✗ Error: ' + response.message, false, true);
                            // Solo revertir si hay error real
                            cargarEjecutivos();
                        }
                        setTimeout(function() {
                            ocultarMensajeDragDrop();
                        }, 3000);
                    },
                    error: function() {
                        mostrarMensajeDragDrop('✗ Error de conexión', false, true);
                        cargarEjecutivos(); // Revertir cambios
                        setTimeout(function() {
                            ocultarMensajeDragDrop();
                        }, 3000);
                    }
                });
            });
            
            // Búsqueda en tiempo real
            $('#buscarTexto, #buscarTextoAcciones').on('keyup', function() {
                var searchString = $(this).val();
                $('#jstree').jstree('search', searchString);
            });
            
            // Filtro de mostrar ocultos
            $('#mostrarOcultos, #mostrarOcultosAcciones').on('change', function() {
                aplicarFiltros();
            });
        }
        
        // =====================================
        // FUNCIONES DE INTERFAZ
        // =====================================
        
        function mostrarInformacionNodo(ejecutivo) {
            $('#selectedId').text(ejecutivo.id_eje);
            $('#selectedNombre').text(ejecutivo.nom_eje);
            $('#selectedTelefono').text(ejecutivo.tel_eje);
            $('#selectedEstado').html('<span class="badge badge-' + (ejecutivo.eli_eje == 1 ? 'success' : 'secondary') + '">' + (ejecutivo.eli_eje == 1 ? 'Visible' : 'Oculto') + '</span>');
            
            // Buscar nombre del padre
            var padre = ejecutivos.find(e => e.id_eje == ejecutivo.id_padre);
            $('#selectedPadre').text(padre ? padre.nom_eje : 'Sin padre (Raíz)');
            
            $('#nodoSeleccionadoInfo').show();
        }
        
        function mostrarRutaNodo(nodo) {
            var ruta = [];
            var nodoActual = nodo;
            
            while(nodoActual) {
                ruta.unshift(nodoActual.text.replace(/<[^>]*>/g, '')); // Quitar HTML
                nodoActual = $('#jstree').jstree('get_node', nodoActual.parent);
                if(nodoActual.id === '#') break;
            }
            
            $('#rutaSeleccionada').text(ruta.join(' > '));
        }
        
        function habilitarBotones() {
            $('#btnEditar, #btnToggle, #btnMover').prop('disabled', false);
            
            // Cambiar texto del botón toggle según estado
            var ejecutivo = nodoSeleccionado.data;
            if(ejecutivo.eli_eje == 1) {
                $('#btnToggle').html('<i class="fas fa-eye-slash"></i> Ocultar').removeClass('btn-success').addClass('btn-warning');
            } else {
                $('#btnToggle').html('<i class="fas fa-eye"></i> Mostrar').removeClass('btn-warning').addClass('btn-success');
            }
        }
        
        function actualizarEstadisticas() {
            var total = ejecutivos.length;
            var activos = ejecutivos.filter(e => e.eli_eje == 1).length;
            var ocultos = total - activos;
            var raiz = ejecutivos.filter(e => !e.id_padre).length;
            
            $('#totalEjecutivos').text(total);
            $('#ejecutivosActivos').text(activos);
            $('#ejecutivosOcultos').text(ocultos);
            $('#nodosRaiz').text(raiz);
        }
        
        // =====================================
        // P32 - CARD DE RESUMEN DE EJECUTIVO
        // =====================================
        
        function mostrarCardEjecutivo(ejecutivo, event) {
            // Ocultar cualquier card existente
            $('.ejecutivo-card').remove();
            
            // Buscar información del padre
            var padre = ejecutivos.find(e => e.id_eje == ejecutivo.id_padre);
            var nombrePadre = padre ? padre.nom_eje : 'Sin responsable (Raíz)';
            
            // Calcular información del semáforo
            var semaforoInfo = '';
            var semaforoClass = '';
            
            switch(ejecutivo.semaforo_sesion) {
                case 'verde':
                    semaforoClass = 'semaforo-verde';
                    semaforoInfo = 'Sesión reciente (≤1 día)';
                    break;
                case 'amarillo':
                    semaforoClass = 'semaforo-amarillo';
                    semaforoInfo = 'Sesión reciente (2-3 días)';
                    break;
                case 'rojo':
                    semaforoClass = 'semaforo-rojo';
                    semaforoInfo = 'Sesión antigua (≥4 días)';
                    break;
                default:
                    semaforoClass = 'semaforo-sin-sesion';
                    semaforoInfo = 'Sin registro de sesión';
            }
            
            // Formatear última sesión
            var ultimaSesion = ejecutivo.ult_eje ? 
                new Date(ejecutivo.ult_eje).toLocaleDateString('es-ES') + ' ' + new Date(ejecutivo.ult_eje).toLocaleTimeString('es-ES') : 
                'Nunca';
            
            // Crear avatar del ejecutivo
            var avatarHtml = '';
            if (ejecutivo.fot_eje) {
                avatarHtml = `<div class="ejecutivo-card-avatar" style="background-image: url('uploads/${ejecutivo.fot_eje}')"></div>`;
            } else {
                avatarHtml = `<div class="ejecutivo-card-avatar default"><i class="fas fa-user"></i></div>`;
            }
            
            // Crear el card HTML
            var cardHtml = `
                <div class="ejecutivo-card">
                    <div class="ejecutivo-card-header">
                        ${avatarHtml}
                        <div class="ejecutivo-card-info">
                            <h6>${ejecutivo.nom_eje}</h6>
                            <small>${ejecutivo.tel_eje}</small>
                        </div>
                    </div>
                    <div class="ejecutivo-card-body">
                        <div class="ejecutivo-card-row">
                            <span class="ejecutivo-card-label">Estado:</span>
                            <span class="ejecutivo-card-value">
                                <span class="badge badge-${ejecutivo.eli_eje == 1 ? 'success' : 'danger'}">
                                    ${ejecutivo.eli_eje == 1 ? 'Activo' : 'Inactivo'}
                                </span>
                            </span>
                        </div>
                        <div class="ejecutivo-card-row">
                            <span class="ejecutivo-card-label">Sesión:</span>
                            <span class="ejecutivo-card-value">
                                <span class="semaforo-badge ${semaforoClass}">${semaforoInfo}</span>
                            </span>
                        </div>
                        <div class="ejecutivo-card-row">
                            <span class="ejecutivo-card-label">Última conexión:</span>
                            <span class="ejecutivo-card-value">${ultimaSesion}</span>
                        </div>
                        <div class="ejecutivo-card-row">
                            <span class="ejecutivo-card-label">Responsable:</span>
                            <span class="ejecutivo-card-value">${nombrePadre}</span>
                        </div>
                        <div class="ejecutivo-card-row">
                            <span class="ejecutivo-card-label">Citas del día:</span>
                            <span class="ejecutivo-card-value">
                                <span class="badge badge-primary">${ejecutivo.citas_propias || 0}</span>
                            </span>
                        </div>
                    </div>
                </div>
            `;
            
            // Agregar el card al DOM
            $('body').append(cardHtml);
            
            // Posicionar el card cerca del clic
            var card = $('.ejecutivo-card');
            var x = event.pageX + 10;
            var y = event.pageY - 10;
            
            // Ajustar posición si se sale de la pantalla
            if (x + card.outerWidth() > $(window).width()) {
                x = event.pageX - card.outerWidth() - 10;
            }
            if (y + card.outerHeight() > $(window).height()) {
                y = event.pageY - card.outerHeight() + 10;
            }
            
            card.css({
                'left': x + 'px',
                'top': y + 'px'
            }).fadeIn(200);
        }
        
        // =====================================
        // FUNCIONES DE CRUD
        // =====================================
        
        function mostrarModalCrear() {
            // Resetear SOLO el formulario y estado de edición
            $('#formEjecutivo')[0].reset();
            $('#preview').hide();
            
            modoEdicion = false;
            $('#modalTitulo').text('Crear Nuevo Ejecutivo');
            $('#ejecutivoId').val('');
            $('#ejecutivoNombre').val('');
            $('#ejecutivoTelefono').val('');
            $('#ejecutivoActivo').prop('checked', true);
            
            // Limpiar preview de imagen
            $('#fot_eje').val('');
            $('#preview').hide();
            
            // Restablecer texto del botón
            $('.btn-primary').text('Guardar');
            
            // Establecer padre seleccionado si hay uno
            if(nodoSeleccionado) {
                $('#ejecutivoPadreId').val(nodoSeleccionado.id);
                cargarSelectPadres(nodoSeleccionado.id);
            } else {
                $('#ejecutivoPadreId').val('');
                cargarSelectPadres();
            }
            
            $('#modalEjecutivo').modal('show');
        }
        
        function mostrarModalEditar() {
            if(!nodoSeleccionado) return;
            
            // Resetear SOLO el formulario, NO el nodoSeleccionado
            $('#formEjecutivo')[0].reset();
            $('#preview').hide();
            
            modoEdicion = true;
            var ejecutivo = nodoSeleccionado.data;
            
            $('#modalTitulo').text('Editar Ejecutivo');
            $('#ejecutivoId').val(ejecutivo.id_eje);
            $('#ejecutivoNombre').val(ejecutivo.nom_eje);
            $('#ejecutivoTelefono').val(ejecutivo.tel_eje);
            $('#ejecutivoActivo').prop('checked', ejecutivo.eli_eje == 1);
            
            // Restablecer texto del botón para edición
            $('.btn-primary').text('Actualizar');
            
            // Mostrar foto actual si existe
            if(ejecutivo.fot_eje) {
                $('#img-preview').attr('src', 'uploads/' + ejecutivo.fot_eje);
                $('#preview').show();
            } else {
                $('#preview').hide();
            }
            
            cargarSelectPadres(null, ejecutivo.id_padre);
            
            $('#modalEjecutivo').modal('show');
        }
        
        function cargarSelectPadres(padreSeleccionado = null, valorActual = null) {
            var select = $('#ejecutivoPadre');
            select.empty();
            select.append('<option value="">Sin padre (Nodo raíz)</option>');
            
            ejecutivos.filter(e => e.eli_eje == 1).forEach(function(ejecutivo) {
                // No incluir el nodo actual (para evitar referencias circulares)
                if(modoEdicion && ejecutivo.id_eje == $('#ejecutivoId').val()) return;
                
                var selected = (valorActual && valorActual == ejecutivo.id_eje) || (padreSeleccionado == ejecutivo.id_eje) ? 'selected' : '';
                select.append('<option value="' + ejecutivo.id_eje + '" ' + selected + '>' + ejecutivo.nom_eje + '</option>');
            });
        }
        
        function guardarEjecutivo() {
            // Validar campos de texto
            if (!$("#ejecutivoNombre").val().trim()) {
                alert('El nombre es requerido');
                return;
            }
            
            if (!$("#ejecutivoTelefono").val().trim()) {
                alert('El teléfono es requerido');
                return;
            }
            
            // Validar imagen si existe
            if ($("#fot_eje")[0].files[0]) {
                if (!validarImagen()) {
                    return;
                }
            }
            
            // Si todo está bien, enviar
            enviarFormulario();
        }
        
        // Función para validar imagen
        function validarImagen() {
            var archivo = $("#fot_eje")[0].files[0];
            var nombre = archivo.name;
            var tamannio = archivo.size;
            var extension = nombre.split('.').pop().toLowerCase();
            
            // Validar extensión
            if (!['jpg', 'jpeg', 'png'].includes(extension)) {
                alert('Solo se permiten archivos JPG y PNG');
                return false;
            }
            
            // Validar tamaño (50MB)
            if (tamannio > 52428800) {
                alert('La imagen no debe exceder 50MB');
                return false;
            }
            
            return true;
        }
        
        // Enviar formulario completo
        function enviarFormulario() {
            var formData = new FormData($('#formEjecutivo')[0]);
            formData.append('action', modoEdicion ? 'actualizar_ejecutivo' : 'crear_ejecutivo');
            formData.append('nom_eje', $('#ejecutivoNombre').val().trim());
            formData.append('tel_eje', $('#ejecutivoTelefono').val().trim());
            
            var idPadre = $('#ejecutivoPadre').val();
            if (idPadre && idPadre !== '' && idPadre !== 'null') {
                formData.append('id_padre', idPadre);
            }
            // Si no tiene padre, simplemente no enviamos el campo id_padre
            
            formData.append('eli_eje', $('#ejecutivoActivo').is(':checked') ? 1 : 0);
            
            if(modoEdicion) {
                formData.append('id_eje', $('#ejecutivoId').val());
            }
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('.btn-primary').prop('disabled', true).text('Guardando...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalEjecutivo').modal('hide');
                        alert(response.message);
                        
                        // Recargar datos manteniendo la selección actual
                        cargarEjecutivos();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error de conexión');
                },
                complete: function() {
                    // Restaurar el texto correcto del botón según el modo
                    var textoBoton = modoEdicion ? 'Actualizar' : 'Guardar';
                    $('.btn-primary').prop('disabled', false).text(textoBoton);
                }
            });
        }
        
        // Mostrar preview de imagen
        function mostrarPreview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img-preview').attr('src', e.target.result);
                    $('#preview').show();
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#preview').hide();
            }
        }
        
        // Limpiar formulario
        function limpiarFormulario() {
            $('#formEjecutivo')[0].reset();
            $('#preview').hide();
            
            // NO resetear nodoSeleccionado aquí - mantener la selección
            // Solo resetear el estado del modal
            modoEdicion = false;
            
            // Resetear el botón de guardar a su estado original
            $('.btn-primary').prop('disabled', false).text('Guardar');
            
            // Resetear título del modal
            $('#modalTitulo').text('Crear Ejecutivo');
        }
        
        // Función específica para recargar el árbol
        function recargarArbol() {
            cargarEjecutivos(true); // Limpiar selección al recargar manualmente
        }
        
        // Función para recargar preservando la selección actual
        function recargarArbolConSeleccion() {
            var idSeleccionado = nodoSeleccionado ? nodoSeleccionado.id : null;
            
            cargarEjecutivos();
            
            // Restaurar selección después de recargar
            if (idSeleccionado) {
                setTimeout(function() {
                    var nodo = $('#jstree').jstree('get_node', idSeleccionado);
                    if (nodo) {
                        $('#jstree').jstree('select_node', nodo);
                    }
                }, 500);
            }
        }
        
        function toggleEstado() {
            if(!nodoSeleccionado) return;
            
            var ejecutivo = nodoSeleccionado.data;
            var nuevoEstado = ejecutivo.eli_eje == 1 ? 0 : 1;
            var accion = nuevoEstado == 1 ? 'mostrar' : 'ocultar';
            
            if(confirm('¿Está seguro de ' + accion + ' a ' + ejecutivo.nom_eje + '?')) {
                $.ajax({
                    url: 'server/controlador_ejecutivos.php',
                    type: 'POST',
                    data: {
                        action: 'toggle_estado_ejecutivo',
                        id_eje: ejecutivo.id_eje,
                        eli_eje: nuevoEstado
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            cargarEjecutivos();
                            alert('Estado actualizado correctamente');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error de conexión al servidor');
                    }
                });
            }
        }
        
        function moverNodo() {
            if(!nodoSeleccionado) return;
            
            var ejecutivo = nodoSeleccionado.data;
            $('#nombreMover').text(ejecutivo.nom_eje);
            
            // Cargar select de nuevos padres
            var select = $('#nuevoPadre');
            select.empty();
            select.append('<option value="">Sin padre (Nodo raíz)</option>');
            
            ejecutivos.filter(e => e.eli_eje == 1 && e.id_eje != ejecutivo.id_eje).forEach(function(eje) {
                // Evitar crear referencias circulares (no puede ser hijo de sí mismo o de sus descendientes)
                var selected = eje.id_eje == ejecutivo.id_padre ? 'selected' : '';
                select.append('<option value="' + eje.id_eje + '" ' + selected + '>' + eje.nom_eje + '</option>');
            });
            
            $('#modalMover').modal('show');
        }
        
        function confirmarMover() {
            var ejecutivo = nodoSeleccionado.data;
            var nuevoPadreId = $('#nuevoPadre').val() || null;
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: {
                    action: 'mover_ejecutivo',
                    id_eje: ejecutivo.id_eje,
                    id_padre: nuevoPadreId
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#modalMover').modal('hide');
                        cargarEjecutivos();
                        alert('Ejecutivo movido correctamente');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error de conexión al servidor');
                }
            });
        }
        
        // =====================================
        // FUNCIONES DE NAVEGACIÓN A CITAS
        // =====================================
        
        function verDetallesCitas(idEjecutivo, tipo) {
            // Navegar al apartado de citas con filtro por ejecutivo
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            var url = 'index.php?ejecutivo=' + idEjecutivo;
            
            if (fechaInicio) {
                url += '&fecha_inicio=' + fechaInicio;
            }
            if (fechaFin) {
                url += '&fecha_fin=' + fechaFin;
            }
            
            // Agregar parámetro para indicar el tipo de conteo
            url += '&tipo_conteo=' + tipo;
            url += '&origen=ejecutivos';
            
            console.log('Navegando a:', url);
            window.location.href = url;
        }
        
        
        // =====================================
        // FUNCIONES DE FILTRO DE FECHAS
        // =====================================
        
        function aplicarFiltroFechas() {
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            // Validar fechas
            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor que la fecha de fin');
                return;
            }
            
            console.log('Aplicando filtro de fechas:', fechaInicio, 'a', fechaFin);
            
            // Recargar ejecutivos con filtro de fechas
            cargarEjecutivos();
        }
        
        function limpiarFiltroFechas() {
            $('#fechaInicio').val('');
            $('#fechaFin').val('');
            console.log('Limpiando filtro de fechas');
            
            // Recargar ejecutivos sin filtro
            cargarEjecutivos();
        }
        
        // =====================================
        // FUNCIONES DE FILTROS
        // =====================================
        
        function aplicarFiltros() {
            aplicarFiltroFechas();
        }
        
        function expandirTodo() {
            $('#jstree').jstree('open_all');
        }
        
        function colapsarTodo() {
            $('#jstree').jstree('close_all');
        }
        
        function buscarEnArbol() {
            var texto = $('#buscarTexto').val();
            $('#jstree').jstree('search', texto);
        }
        
        // Agregar event listeners para búsqueda en tiempo real
        $('#buscarTexto').on('keyup', function() {
            buscarEnArbol();
        });
        
        // Función para verificar si un nodo es descendiente de otro
        function esDescendiente(nodoId, posibleAncestroId) {
            if (nodoId === posibleAncestroId) {
                return true; // Un nodo es descendiente de sí mismo
            }
            
            var ejecutivo = ejecutivos.find(e => e.id_eje == nodoId);
            while (ejecutivo && ejecutivo.id_padre) {
                if (ejecutivo.id_padre == posibleAncestroId) {
                    return true;
                }
                ejecutivo = ejecutivos.find(e => e.id_eje == ejecutivo.id_padre);
            }
            return false;
        }
        
        // Funciones para mostrar mensajes de drag & drop
        function mostrarMensajeDragDrop(mensaje, exito, error) {
            var $status = $('.drag-status');
            if ($status.length === 0) {
                $status = $('<div class="drag-status"></div>');
                $('body').append($status);
            }
            
            $status.removeClass('success error').text(mensaje);
            
            if (exito) {
                $status.addClass('success');
            } else if (error) {
                $status.addClass('error');
            }
            
            $status.fadeIn();
        }
        
        function ocultarMensajeDragDrop() {
            $('.drag-status').fadeOut();
        }
        
        // =====================================
        // P34 - FUNCIONES DE NOTIFICACIÓN SONORA
        // =====================================
        
        function inicializarAudioNotificacion() {
            try {
                audioNotificacion = new Audio('assets/notification.mp3');
                audioNotificacion.preload = 'auto';
                audioNotificacion.volume = 0.5; // Volumen al 50%
                
                // Test de carga del audio
                audioNotificacion.addEventListener('canplaythrough', function() {
                    console.log('🔊 Audio de notificación cargado correctamente');
                });
                
                audioNotificacion.addEventListener('error', function(e) {
                    console.error('❌ Error al cargar audio de notificación:', e);
                });
                
            } catch (error) {
                console.error('❌ Error al inicializar audio de notificación:', error);
            }
        }
        
        function reproducirNotificacionSonora() {
            try {
                if (audioNotificacion) {
                    // Reiniciar el audio al inicio para permitir múltiples reproducciones rápidas
                    audioNotificacion.currentTime = 0;
                    
                    var playPromise = audioNotificacion.play();
                    
                    if (playPromise !== undefined) {
                        playPromise.then(function() {
                            console.log('🔊 Notificación sonora reproducida');
                        }).catch(function(error) {
                            // El navegador puede bloquear autoplay, es normal
                            console.warn('⚠️ Autoplay bloqueado por el navegador:', error);
                        });
                    }
                } else {
                    console.warn('⚠️ Audio de notificación no inicializado');
                }
            } catch (error) {
                console.error('❌ Error al reproducir notificación sonora:', error);
            }
        }
        
        // =====================================
        // FUNCIONES DE WEBSOCKET
        // =====================================
        
        function inicializarWebSocket() {
            if (socket) {
                socket.close();
            }
            
            console.log('Conectando a WebSocket...');
            
            socket = new WebSocket(websocketUrl);
            
            socket.onopen = function() {
                console.log('✅ WebSocket conectado');
                reconectarIntento = 0;
            };
            
            socket.onmessage = function(event) {
                var mensaje = JSON.parse(event.data);
                console.log('📨 Mensaje recibido: ' + JSON.stringify(mensaje));
                
                // Ignorar mis propios mensajes
                if (mensaje.id_ejecutivo === miIdEjecutivo) {
                    console.log('⏭️ Ignorando mi propio mensaje');
                    return;
                }
                
                // P34 - Reproducir notificación sonora para cambios en EJECUTIVOS o CITAS
                var tiposConSonido = [
                    'ejecutivo_actualizado', 'ejecutivo_creado', 'ejecutivo_movido', 
                    'ejecutivo_estado_cambiado', 'ejecutivo_cambio_plantel',
                    'actualizacion_citas_plantel', 'cita_cambio_plantel', 
                    'cita_disociacion', 'cita_reasociacion', 'cita_actualizada', 
                    'cita_creada', 'cita_eliminada'
                ];
                
                if (tiposConSonido.includes(mensaje.tipo)) {
                    reproducirNotificacionSonora();
                }
                
                // Procesar diferentes tipos de mensajes
                if (mensaje.tipo === 'ejecutivo_actualizado') {
                    procesarActualizacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_creado') {
                    procesarCreacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_movido') {
                    procesarMovimientoWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_estado_cambiado') {
                    procesarCambioEstadoWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_cambio_plantel') {
                    procesarCambioPlantelWebSocket(mensaje);
                }
            };
            
            socket.onclose = function() {
                console.log('🔴 WebSocket desconectado');
                
                // Intentar reconectar
                if (reconectarIntento < maxReconectarIntentos) {
                    reconectarIntento++;
                    console.log('Intentando reconectar... (' + reconectarIntento + '/' + maxReconectarIntentos + ')');
                    setTimeout(inicializarWebSocket, 2000 * reconectarIntento);
                } else {
                    console.log('❌ Máximo número de intentos de reconexión alcanzado');
                }
            };
            
            socket.onerror = function(error) {
                console.log('❌ Error WebSocket: ' + error);
            };
        }
        
        function procesarActualizacionWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            console.log('🔄 Procesando actualización de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo y actualizar sus datos
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                if (mensaje.campo && mensaje.hasOwnProperty('valor')) {
                    ejecutivo[mensaje.campo] = mensaje.valor;
                    console.log('Campo ' + mensaje.campo + ' actualizado a: ' + mensaje.valor);
                }
                
                // Recargar el árbol para mostrar cambios
                cargarEjecutivos();
            }
        }
        
        function procesarCreacionWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            console.log('➕ Procesando creación de ejecutivo ID: ' + mensaje.id_eje);
            
            // Recargar ejecutivos para mostrar el nuevo
            cargarEjecutivos();
        }
        
        function procesarMovimientoWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            console.log('🔄 Procesando movimiento de ejecutivo ID: ' + mensaje.id_eje);
            
            // Recargar ejecutivos para mostrar los cambios
            cargarEjecutivos();
        }
        
        function procesarCambioEstadoWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            console.log('🔄 Procesando cambio de estado de ejecutivo ID: ' + mensaje.id_eje);
            
            // Recargar ejecutivos para mostrar los cambios
            cargarEjecutivos();
        }
        
        function procesarCambioPlantelWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            console.log('🔄 Procesando cambio de plantel de ejecutivo ID: ' + mensaje.id_eje);
            
            // Recargar ejecutivos para mostrar los cambios
            cargarEjecutivos();
        }
        
    </script>
</body>
</html>
