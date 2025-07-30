<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 25 - Árbol Recursivo por Plantel</title>
    
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
        /* Estilos para planteles */
        .plantel-container {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            min-height: 400px;
            position: relative;
        }
        
        .plantel-header {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        
        .plantel-tree {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            min-height: 320px;
        }
        
        /* Estilos para drag & drop entre planteles */
        .plantel-container.drop-zone {
            border: 3px dashed #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .plantel-container.drop-zone .plantel-header {
            background-color: #28a745;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
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
        
        /* Mensaje de estado para drag & drop */
        .drag-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #6c757d;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            z-index: 9999;
            display: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-size: 0.9em;
            opacity: 0.9;
            transition: all 0.2s ease;
        }
        
        .drag-status.error {
            background: #dc3545;
            opacity: 1;
        }
        
        .drag-status.success {
            background: #28a745;
            opacity: 0.95;
        }
        
        /* Estilos para mejorar jsTree con sangría tipo árbol de directorios */
        .jstree-default .jstree-node {
            min-height: 36px;
            line-height: 36px;
            margin: 2px 0;
            min-width: 24px;
        }
        
        .jstree-default .jstree-anchor {
            line-height: 36px;
            height: 36px;
            padding: 0 10px 0 8px;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .jstree-default .jstree-icon {
            width: 18px;
            height: 18px;
            line-height: 18px;
            margin-top: 9px;
            margin-right: 8px;
        }
        
        /* Estilos para imágenes de ejecutivos como iconos */
        .jstree-default .jstree-anchor .jstree-icon {
            background-size: cover;
            background-position: center;
            border-radius: 50%;
            border: 1px solid #ddd;
        }
        
        /* Cuando el ícono es una imagen, ajustar el tamaño */
        .jstree-anchor .jstree-icon[style*="background-image"] {
            width: 24px !important;
            height: 24px !important;
            margin-top: 6px !important;
            border-radius: 50%;
            border: 2px solid #007bff;
            background-size: cover !important;
            background-position: center !important;
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
        
        /* Sangría y líneas de conexión jerárquicas mejoradas */
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
        
        /* Hover effects para nodos */
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
        
        /* Mejorar los iconos por tipo de nodo */
        .jstree-default .jstree-icon.fas {
            color: #007bff;
        }
        
        .jstree-default .jstree-icon.text-success {
            color: #28a745 !important;
        }
        
        .jstree-default .jstree-icon.text-danger {
            color: #dc3545 !important;
        }
        
        /* Efectos visuales para nodos inactivos */
        .jstree-default .jstree-node[data-type="inactive"] > .jstree-anchor {
            opacity: 0.7;
            background-color: #f8f9fa;
        }
        
        /* =============================================
           ESTILOS PARA SEMÁFORO DE SESIÓN P15
           ============================================= */
        
        /* Indicador de semáforo al lado del nombre */
        .semaforo-sesion {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
            margin-right: 4px;
            position: relative;
            top: 1px;
            border: 1px solid rgba(0,0,0,0.2);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .semaforo-sesion.verde {
            background-color: #28a745;
            box-shadow: 0 0 6px rgba(40, 167, 69, 0.5);
        }
        
        .semaforo-sesion.amarillo {
            background-color: #ffc107;
            box-shadow: 0 0 6px rgba(255, 193, 7, 0.5);
        }
        
        .semaforo-sesion.rojo {
            background-color: #dc3545;
            box-shadow: 0 0 6px rgba(220, 53, 69, 0.5);
        }
        
        .semaforo-sesion.sin_sesion {
            background-color: #6c757d;
            box-shadow: 0 0 6px rgba(108, 117, 125, 0.5);
        }
        
        /* Tooltip para mostrar información del semáforo */
        .semaforo-tooltip {
            position: absolute;
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1000;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        
        .semaforo-tooltip.show {
            opacity: 1;
        }
        
        .semaforo-tooltip::before {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }
        
        /* Mejorar el aspecto de los nodos con semáforo */
        .jstree-default .jstree-anchor {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .jstree-anchor-content {
            flex-grow: 1;
            display: flex;
            align-items: center;
        }
        
        .jstree-anchor-indicators {
            display: flex;
            align-items: center;
            margin-left: 8px;
        }
        
        /* Estilos para el nombre del ejecutivo */
        .ejecutivo-nombre {
            flex-grow: 1;
        }
        
        /* Animación para el semáforo */
        .semaforo-sesion {
            animation: semaforoPulse 2s infinite;
        }
        
        @keyframes semaforoPulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        /* Desactivar animación en semáforos verdes para no distraer */
        .semaforo-sesion.verde {
            animation: none;
        }
        
        /* Estilos para estadísticas del semáforo */
        .stats-semaforo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .stat-semaforo {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .stat-semaforo .semaforo-sesion {
            position: static;
            top: auto;
            animation: none;
        }
        
        /* Leyenda del semáforo */
        .semaforo-leyenda {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .semaforo-leyenda h6 {
            margin-bottom: 12px;
            color: #495057;
            font-weight: bold;
        }
        
        .leyenda-items {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #495057;
        }
        
        .leyenda-item .semaforo-sesion {
            position: static;
            top: auto;
            animation: none;
        }
        
        /* Panel de control */
        .control-panel {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .info-panel {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .badge {
            font-size: 0.8em;
            padding: 0.4em 0.6em;
        }
        
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
        
        /* Estilos para estadísticas */
        .stats-container {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .stat-item {
            text-align: center;
            padding: 10px;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 0.9em;
            color: #6c757d;
        }
        
        /* Mejorar las zonas de drop para incluir elementos internos */
        .plantel-container.drop-zone * {
            pointer-events: none; /* Evitar que los elementos internos interfieran con el drop */
        }
        
        .plantel-container.drop-zone .plantel-tree {
            pointer-events: all; /* Permitir drops en el área del árbol */
        }
        
        .plantel-container.drop-zone .jstree-node {
            pointer-events: all; /* Permitir drops en los nodos */
        }
        
        .plantel-container.drop-zone .jstree-anchor {
            pointer-events: all; /* Permitir drops en los enlaces de nodos */
        }
        
        /* Estilo visual cuando el drop es válido */
        .plantel-container.drop-zone .plantel-tree {
            background-color: rgba(40, 167, 69, 0.1) !important;
            border: 2px dashed #28a745 !important;
            border-radius: 8px !important;
        }
        
        /* =====================================
           ESTILOS PARA BADGES DE CONTEO DE CITAS - P18
           ===================================== */
        
        /* Badge para citas propias (blanco) */
        .badge-citas-propias {
            background-color: #ffffff;
            color: #333333;
            border: 1px solid #dee2e6;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .badge-citas-propias:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
            transform: scale(1.05);
            color: #007bff;
        }
        
        /* Badge para citas recursivas (morado) */
        .badge-citas-recursivas {
            background-color: #6f42c1;
            color: #ffffff;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .badge-citas-recursivas:hover {
            background-color: #563d7c;
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(111, 66, 193, 0.3);
        }
        
        /* Badge para citas de plantel (azul) */
        .badge-citas-plantel {
            background-color: #6f42c1;
            color: #ffffff;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .badge-citas-plantel:hover {
            background-color: #563d7c;
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(111, 66, 193, 0.3);
        }
        
        /* Contenedor para los badges de citas */
        .badges-citas-container {
            display: inline-block;
            margin-left: 8px;
        }
        
        /* Estilos para WebSocket */
        .websocket-changed {
            background-color: #ffeb3b !important;
            transition: background-color 0.3s ease;
        }
        
        /* Específico para ejecutivos movidos - MÁS RÁPIDO */
        .ejecutivo-movido {
            background-color: #ffeb3b !important;
            animation: pulsoAmarilloRapido 1s ease-in-out;
            border-radius: 6px !important;
            box-shadow: 0 0 10px rgba(255, 235, 59, 0.7) !important;
        }
        
        @keyframes pulsoAmarilloRapido {
            0% { 
                background-color: #ffeb3b !important; 
                transform: scale(1);
                box-shadow: 0 0 10px rgba(255, 235, 59, 0.7);
            }
            33% { 
                background-color: #fff176 !important; 
                transform: scale(1.05);
                box-shadow: 0 0 18px rgba(255, 235, 59, 0.9);
            }
            66% { 
                background-color: #ffeb3b !important; 
                transform: scale(1.02);
                box-shadow: 0 0 12px rgba(255, 235, 59, 0.8);
            }
            100% { 
                background-color: #fff59d !important; 
                transform: scale(1);
                box-shadow: 0 0 8px rgba(255, 235, 59, 0.6);
            }
            100% { 
                background-color: transparent !important; 
                transform: scale(1);
                box-shadow: none;
            }
        }
        
        /* Específico para cambios de plantel - MÁS PROMINENTE Y RÁPIDO */
        .ejecutivo-cambio-plantel {
            background: linear-gradient(45deg, #ff9800, #ffc107) !important;
            animation: pulsoCambioPlantelRapido 1.5s ease-in-out;
            border-radius: 6px !important;
            box-shadow: 0 0 20px rgba(255, 152, 0, 0.8) !important;
            border: 2px solid #ff5722 !important;
            color: #fff !important;
            font-weight: bold !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.7) !important;
        }
        
        @keyframes pulsoCambioPlantelRapido {
            0% { 
                background: linear-gradient(45deg, #ff9800, #ffc107) !important; 
                transform: scale(1);
                box-shadow: 0 0 20px rgba(255, 152, 0, 0.8);
                border-color: #ff5722;
            }
            25% { 
                background: linear-gradient(45deg, #ffc107, #ffeb3b) !important; 
                transform: scale(1.08);
                box-shadow: 0 0 35px rgba(255, 193, 7, 0.9);
                border-color: #f44336;
            }
            50% { 
                background: linear-gradient(45deg, #ff9800, #ffc107) !important; 
                transform: scale(1.05);
                box-shadow: 0 0 25px rgba(255, 152, 0, 0.8);
                border-color: #ff5722;
            }
            75% { 
                background: linear-gradient(45deg, #ffb74d, #ffd54f) !important; 
                transform: scale(1.03);
                box-shadow: 0 0 20px rgba(255, 183, 77, 0.7);
                border-color: #e65100;
            }
            100% { 
                background: linear-gradient(45deg, #ff9800, #ffc107) !important; 
                transform: scale(1);
                box-shadow: 0 0 10px rgba(255, 152, 0, 0.4);
                border-color: #ff5722;
            }
        }
        
        .websocket-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            animation: fadeInOut 3s ease-in-out;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-10px); }
            20% { opacity: 1; transform: translateY(0); }
            80% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
        
        .websocket-status {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .websocket-status.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .websocket-status.danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .websocket-status.warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        
        .websocket-log {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 8px;
            height: 60px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.8em;
            margin-bottom: 10px;
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
        <h1 class="text-center mb-4">Práctica 11 - Árbol Recursivo por Plantel</h1>
        
        <!-- Panel de control -->
        <div class="control-panel">
            <div class="row">
                <div class="col-md-5">
                    <h4>Gestión de Ejecutivos por Plantel</h4>
                    <p class="text-muted">Arrastra y suelta ejecutivos entre planteles para reorganizar la estructura</p>
                </div>
                <div class="col-md-7">
                    <!-- WebSocket Status y Controls -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div id="websocket-status" class="websocket-status warning">
                                <strong>WebSocket:</strong> <span id="websocket-status-text">Conectando...</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="websocket-log" id="websocket-log">
                                <div id="websocket-log-text">Logs de WebSocket aparecerán aquí...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Control ID - P20 -->
                    <div class="form-row mb-3">
                        <div class="col-md-2">
                            <label for="mi-id-ejecutivo" class="small"><strong>Mi ID:</strong></label>
                            <input type="number" id="mi-id-ejecutivo" class="form-control form-control-sm" value="1" min="1" max="999">
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary mr-2" onclick="mostrarModalCrear()">
                            <i class="fas fa-plus"></i> Nuevo Ejecutivo
                        </button>
                        <button class="btn btn-secondary" onclick="recargarTodos()">
                            <i class="fas fa-sync"></i> Recargar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leyenda del Semáforo de Sesión P15 -->
        <div class="semaforo-leyenda">
            <h6><i class="fas fa-traffic-light"></i> Semáforo de Sesión - Práctica 15</h6>
            <div class="leyenda-items">
                <div class="leyenda-item">
                    <span class="semaforo-sesion verde"></span>
                    <span>Verde: ≤ 1 día</span>
                </div>
                <div class="leyenda-item">
                    <span class="semaforo-sesion amarillo"></span>
                    <span>Amarillo: 2-3 días</span>
                </div>
                <div class="leyenda-item">
                    <span class="semaforo-sesion rojo"></span>
                    <span>Rojo: ≥ 4 días</span>
                </div>
                <div class="leyenda-item">
                    <span class="semaforo-sesion sin_sesion"></span>
                    <span>Sin sesión</span>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="row" id="estadisticas">
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="total-ejecutivos">0</div>
                    <div class="stat-label">Total Ejecutivos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="ejecutivos-activos">0</div>
                    <div class="stat-label">Activos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="ejecutivos-ocultos">0</div>
                    <div class="stat-label">Ocultos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="total-planteles">3</div>
                    <div class="stat-label">Planteles</div>
                </div>
            </div>
        </div>
        
        <!-- Planteles -->
        <div class="row" id="planteles-container">
            <!-- Los planteles se cargarán dinámicamente aquí -->
        </div>
        
        <!-- Panel de información -->
        <div class="info-panel" id="info-panel" style="display: none;">
            <h5>Información del Ejecutivo Seleccionado</h5>
            <div id="ejecutivo-info"></div>
        </div>
    </div>
    
    <!-- Mensaje de drag & drop -->
    <div class="drag-status" id="drag-status"></div>
    
    <!-- Modal para Crear/Editar -->
    <div class="modal fade" id="modalEjecutivo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Crear Ejecutivo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEjecutivo">
                        <input type="hidden" id="ejecutivo_id" name="id_eje">
                        
                        <div class="form-group">
                            <label for="ejecutivo_nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ejecutivo_nombre" name="nom_eje" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivo_telefono">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ejecutivo_telefono" name="tel_eje" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivo_plantel">Plantel <span class="text-danger">*</span></label>
                            <select class="form-control" id="ejecutivo_plantel" name="id_pla" required>
                                <option value="">Seleccione un plantel</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivo_padre">Jefe Inmediato</label>
                            <select class="form-control" id="ejecutivo_padre" name="id_padre">
                                <option value="">Sin jefe (Raíz)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ejecutivo_activo" name="eli_eje" value="1" checked>
                                <label class="form-check-label" for="ejecutivo_activo">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEjecutivo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Variables globales
        var planteles = [];
        var ejecutivos = [];
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
            
            // Configurar evento para cambio de ID ejecutivo
            $('#mi-id-ejecutivo').on('change', function() {
                miIdEjecutivo = parseInt($(this).val()) || 1;
                log('ID ejecutivo cambiado a: ' + miIdEjecutivo);
            });
            
            // P32 - Cerrar card al hacer clic fuera
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.ejecutivo-card, .jstree-icon, .ejecutivo-imagen').length) {
                    $('.ejecutivo-card').hide();
                }
            });
            
            // Inicializar WebSocket
            inicializarWebSocket();
            
            aplicarFiltrosDesdeURL();
            // Cargar planteles primero, luego ejecutivos
            cargarPlanteles().then(function() {
                return cargarEjecutivos();
            }).then(function() {
                // Cargar conteos de citas por plantel después de cargar todo
                cargarCitasPorPlantel();
            }).catch(function(error) {
                console.error('Error en la inicialización:', error);
            });
        });
        
        function aplicarFiltrosDesdeURL() {
            // Obtener parámetros de la URL
            var params = obtenerParametrosURL();
            
            // Aplicar otros filtros si existen (reservado para futuras funcionalidades)
            console.log('Parámetros URL:', params);
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
        // P34 - FUNCIONES DE NOTIFICACIÓN SONORA
        // =====================================
        
        function inicializarAudioNotificacion() {
            try {
                audioNotificacion = new Audio('assets/notification.mp3');
                audioNotificacion.preload = 'auto';
                audioNotificacion.volume = 0.5; // Volumen al 50%
                
                // Test de carga del audio
                audioNotificacion.addEventListener('canplaythrough', function() {
                    log('🔊 Audio de notificación cargado correctamente');
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
                            log('🔊 Notificación sonora reproducida');
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
            
            log('Conectando a WebSocket...');
            actualizarEstadoWebSocket('warning', 'Conectando...');
            
            socket = new WebSocket(websocketUrl);
            
            socket.onopen = function() {
                log('✅ WebSocket conectado');
                actualizarEstadoWebSocket('success', 'Conectado');
                reconectarIntento = 0;
                
                // Mostrar badge de conexión exitosa
                mostrarBadgeWebSocket('success', 'WebSocket conectado');
            };
            
            socket.onmessage = function(event) {
                var mensaje = JSON.parse(event.data);
                log('📨 Mensaje recibido: ' + JSON.stringify(mensaje));
                
                // Ignorar mis propios mensajes
                if (mensaje.id_ejecutivo === miIdEjecutivo) {
                    log('⏭️ Ignorando mi propio mensaje');
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
                } else if (mensaje.tipo === 'actualizacion_citas_plantel') {
                    procesarActualizacionCitasPlantelWebSocket(mensaje);
                } else if (mensaje.tipo === 'cita_cambio_plantel') {
                    procesarCitaCambioPlantelWebSocket(mensaje);
                } else if (mensaje.tipo === 'cita_disociacion') {
                    procesarCitaDisociacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'cita_reasociacion') {
                    procesarCitaReasociacionWebSocket(mensaje);
                }
            };
            
            socket.onclose = function() {
                log('🔴 WebSocket desconectado');
                actualizarEstadoWebSocket('danger', 'Desconectado');
                
                // Intentar reconectar
                if (reconectarIntento < maxReconectarIntentos) {
                    reconectarIntento++;
                    log('Intentando reconectar... (' + reconectarIntento + '/' + maxReconectarIntentos + ')');
                    setTimeout(inicializarWebSocket, 2000 * reconectarIntento);
                } else {
                    log('❌ Máximo número de intentos de reconexión alcanzado');
                    mostrarBadgeWebSocket('danger', 'WebSocket desconectado');
                }
            };
            
            socket.onerror = function(error) {
                log('❌ Error WebSocket: ' + error);
                actualizarEstadoWebSocket('danger', 'Error de conexión');
            };
        }
        
        function enviarMensajeWebSocket(tipo, datos) {
            if (socket && socket.readyState === WebSocket.OPEN) {
                var mensaje = {
                    tipo: tipo,
                    id_ejecutivo: miIdEjecutivo,
                    timestamp: new Date().toISOString(),
                    ...datos
                };
                
                socket.send(JSON.stringify(mensaje));
                log('📤 Mensaje enviado: ' + JSON.stringify(mensaje));
            } else {
                log('⚠️ WebSocket no está conectado, no se pudo enviar mensaje');
            }
        }
        
        function procesarActualizacionWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('🔄 Procesando actualización de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo en la estructura y actualizar
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                if (mensaje.campo && mensaje.hasOwnProperty('valor')) {
                    ejecutivo[mensaje.campo] = mensaje.valor;
                    log('Campo ' + mensaje.campo + ' actualizado a: ' + mensaje.valor);
                }
                
                // Regenerar los árboles para mostrar cambios
                generarArbolesPorPlantel();
                
                // Reaplicar imágenes después de regenerar por WebSocket
                setTimeout(function() {
                    aplicarImagenesEjecutivos();
                }, 100);
                
                // Aplicar feedback visual
                aplicarFeedbackVisualEjecutivo(mensaje.id_eje, mensaje.campo);
                
                // Mostrar badge de actualización
                mostrarBadgeWebSocket('info', 'Ejecutivo actualizado: ' + (mensaje.campo || 'datos'));
            }
        }
        
        function procesarCreacionWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('➕ Procesando creación de ejecutivo ID: ' + mensaje.id_eje);
            
            // Recargar ejecutivos para mostrar el nuevo
            cargarEjecutivos().then(function() {
                cargarCitasPorPlantel();
                mostrarBadgeWebSocket('success', 'Nuevo ejecutivo creado');
            });
        }
        
        function procesarMovimientoWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('🔄 Procesando movimiento de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo y actualizar su posición
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                if (mensaje.hasOwnProperty('nuevo_padre')) {
                    ejecutivo.id_padre = mensaje.nuevo_padre;
                }
                if (mensaje.hasOwnProperty('nuevo_plantel')) {
                    ejecutivo.id_pla = mensaje.nuevo_plantel;
                }
                
                // Regenerar los árboles para mostrar cambios INMEDIATAMENTE
                generarArbolesPorPlantel();
                
                // Reaplicar imágenes después de regenerar por movimiento WebSocket
                setTimeout(function() {
                    aplicarImagenesEjecutivos();
                }, 100);
                
                // Aplicar feedback visual específico para movimiento - INMEDIATO SIN DELAY
                aplicarFeedbackVisualEjecutivo(mensaje.id_eje, 'movimiento');
                
                // Mostrar badge de notificación
                mostrarBadgeWebSocket('success', 'Ejecutivo movido por otro usuario');
            }
        }
        
        function procesarCambioEstadoWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('🔄 Procesando cambio de estado de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo y actualizar su estado
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                ejecutivo.eli_eje = mensaje.nuevo_estado;
                
                // Regenerar los árboles para mostrar cambios
                generarArbolesPorPlantel();
                
                // Reaplicar imágenes después de regenerar por cambio de estado WebSocket
                setTimeout(function() {
                    aplicarImagenesEjecutivos();
                }, 100);
                
                // Aplicar feedback visual
                aplicarFeedbackVisualEjecutivo(mensaje.id_eje, 'estado');
                
                // Mostrar badge de cambio de estado
                var estadoTexto = mensaje.nuevo_estado == 1 ? 'activado' : 'desactivado';
                mostrarBadgeWebSocket('info', 'Ejecutivo ' + estadoTexto);
            }
        }
        
        function aplicarFeedbackVisualEjecutivo(id_eje, campo) {
            console.log('Aplicando feedback visual INMEDIATO para ejecutivo:', id_eje, 'campo:', campo);
            
            // Buscar el nodo del ejecutivo en todos los árboles
            $('.plantel-tree').each(function() {
                var treeId = $(this).attr('id');
                var tree = $('#' + treeId);
                var node = tree.jstree('get_node', 'ejecutivo_' + id_eje);
                
                if (node && node.id) {
                    var nodeElement = tree.find('#' + node.id + '_anchor');
                    if (nodeElement.length) {
                        console.log('Aplicando estilo INMEDIATO a nodo:', node.id, 'en árbol:', treeId);
                        
                        // Aplicar clase específica según el tipo de cambio - INMEDIATO
                        if (campo === 'movimiento') {
                            nodeElement.addClass('ejecutivo-movido');
                            console.log('Clase ejecutivo-movido aplicada inmediatamente por 2 segundos');
                            setTimeout(function() {
                                nodeElement.removeClass('ejecutivo-movido');
                                console.log('Clase ejecutivo-movido removida');
                            }, 2000); // Reducido de 3000 a 2000
                        } else if (campo === 'cambio_plantel') {
                            // Estilo específico y más prominente para cambios de plantel - INMEDIATO
                            nodeElement.addClass('ejecutivo-cambio-plantel');
                            console.log('Clase ejecutivo-cambio-plantel aplicada inmediatamente por 3 segundos');
                            setTimeout(function() {
                                nodeElement.removeClass('ejecutivo-cambio-plantel');
                                console.log('Clase ejecutivo-cambio-plantel removida');
                            }, 3000); // Reducido de 8000 a 3000
                        } else {
                            // Para otros cambios, usar el estilo general - INMEDIATO
                            nodeElement.addClass('websocket-changed');
                            setTimeout(function() {
                                nodeElement.removeClass('websocket-changed');
                            }, 1500); // Reducido de 2000 a 1500
                        }
                        
                        // Hacer scroll hacia el elemento para que sea visible inmediatamente
                        nodeElement[0].scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    } else {
                        console.warn('No se encontró elemento del nodo:', node.id);
                    }
                } else {
                    console.warn('No se encontró nodo ejecutivo_' + id_eje + ' en árbol:', treeId);
                }
            });
        }
        
        function mostrarBadgeWebSocket(tipo, mensaje) {
            var badge = $('<div class="badge badge-' + tipo + ' websocket-badge">' + mensaje + '</div>');
            $('body').append(badge);
            
            // Remover badge después de la animación
            setTimeout(function() {
                badge.remove();
            }, 3000);
        }
        
        function actualizarEstadoWebSocket(tipo, mensaje) {
            var statusElement = $('#websocket-status');
            var statusText = $('#websocket-status-text');
            
            statusElement.removeClass('success danger warning');
            statusElement.addClass(tipo);
            statusText.text(mensaje);
        }
        
        function log(mensaje) {
            var time = new Date().toLocaleTimeString();
            var logElement = $('#websocket-log-text');
            var currentLog = logElement.text();
            
            // Mantener solo los últimos 3 mensajes
            var lines = currentLog.split('\n').filter(function(line) { return line.trim() !== ''; });
            if (lines.length >= 3) {
                lines = lines.slice(-2);
            }
            
            lines.push('[' + time + '] ' + mensaje);
            logElement.text(lines.join('\n'));
            
            // Scroll automático
            $('#websocket-log')[0].scrollTop = $('#websocket-log')[0].scrollHeight;
            
            // También log en consola para debugging
            console.log('[WebSocket] ' + mensaje);
        }
        
        // =====================================
        // NUEVAS FUNCIONES WEBSOCKET P25/P26
        // =====================================
        
        function procesarCambioPlantelWebSocket(mensaje) {
            // El mensaje llega anidado en un objeto 'datos'
            var datos = mensaje.datos || mensaje;
            
            if (!datos.id_eje) {
                return;
            }
            
            log('🏢 Procesando cambio de plantel ejecutivo ID: ' + datos.id_eje);
            
            // Buscar el ejecutivo y actualizar su plantel
            var ejecutivo = ejecutivos.find(e => e.id_eje == datos.id_eje);
            if (ejecutivo) {
                var plantelAnterior = ejecutivo.id_pla;
                ejecutivo.id_pla = datos.plantel_nuevo;
                
                // Regenerar todos los árboles para mostrar cambios INMEDIATAMENTE
                generarArbolesPorPlantel();
                
                // Reaplicar imágenes después de regenerar por cambio de plantel WebSocket
                setTimeout(function() {
                    aplicarImagenesEjecutivos();
                }, 100);
                
                // Recargar conteos de citas porque cambió la distribución
                cargarCitasPorPlantel();
                
                // Aplicar feedback visual específico INMEDIATO - SIN DELAY
                aplicarFeedbackVisualEjecutivo(datos.id_eje, 'cambio_plantel');
                
                // Mostrar notificación mejorada con nombres de planteles
                var plantelAnteriorNombre = datos.nombre_plantel_anterior || ('Plantel ID ' + datos.plantel_anterior);
                var plantelNuevoNombre = datos.nombre_plantel_nuevo || ('Plantel ID ' + datos.plantel_nuevo);
                var mensajeNotif = datos.nom_eje + ' se movió de ' + plantelAnteriorNombre + ' a ' + plantelNuevoNombre;
                
                mostrarBadgeWebSocket('warning', mensajeNotif);
                
                // Log detallado
                log('✅ Ejecutivo actualizado: ' + datos.nom_eje + ' (' + plantelAnteriorNombre + ' → ' + plantelNuevoNombre + ')');
            } else {
                log('⚠️ No se encontró ejecutivo con ID: ' + datos.id_eje);
            }
        }
        
        function procesarActualizacionCitasPlantelWebSocket(mensaje) {
            if (!mensaje.id_pla) {
                return;
            }
            
            log('📊 Actualizando estadísticas plantel ID: ' + mensaje.id_pla);
            
            // Buscar el plantel y actualizar sus estadísticas si las tenemos cacheadas
            var plantelActualizar = citasPorPlantel[mensaje.id_pla];
            if (plantelActualizar && mensaje.estadisticas) {
                // Actualizar estadísticas cacheadas
                Object.assign(plantelActualizar, mensaje.estadisticas);
                
                // Actualizar contadores visuales en la interfaz
                actualizarContadorPlantel(mensaje.id_pla, mensaje.estadisticas);
                
                // Mostrar notificación
                mostrarBadgeWebSocket('info', 'Estadísticas actualizadas: ' + mensaje.nom_pla);
            }
        }
        
        function procesarCitaCambioPlantelWebSocket(mensaje) {
            if (!mensaje.id_cit) {
                return;
            }
            
            log('🔄 Procesando migración cita ID: ' + mensaje.id_cit);
            
            // Recargar estadísticas de ambos planteles afectados
            if (mensaje.plantel_anterior) {
                cargarCitasPlantelEspecifico(mensaje.plantel_anterior);
            }
            if (mensaje.plantel_nuevo) {
                cargarCitasPlantelEspecifico(mensaje.plantel_nuevo);
            }
            
            // Mostrar notificación
            mostrarBadgeWebSocket('info', 'Cita migrada entre planteles');
        }
        
        function procesarCitaDisociacionWebSocket(mensaje) {
            if (!mensaje.id_cit) {
                return;
            }
            
            log('❌ Procesando disociación cita ID: ' + mensaje.id_cit);
            
            // Recargar estadísticas porque una cita quedó sin ejecutivo
            cargarCitasPorPlantel();
            
            // Mostrar notificación
            mostrarBadgeWebSocket('warning', 'Cita desasociada de ejecutivo');
        }
        
        function procesarCitaReasociacionWebSocket(mensaje) {
            if (!mensaje.id_cit) {
                return;
            }
            
            log('➡️ Procesando reasociación cita ID: ' + mensaje.id_cit);
            
            // Recargar estadísticas porque cambió la asignación
            cargarCitasPorPlantel();
            
            // Mostrar notificación  
            mostrarBadgeWebSocket('success', 'Cita reasignada a nuevo ejecutivo');
        }
        
        function actualizarContadorPlantel(idPlantel, estadisticas) {
            // Actualizar el contador visual del plantel en la interfaz
            var contenedorPlantel = $('#plantel_' + idPlantel);
            if (contenedorPlantel.length) {
                var headerPlantel = contenedorPlantel.find('.plantel-header');
                var contadorSpan = headerPlantel.find('.badge-primary');
                
                if (contadorSpan.length && estadisticas.total_citas !== undefined) {
                    contadorSpan.text(estadisticas.total_citas + ' citas');
                    
                    // Agregar animación de actualización
                    contadorSpan.addClass('websocket-changed');
                    setTimeout(function() {
                        contadorSpan.removeClass('websocket-changed');
                    }, 2000);
                }
            }
        }
        
        function cargarCitasPlantelEspecifico(idPlantel) {
            // Función auxiliar para recargar las estadísticas de un plantel específico
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: { 
                    action: 'obtener_citas_totales_por_plantel',
                    id_pla: idPlantel 
                },
                dataType: 'json'
            }).done(function(response) {
                if (response.success && response.data) {
                    citasPorPlantel[idPlantel] = response.data;
                    actualizarContadorPlantel(idPlantel, response.data);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error al cargar citas del plantel ' + idPlantel + ':', error);
            });
        }

        // =====================================
        // FUNCIONES DE CARGA DE DATOS
        // =====================================
        
        function cargarPlanteles() {
            return $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: { action: 'obtener_planteles' },
                dataType: 'json'
            }).done(function(response) {
                console.log('Respuesta planteles:', response);
                if(response.success) {
                    planteles = response.data;
                    console.log('Planteles cargados:', planteles.length);
                    generarPlanteles();
                    cargarSelectPlanteles();
                } else {
                    console.error('Error planteles:', response.message);
                    throw new Error('Error al cargar planteles: ' + response.message);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error AJAX planteles:', {xhr: xhr, status: status, error: error});
                console.error('Respuesta completa:', xhr.responseText);
                throw new Error('Error de conexión al cargar planteles');
            });
        }
        
        function cargarEjecutivos() {
            // Obtener fechas de filtro si existen
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
            
            return $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: datosEnvio,
                dataType: 'json'
            }).done(function(response) {
                console.log('Respuesta ejecutivos con citas:', response);
                if(response.success) {
                    ejecutivos = response.data;
                    console.log('Ejecutivos cargados:', ejecutivos.length);
                    console.log('Datos de ejecutivos:', ejecutivos);
                    
                    // Debug: mostrar distribución
                    var distribucion = {};
                    ejecutivos.forEach(function(ej) {
                        var plantel = ej.id_pla || 'Sin plantel';
                        distribucion[plantel] = (distribucion[plantel] || 0) + 1;
                    });
                    console.log('Distribución por plantel:', distribucion);
                    
                    // Debug: verificar ejecutivos por cada plantel
                    planteles.forEach(function(p) {
                        var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == p.id_pla);
                        console.log('Plantel', p.nom_pla, '(ID:', p.id_pla, ') tiene', ejecutivosPlantel.length, 'ejecutivos');
                        ejecutivosPlantel.forEach(function(ej) {
                            console.log('  -', ej.nom_eje, 'Activo:', ej.eli_eje, 'Padre:', ej.id_padre, 'Citas propias:', ej.citas_propias, 'Citas recursivas:', ej.citas_recursivas);
                        });
                    });
                    
                    generarArbolesPorPlantel();
                    
                    // Aplicar imágenes después de generar los árboles con múltiples intentos
                    setTimeout(function() {
                        console.log('🕐 CARGA INICIAL - Aplicando imágenes (500ms)');
                        aplicarImagenesEjecutivos();
                    }, 500);
                    
                    setTimeout(function() {
                        console.log('🕐 CARGA INICIAL - Aplicando imágenes (1000ms)');
                        aplicarImagenesEjecutivos();
                    }, 1000);
                    
                    setTimeout(function() {
                        console.log('🕐 CARGA INICIAL - Aplicando imágenes (2000ms)');
                        aplicarImagenesEjecutivos();
                    }, 2000);
                    
                    setTimeout(function() {
                        console.log('🕐 CARGA INICIAL - Aplicando imágenes (3000ms)');
                        aplicarImagenesEjecutivos();
                    }, 3000);
                    
                    actualizarEstadisticas();
                } else {
                    console.error('Error ejecutivos:', response.message);
                    throw new Error('Error al cargar ejecutivos: ' + response.message);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error AJAX ejecutivos:', {xhr: xhr, status: status, error: error});
                console.error('Respuesta completa:', xhr.responseText);
                throw new Error('Error de conexión al cargar ejecutivos');
            });
        }
        
        // =====================================
        // FUNCIONES DE GENERACIÓN DE PLANTELES
        // =====================================
        
        function generarPlanteles() {
            var html = '';
            
            planteles.forEach(function(plantel) {
                // Calcular conteo de ejecutivos por plantel
                var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == plantel.id_pla);
                
                html += `
                    <div class="col-md-4">
                        <div class="plantel-container" data-plantel-id="${plantel.id_pla}">
                            <div class="plantel-header">
                                <i class="fas fa-building"></i> ${plantel.nom_pla}
                                <div class="badge badge-light" id="count-plantel-${plantel.id_pla}">
                                    ${ejecutivosPlantel.length}
                                </div>
                                <span class="badge badge-citas-plantel ml-2" id="badge-citas-plantel-${plantel.id_pla}" onclick="verDetallesCitasPlantel(${plantel.id_pla})" title="Cargando citas..." style="display: none;">0</span>
                            </div>
                            <div class="plantel-tree" id="jstree-plantel-${plantel.id_pla}"></div>
                        </div>
                    </div>
                `;
            });
            
            $('#planteles-container').html(html);
            
            // Cargar conteo de citas por plantel de forma asíncrona
            cargarCitasPorPlantel();
        }
        
        function cargarCitasPorPlantel() {
            // Obtener fechas de filtro si existen
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            planteles.forEach(function(plantel) {
                var datosEnvio = { 
                    action: 'obtener_citas_totales_por_plantel',
                    id_pla: plantel.id_pla
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
                        if(response.success) {
                            var totalCitas = response.data.total_citas;
                            var badgeElement = $('#badge-citas-plantel-' + plantel.id_pla);
                            
                            if(totalCitas > 0) {
                                badgeElement.text(totalCitas + (totalCitas >= 2 ? ' citas totales' : ' cita total'))
                                .attr('title', 'Citas totales del plantel: ' + totalCitas)
                                .show();
                            } else {
                                badgeElement.hide();
                            }
                        } else {
                            console.error('Error al obtener citas del plantel', plantel.id_pla, ':', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX citas plantel', plantel.id_pla, ':', error);
                    }
                });
            });
        }
        
        // Función de debug para verificar ejecutivos con fotos
        function debugEjecutivosConFotos() {
            console.log('🔍 ==== DEBUG EJECUTIVOS CON FOTOS ====');
            console.log('Total ejecutivos cargados:', ejecutivos.length);
            
            var ejecutivosConFoto = ejecutivos.filter(ej => ej.fot_eje && ej.fot_eje.trim() !== '');
            console.log('Ejecutivos con foto:', ejecutivosConFoto.length);
            
            ejecutivosConFoto.forEach(ej => {
                console.log('📷 Ejecutivo:', ej.nom_eje);
                console.log('   - ID:', ej.id_eje);
                console.log('   - Foto:', ej.fot_eje);
                console.log('   - Plantel:', ej.id_pla);
                console.log('   - Archivo ruta:', 'uploads/' + ej.fot_eje);
                
                // Probar carga de imagen para verificar que existe
                var testImg = new Image();
                testImg.onload = function() {
                    console.log('   - ✅ Imagen EXISTE y se puede cargar:', ej.fot_eje);
                };
                testImg.onerror = function() {
                    console.log('   - ❌ Imagen NO EXISTE o no se puede cargar:', ej.fot_eje);
                };
                testImg.src = 'uploads/' + ej.fot_eje;
                
                // Verificar si el elemento está en el DOM
                var nodoEncontrado = $('[id="' + ej.id_eje + '"]');
                console.log('   - Nodo en DOM:', nodoEncontrado.length);
                
                if (nodoEncontrado.length > 0) {
                    var anchor = nodoEncontrado.find('.jstree-anchor');
                    console.log('   - Anchor encontrado:', anchor.length);
                    var imagen = anchor.find('.ejecutivo-imagen');
                    console.log('   - Imagen ya aplicada:', imagen.length);
                    var icono = anchor.find('.jstree-icon');
                    console.log('   - Ícono presente:', icono.length);
                } else {
                    console.log('   - ❌ NODO NO ENCONTRADO EN DOM');
                }
            });
            
            // Verificar todos los nodos en DOM por plantel
            $('.plantel-tree').each(function(index) {
                var plantelTree = $(this);
                var plantelContainer = plantelTree.closest('.plantel-container');
                var plantelId = plantelContainer.data('plantel-id');
                console.log('🌳 PLANTEL ID:', plantelId, '- Árbol #', index);
                
                var todosLosNodos = plantelTree.find('.jstree-node');
                console.log('   - Nodos jsTree en este plantel:', todosLosNodos.length);
                
                todosLosNodos.each(function() {
                    var id = $(this).attr('id');
                    var texto = $(this).find('.jstree-anchor').text().trim();
                    console.log('   - Nodo ID:', id, 'Texto:', texto);
                });
            });
            
            // También verificar si jsTree está inicializado
            $('.plantel-tree').each(function(index) {
                var jstreeInstance = $(this).jstree(true);
                if (jstreeInstance) {
                    console.log('🌳 jsTree INSTANCIA #', index, '- Estado:', 'INICIALIZADO');
                    var nodes = jstreeInstance.get_json('#', {flat: true});
                    console.log('   - Nodos desde jsTree API:', nodes.length);
                    nodes.forEach(function(node) {
                        console.log('   - Node API ID:', node.id, 'Text:', node.text);
                    });
                } else {
                    console.log('🌳 jsTree INSTANCIA #', index, '- Estado:', 'NO INICIALIZADO');
                }
            });
        }
        
        // Función para test manual desde consola
        function testImagenesManual() {
            console.log('🧪 TEST MANUAL DE IMÁGENES');
            aplicarImagenesEjecutivos();
        }
        
        // Función para forzar aplicación agresiva de imágenes
        function forzarImagenes() {
            console.log('💪 FORZANDO APLICACIÓN AGRESIVA DE IMÁGENES');
            
            // Aplicar cada 500ms durante 10 segundos
            for (let i = 0; i < 20; i++) {
                setTimeout(function() {
                    console.log('💪 Intento forzado #' + (i + 1));
                    aplicarImagenesEjecutivos();
                    if (i % 5 === 0) { // Cada 5 intentos, usar el método super agresivo
                        aplicarImagenesSuperAgresiva();
                    }
                }, i * 500);
            }
        }
        
        // Función para debug de generación de nodos
        function debugGeneracionNodos() {
            console.log('🔧 ==== DEBUG GENERACIÓN DE NODOS ====');
            
            planteles.forEach(function(plantel) {
                console.log('🏢 PLANTEL:', plantel.nom_pla, 'ID:', plantel.id_pla);
                
                var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == plantel.id_pla);
                console.log('   - Ejecutivos en este plantel:', ejecutivosPlantel.length);
                
                ejecutivosPlantel.forEach(function(ej) {
                    console.log('   - Ejecutivo:', ej.nom_eje, 'ID:', ej.id_eje, 'Foto:', ej.fot_eje || 'SIN FOTO');
                });
                
                // Generar nodos para este plantel específicamente
                var nodosGenerados = generarNodosJsTree(ejecutivosPlantel);
                console.log('   - Nodos generados:', nodosGenerados.length);
                
                nodosGenerados.forEach(function(nodo) {
                    console.log('   - Nodo ID:', nodo.id, 'Text:', nodo.text.replace(/<[^>]*>/g, ''), 'Parent:', nodo.parent);
                });
            });
        }
        
        // Variables globales para debug adicional
        window.debugGeneracionNodos = debugGeneracionNodos;
        
        // Función para verificar estado completo del DOM
        function verificarEstadoDOM() {
            console.log('🔍 ==== VERIFICACIÓN COMPLETA DEL DOM ====');
            
            // 1. Verificar contenedores de planteles
            var contenedoresPlanteles = $('.plantel-container');
            console.log('Contenedores de planteles encontrados:', contenedoresPlanteles.length);
            
            contenedoresPlanteles.each(function(index) {
                var container = $(this);
                var plantelId = container.data('plantel-id');
                var plantelTree = container.find('.plantel-tree');
                console.log('   - Contenedor #' + index + ': Plantel ID=' + plantelId);
                console.log('     * Tiene .plantel-tree:', plantelTree.length);
                
                if (plantelTree.length > 0) {
                    var treeId = plantelTree.attr('id');
                    console.log('     * Tree ID:', treeId);
                    
                    // Verificar si tiene clase jstree
                    var tieneJsTree = plantelTree.hasClass('jstree');
                    console.log('     * Tiene clase jstree:', tieneJsTree);
                    
                    // Verificar nodos
                    var nodos = plantelTree.find('.jstree-node');
                    console.log('     * Nodos encontrados:', nodos.length);
                    
                    nodos.each(function() {
                        var nodo = $(this);
                        var id = nodo.attr('id');
                        var texto = nodo.find('.jstree-anchor').text().trim();
                        console.log('       - Nodo ID: "' + id + '", Texto: "' + texto + '"');
                    });
                }
            });
            
            // 2. Verificar todos los elementos con ID numérico
            var elementosConIdNumerico = $('[id]').filter(function() {
                return /^\d+$/.test(this.id);
            });
            
            console.log('Elementos con ID numérico encontrados:', elementosConIdNumerico.length);
            elementosConIdNumerico.each(function() {
                var elemento = $(this);
                var id = elemento.attr('id');
                var clases = elemento.attr('class') || 'sin-clases';
                var texto = elemento.text().trim() || 'sin-texto';
                console.log('   - ID: "' + id + '", Clases: "' + clases + '", Texto: "' + texto.substring(0, 50) + '"');
            });
            
            // 3. Verificar ejecutivos con fotos que deberían estar
            var ejecutivosConFoto = ejecutivos.filter(ej => ej.fot_eje);
            console.log('Ejecutivos con fotos que deberían estar en DOM:', ejecutivosConFoto.length);
            
            ejecutivosConFoto.forEach(function(ej) {
                console.log('   🔎 Buscando ejecutivo:', ej.nom_eje, 'ID:', ej.id_eje);
                
                // Buscar de todas las formas posibles
                var porId = $('#' + ej.id_eje);
                var porAtributoId = $('[id="' + ej.id_eje + '"]');
                var porTexto = $('.jstree-anchor:contains("' + ej.nom_eje + '")');
                
                console.log('     * Por #ID:', porId.length);
                console.log('     * Por [id=""]:', porAtributoId.length);
                console.log('     * Por texto:', porTexto.length);
                
                if (porTexto.length > 0) {
                    porTexto.each(function() {
                        var nodo = $(this).closest('.jstree-node');
                        var idNodo = nodo.attr('id');
                        console.log('     * Encontrado por texto - ID del nodo:', idNodo);
                    });
                }
            });
        }
        
        // Función SUPER AGRESIVA para aplicar imágenes - última línea de defensa
        function aplicarImagenesSuperAgresiva() {
            console.log('💪 ===== APLICACIÓN SUPER AGRESIVA DE IMÁGENES =====');
            
            // Buscar TODOS los anchors de jsTree en toda la página
            var todosLosAnchors = $('.jstree-anchor');
            console.log('Total anchors jstree encontrados:', todosLosAnchors.length);
            
            todosLosAnchors.each(function() {
                var anchor = $(this);
                var nodo = anchor.closest('.jstree-node');
                var idNodo = nodo.attr('id');
                
                if (idNodo) {
                    console.log('🔍 Revisando anchor con nodo ID:', idNodo);
                    
                    // Buscar ejecutivo por ID
                    var ejecutivo = ejecutivos.find(ej => ej.id_eje == idNodo);
                    
                    if (ejecutivo && ejecutivo.fot_eje) {
                        console.log('💪 APLICANDO IMAGEN AGRESIVA a:', ejecutivo.nom_eje);
                        
                        // Remover imagen existente si la hay
                        anchor.find('.ejecutivo-imagen').remove();
                        
                        // Crear nueva imagen
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
                                'display': 'inline-block !important',
                                'vertical-align': 'middle',
                                'position': 'relative',
                                'z-index': '10'
                            },
                            alt: ejecutivo.nom_eje,
                            title: 'Foto de ' + ejecutivo.nom_eje,
                            class: 'ejecutivo-imagen',
                            'data-ejecutivo-id': ejecutivo.id_eje
                        });
                        
                        // Ocultar ícono
                        anchor.find('.jstree-icon').hide();
                        
                        // Insertar imagen
                        anchor.prepend(imgElement);
                        
                        console.log('💪 ✅ IMAGEN APLICADA AGRESIVAMENTE:', ejecutivo.nom_eje);
                    }
                }
            });
        }
        
        // Función global
        window.aplicarImagenesSuperAgresiva = aplicarImagenesSuperAgresiva;
        
        // Variables globales para debug
        window.debugEjecutivosConFotos = debugEjecutivosConFotos;
        window.testImagenesManual = testImagenesManual;
        window.aplicarImagenesEjecutivos = aplicarImagenesEjecutivos;
        window.forzarImagenes = forzarImagenes;
        window.aplicarImagenesSuperAgresiva = aplicarImagenesSuperAgresiva;
        window.verificarEstadoDOM = verificarEstadoDOM;
        
        // Función para aplicar imágenes de ejecutivos (reutilizable) - Portada desde arbol_ejecutivos.php
        function aplicarImagenesEjecutivos() {
            console.log('🖼️ ===== APLICANDO IMÁGENES DE EJECUTIVOS EN PLANTELES =====');
            console.log('Total de ejecutivos a revisar:', ejecutivos.length);
            
            var ejecutivosConFoto = ejecutivos.filter(ej => ej.fot_eje);
            console.log('Ejecutivos con foto encontrados:', ejecutivosConFoto.length);
            ejecutivosConFoto.forEach(ej => {
                console.log('  - ID:', ej.id_eje, 'Nombre:', ej.nom_eje, 'Foto:', ej.fot_eje, 'Plantel:', ej.id_pla);
            });
            
            ejecutivos.forEach(function(ejecutivo) {
                if (ejecutivo.fot_eje) {
                    console.log('🔍 Procesando ejecutivo con foto:', ejecutivo.nom_eje, 'ID:', ejecutivo.id_eje, 'Foto:', ejecutivo.fot_eje);
                    
                    // Intentar múltiples estrategias para encontrar el nodo
                    var nodo = null;
                    
                    // Estrategia 1: Selector de atributo
                    nodo = $('[id="' + ejecutivo.id_eje + '"]');
                    console.log('  - Estrategia 1 [id="' + ejecutivo.id_eje + '"]:', nodo.length);
                    
                    // Estrategia 2: ID directo
                    if (nodo.length === 0) {
                        nodo = $('#' + ejecutivo.id_eje);
                        console.log('  - Estrategia 2 #' + ejecutivo.id_eje + ':', nodo.length);
                    }
                    
                    // Estrategia 3: Buscar en todos los árboles específicamente
                    if (nodo.length === 0) {
                        $('.plantel-tree').each(function() {
                            var nodoEnArbol = $(this).find('[id="' + ejecutivo.id_eje + '"]');
                            if (nodoEnArbol.length > 0) {
                                nodo = nodoEnArbol;
                                console.log('  - Estrategia 3 encontrado en árbol:', nodo.length);
                                return false; // Salir del each
                            }
                        });
                    }
                    
                    // Estrategia 4: Buscar por clase jstree-node con ID
                    if (nodo.length === 0) {
                        nodo = $('.jstree-node[id="' + ejecutivo.id_eje + '"]');
                        console.log('  - Estrategia 4 .jstree-node[id="' + ejecutivo.id_eje + '"]:', nodo.length);
                    }
                    
                    if (nodo.length > 0) {
                        console.log('  - ✅ Nodo encontrado en DOM');
                        var anchor = nodo.find('.jstree-anchor');
                        console.log('  - Anchors encontrados:', anchor.length);
                        
                        // Verificar que no tenga ya una imagen aplicada
                        if (!anchor.find('.ejecutivo-imagen').length) {
                            console.log('  - 🆕 Creando nueva imagen para:', ejecutivo.nom_eje);
                            
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
                            
                            console.log('  - ✅ Imagen insertada para:', ejecutivo.nom_eje, 'ID:', ejecutivo.id_eje, 'Archivo:', ejecutivo.fot_eje);
                            console.log('  - ✅ Imagen DOM element:', imgElement[0]);
                            console.log('  - ✅ Ícono oculto:', icono.is(':hidden'));
                            
                            console.log('Imagen aplicada a ejecutivo:', ejecutivo.nom_eje, 'ID:', ejecutivo.id_eje, 'Plantel:', ejecutivo.id_pla);
                        } else {
                            // Ya tiene imagen, asegurar que esté visible y el ícono oculto
                            var img = anchor.find('.ejecutivo-imagen');
                            img.show();
                            anchor.find('.jstree-icon').hide();
                            console.log('  - ♻️ Imagen ya existente mantenida para:', ejecutivo.nom_eje, 'Visible:', img.is(':visible'));
                        }
                    } else {
                        console.log('  - ❌ Nodo NO encontrado en DOM para:', ejecutivo.nom_eje, 'ID:', ejecutivo.id_eje);
                        console.log('  - ❌ Verificando si existe algún elemento con ese ID...');
                        var todoElemento = $('#' + ejecutivo.id_eje);
                        console.log('  - ❌ Elemento por ID simple:', todoElemento.length);
                        var todosElementos = $('[id]').filter(function() { return this.id == ejecutivo.id_eje; });
                        console.log('  - ❌ Elementos con ID coincidente:', todosElementos.length);
                    }
                } else {
                    // Si el ejecutivo NO tiene foto, asegurar que su ícono esté visible
                    var nodo = $('[id="' + ejecutivo.id_eje + '"]');
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
        
        function generarArbolesPorPlantel() {
            console.log('=== GENERANDO ÁRBOLES POR PLANTEL ===');
            console.log('Planteles disponibles:', planteles.length);
            console.log('Ejecutivos disponibles:', ejecutivos.length);
            
            // Debug: verificar ejecutivos con fotos antes de generar árboles
            var ejecutivosConFoto = ejecutivos.filter(ej => ej.fot_eje);
            console.log('🖼️ Ejecutivos con foto ANTES de generar árboles:', ejecutivosConFoto.length);
            ejecutivosConFoto.forEach(ej => {
                console.log('   - ID:', ej.id_eje, 'Nombre:', ej.nom_eje, 'Plantel:', ej.id_pla, 'Foto:', ej.fot_eje);
            });
            
            planteles.forEach(function(plantel) {
                console.log('🏢 Procesando plantel:', plantel.nom_pla, '(ID:', plantel.id_pla, ')');
                
                var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == plantel.id_pla);
                console.log('   - Ejecutivos en este plantel:', ejecutivosPlantel.length);
                
                // Debug: verificar ejecutivos con fotos en este plantel específico
                var ejecutivosConFotoPlantel = ejecutivosPlantel.filter(ej => ej.fot_eje);
                console.log('   - Ejecutivos con foto en este plantel:', ejecutivosConFotoPlantel.length);
                ejecutivosConFotoPlantel.forEach(ej => {
                    console.log('     * ID:', ej.id_eje, 'Nombre:', ej.nom_eje, 'Foto:', ej.fot_eje);
                });
                
                var nodosTree = generarNodosJsTree(ejecutivosPlantel);
                console.log('   - Nodos generados:', nodosTree.length);
                
                // Actualizar contador
                $('#count-plantel-' + plantel.id_pla).text(ejecutivosPlantel.length);
                
                // Generar árbol
                var treeId = '#jstree-plantel-' + plantel.id_pla;
                
                // Verificar que el elemento existe
                if($(treeId).length === 0) {
                    console.error('ERROR: Elemento', treeId, 'no encontrado');
                    return;
                }
                
                // Destruir árbol existente si existe
                if($(treeId).hasClass('jstree')) {
                    $(treeId).jstree('destroy');
                    console.log('Árbol anterior destruido para:', treeId);
                }
                
                // Validar que hay nodos para mostrar
                if(nodosTree.length === 0) {
                    $(treeId).html('<p class="text-center text-muted">No hay ejecutivos en este plantel</p>');
                    return;
                }
                
                // Crear nuevo árbol usando closure para preservar el contexto del plantel
                (function(currentPlantel, currentTreeId, currentNodes) {
                    $(currentTreeId).jstree({
                        'core': {
                            'data': currentNodes,
                            'check_callback': function(operation, node, parent, position, more) {
                                if(operation === 'move_node') {
                                    return true; // Permitir movimiento
                                }
                                return true;
                            },
                            'themes': {
                                'responsive': true,
                                'variant': 'large',
                                'stripes': false,
                                'dots': true,
                                'icons': true
                            }
                        },
                        'plugins': ['dnd', 'types', 'state'],
                        'state': {
                            'key': 'jstree_plantel_' + currentPlantel.id_pla
                        },
                        'dnd': {
                            'is_draggable': function(node) {
                                return true;
                            },
                            'copy': false,
                            'large_drop_target': true,
                            'large_drag_target': true,
                            'touch': true,
                            'inside_pos': 'last',
                            'check_while_dragging': true,
                            'always_copy': false,
                            'drag_selection': false,
                            'use_html5': true,
                            // Permitir drops externos en cualquier parte del árbol
                            'check_callback': function(operation, node, parent, position, more) {
                                // Permitir drops externos (entre planteles)
                                if(operation === 'dnd_start' || operation === 'dnd_stop') {
                                    return true;
                                }
                                
                                // Para movimientos dentro del mismo árbol
                                if(operation === 'move_node') {
                                    return true;
                                }
                                
                                // Para drops externos
                                if(operation === 'copy_node' || operation === 'move_node') {
                                    return true;
                                }
                                
                                return true;
                            }
                        },
                        'types': {
                            'default': {
                                'icon': 'fas fa-user'
                            },
                            'inactive': {
                                'icon': 'fas fa-user-slash'
                            }
                        }
                    }).on('ready.jstree', function() {
                        console.log('✅ Árbol jsTree inicializado correctamente para:', currentPlantel.nom_pla);
                        console.log('   - Nodos creados:', currentNodes.length);
                        
                        // Expandir todos los nodos para mostrar la estructura completa
                        $(currentTreeId).jstree('open_all');
                        
                        console.log('🌳 jsTree expandido completamente para:', currentPlantel.nom_pla);
                        console.log('🌳 Nodos en DOM después de expandir:', $(currentTreeId).find('.jstree-node').length);
                        
                        // Aplicar imágenes inmediatamente después de la inicialización
                        setTimeout(function() {
                            console.log('🕐 TIMEOUT 200ms - Aplicando imágenes para:', currentPlantel.nom_pla);
                            verificarEstadoDOM(); // Agregar verificación completa
                            debugEjecutivosConFotos();
                            aplicarImagenesEjecutivos();
                        }, 200);
                        
                        // Aplicar estilos adicionales para mejorar la visualización jerárquica
                        setTimeout(function() {
                            // Verificar cuántos nodos están visibles
                            var visibleNodes = $(currentTreeId).find('.jstree-node').length;
                            console.log('   - Nodos visibles en el DOM:', visibleNodes);
                            
                            // Agregar clases específicas para mejorar la visualización por niveles
                            $(currentTreeId).find('.jstree-node[aria-level="1"]').addClass('jstree-root-level');
                            $(currentTreeId).find('.jstree-node[aria-level="2"]').addClass('jstree-level-2');
                            $(currentTreeId).find('.jstree-node[aria-level="3"]').addClass('jstree-level-3');
                            $(currentTreeId).find('.jstree-node[aria-level="4"]').addClass('jstree-level-4');
                            $(currentTreeId).find('.jstree-node[aria-level="5"]').addClass('jstree-level-5');
                            
                            // Añadir iconos específicos por nivel
                            $(currentTreeId).find('.jstree-root-level .jstree-icon').removeClass('fas fa-user').addClass('fas fa-crown');
                            $(currentTreeId).find('.jstree-level-2 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-tie');
                            $(currentTreeId).find('.jstree-level-3 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-friends');
                            $(currentTreeId).find('.jstree-level-4 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-check');
                            $(currentTreeId).find('.jstree-level-5 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-plus');
                            
                            // Personalizar iconos con imágenes de ejecutivos usando función mejorada
                            aplicarImagenesEjecutivos();
                            
                            console.log('   - Clases de nivel aplicadas correctamente');
                        }, 150);
                        
                        // Aplicar imágenes adicionales con delays progresivos para asegurar completa renderización
                        setTimeout(function() {
                            console.log('🕐 TIMEOUT 500ms - Segunda aplicación de imágenes');
                            aplicarImagenesEjecutivos();
                        }, 500);
                        
                        setTimeout(function() {
                            console.log('🕐 TIMEOUT 1000ms - Tercera aplicación de imágenes');
                            aplicarImagenesEjecutivos();
                        }, 1000);
                        
                        setTimeout(function() {
                            console.log('🕐 TIMEOUT 2000ms - Cuarta aplicación de imágenes');
                            aplicarImagenesEjecutivos();
                        }, 2000);
                        
                        // Una aplicación final después de que todo esté estable
                        setTimeout(function() {
                            console.log('🕐 TIMEOUT 3000ms - Aplicación final de imágenes');
                            aplicarImagenesEjecutivos();
                            aplicarImagenesSuperAgresiva(); // Aplicación super agresiva
                        }, 3000);
                    }).on('select_node.jstree', function(e, data) {
                        var ejecutivo = ejecutivos.find(ej => ej.id_eje == data.node.id);
                        if(ejecutivo) {
                            console.log('Ejecutivo seleccionado:', ejecutivo.nom_eje);
                            mostrarInformacionEjecutivo(ejecutivo);
                            nodoSeleccionado = ejecutivo;
                        }
                    }).on('click', '.jstree-icon, .ejecutivo-imagen', function(e) {
                        // P32 - Evento de clic en icono/imagen de ejecutivo para mostrar card
                        e.preventDefault(); // Prevenir comportamiento por defecto del enlace
                        e.stopPropagation();
                        var nodeElement = $(this).closest('.jstree-node');
                        var nodeId = nodeElement.attr('id');
                        
                        // Buscar el nodo usando jsTree API para obtener el ID original
                        var nodoJsTree = $(currentTreeId).jstree('get_node', nodeId);
                        if (nodoJsTree && nodoJsTree.original && nodoJsTree.original.data) {
                            var ejecutivo = nodoJsTree.original.data;
                            mostrarCardEjecutivo(ejecutivo, e);
                        } else {
                            // Fallback: buscar por ID del nodo directamente
                            var ejecutivo = ejecutivos.find(ej => ej.id_eje == nodeId);
                            if (ejecutivo) {
                                mostrarCardEjecutivo(ejecutivo, e);
                            }
                        }
                    }).on('move_node.jstree', function(e, data) {
                        // Manejar movimiento dentro del mismo plantel
                        var ejecutivoId = data.node.id;
                        var nuevoPadreId = data.parent === '#' ? null : data.parent;
                        
                        console.log('Movimiento dentro del plantel:', currentPlantel.id_pla, 'ejecutivo:', ejecutivoId, 'nuevo padre:', nuevoPadreId);
                        moverEjecutivo(ejecutivoId, nuevoPadreId, currentPlantel.id_pla);
                        
                        // Reaplicar imágenes después del movimiento
                        setTimeout(function() {
                            aplicarImagenesEjecutivos();
                        }, 100);
                        
                        setTimeout(function() {
                            aplicarImagenesEjecutivos();
                        }, 300);
                    }).on('dnd_start.vakata', function(e, data) {
                        console.log('DND Start desde árbol:', currentTreeId, 'plantel:', currentPlantel.id_pla);
                        // Establecer variables globales para drag & drop entre planteles
                        if(data.data && data.data.nodes && data.data.nodes.length > 0) {
                            draggedNode = data.data.nodes[0];
                            draggedFromPlantel = currentPlantel.id_pla;
                            draggedExecutivo = ejecutivos.find(ej => ej.id_eje == draggedNode);
                            console.log('Variables drag establecidas:', {draggedNode, draggedFromPlantel, draggedExecutivo});
                        }
                    });
                })(plantel, treeId, nodosTree);
            });
            
            // Configurar drag & drop entre planteles después de crear los árboles
            setTimeout(function() {
                configurarDragDropEntrePlanteles();
                
                // Inicializar tooltips para el semáforo de sesión
                $('[data-toggle="tooltip"]').tooltip({
                    placement: 'top',
                    trigger: 'hover',
                    delay: { show: 300, hide: 100 }
                });
                
                console.log('Tooltips del semáforo inicializados');
            }, 500);
        }
        
        function generarNodosJsTree(ejecutivosPlantel) {
            var nodos = [];
            var nodosMap = {};
            
            console.log('=== GENERANDO NODOS JSTREE ===');
            console.log('Ejecutivos recibidos:', ejecutivosPlantel.length);
            console.log('Lista de ejecutivos:', ejecutivosPlantel);
            
            // Crear nodos
            ejecutivosPlantel.forEach(function(ejecutivo, index) {
                console.log('Procesando ejecutivo', index + 1, ':', ejecutivo.nom_eje);
                
                var icono = ejecutivo.eli_eje == 1 ? 'fas fa-user text-success' : 'fas fa-user-slash text-danger';
                
                // NO establecer imagen como ícono aquí - se hará en ready.jstree
                // if (ejecutivo.fot_eje) {
                //     icono = 'uploads/' + ejecutivo.fot_eje;
                // }
                
                var badge = ejecutivo.eli_eje == 1 ? 
                    '<span class="badge badge-success ml-1">Activo</span>' : 
                    '<span class="badge badge-danger ml-1">Inactivo</span>';
                
                // Generar indicador de semáforo de sesión P15
                var semaforoHtml = '';
                var tooltipText = '';
                
                console.log('  - Datos semáforo ejecutivo', ejecutivo.nom_eje, ':', {
                    semaforo_sesion: ejecutivo.semaforo_sesion,
                    dias_desde_ultima_sesion: ejecutivo.dias_desde_ultima_sesion,
                    ult_eje: ejecutivo.ult_eje
                });
                
                if (ejecutivo.semaforo_sesion) {
                    var colorSemaforo = ejecutivo.semaforo_sesion;
                    
                    // Generar texto del tooltip
                    switch(colorSemaforo) {
                        case 'verde':
                            tooltipText = 'Sesión reciente (≤1 día)';
                            if (ejecutivo.dias_desde_ultima_sesion !== null) {
                                tooltipText += ' - Último acceso: ' + ejecutivo.dias_desde_ultima_sesion + ' día(s)';
                            }
                            break;
                        case 'amarillo':
                            tooltipText = 'Sesión moderada (2-3 días)';
                            if (ejecutivo.dias_desde_ultima_sesion !== null) {
                                tooltipText += ' - Último acceso: ' + ejecutivo.dias_desde_ultima_sesion + ' día(s)';
                            }
                            break;
                        case 'rojo':
                            tooltipText = 'Sesión antigua (≥4 días)';
                            if (ejecutivo.dias_desde_ultima_sesion !== null) {
                                tooltipText += ' - Último acceso: ' + ejecutivo.dias_desde_ultima_sesion + ' día(s)';
                            }
                            break;
                        case 'sin_sesion':
                            tooltipText = 'Sin registro de sesión';
                            break;
                    }
                    
                    semaforoHtml = '<span class="semaforo-sesion ' + colorSemaforo + '" title="' + tooltipText + '" data-toggle="tooltip"></span>';
                    console.log('  - Semáforo generado:', colorSemaforo, 'HTML:', semaforoHtml);
                } else {
                    console.warn('  - No hay datos de semáforo para ejecutivo', ejecutivo.nom_eje);
                }
                
                // Generar emojis de planteles asociados
                var plantelesEmojis = '';
                if (ejecutivo.planteles_asociados_array && ejecutivo.planteles_asociados_array.length > 0) {
                    // Crear un emoji 🕋 por cada plantel asociado
                    plantelesEmojis = ' ' + '🕋'.repeat(ejecutivo.planteles_asociados_array.length);
                    console.log('  - Planteles asociados:', ejecutivo.planteles_asociados_array.length, 'planteles');
                }
                
                // Construir badges de conteo de citas - P18
                var badgesCitas = '';
                var badgesPropias = ejecutivo.citas_propias || 0;
                var badgesRecursivas = ejecutivo.citas_recursivas || 0;
                
                if (badgesPropias > 0) {
                    badgesCitas += '<span class="badge badge-citas-propias" onclick="verDetallesCitas(' + ejecutivo.id_eje + ', \'propias\')" title="Citas propias: ' + badgesPropias + '">' + badgesPropias + '</span>';
                }
                if (badgesRecursivas > 0) {
                    badgesCitas += '<span class="badge badge-citas-recursivas" onclick="verDetallesCitas(' + ejecutivo.id_eje + ', \'recursivas\')" title="Citas totales (recursivas): ' + badgesRecursivas + '">' + badgesRecursivas + '</span>';
                }
                
                console.log('  - Badges citas:', 'Propias:', badgesPropias, 'Recursivas:', badgesRecursivas);
                
                // Verificar si el padre existe en el mismo plantel
                var parent = '#'; // Por defecto es raíz
                if (ejecutivo.id_padre) {
                    var padreExiste = ejecutivosPlantel.find(ej => ej.id_eje == ejecutivo.id_padre);
                    if (padreExiste) {
                        parent = ejecutivo.id_padre;
                        console.log('  - Padre encontrado:', padreExiste.nom_eje, 'ID:', padreExiste.id_eje);
                    } else {
                        console.warn('  - Padre', ejecutivo.id_padre, 'no encontrado en el mismo plantel para ejecutivo', ejecutivo.nom_eje, '- se pondrá como raíz');
                    }
                } else {
                    console.log('  - Sin padre, será nodo raíz');
                }
                
                var nodo = {
                    'id': ejecutivo.id_eje,
                    'text': '<div class="jstree-anchor-content"><span class="ejecutivo-nombre">' + ejecutivo.nom_eje + '</span><div class="jstree-anchor-indicators">' + semaforoHtml + plantelesEmojis + ' ' + badge + badgesCitas + '</div></div>',
                    'icon': icono,
                    'parent': parent,
                    'data': ejecutivo,
                    'type': ejecutivo.eli_eje == 1 ? 'default' : 'inactive'
                };
                
                console.log('  - Nodo creado:', {
                    id: nodo.id,
                    text: ejecutivo.nom_eje,
                    parent: nodo.parent,
                    activo: ejecutivo.eli_eje
                });
                
                nodos.push(nodo);
                nodosMap[ejecutivo.id_eje] = nodo;
            });
            
            console.log('Total nodos generados:', nodos.length);
            console.log('Nodos finales:', nodos);
            
            // Validar estructura jerárquica
            var nodosRaiz = nodos.filter(n => n.parent === '#');
            var nodosHijos = nodos.filter(n => n.parent !== '#');
            console.log('Nodos raíz:', nodosRaiz.length, 'Nodos hijos:', nodosHijos.length);
            
            // Mostrar estructura de árbol
            nodosRaiz.forEach(function(raiz) {
                console.log('RAÍZ:', raiz.data.nom_eje);
                mostrarHijos(raiz, nodos, '  ');
            });
            
            return nodos;
        }
        
        function mostrarHijos(nodo, todosLosNodos, prefijo) {
            var hijos = todosLosNodos.filter(n => n.parent == nodo.id);
            hijos.forEach(function(hijo) {
                console.log(prefijo + '└─ ' + hijo.data.nom_eje);
                mostrarHijos(hijo, todosLosNodos, prefijo + '  ');
            });
        }
        
        // =====================================
        // FUNCIONES DE DRAG & DROP
        // =====================================
        
        function configurarDragDrop() {
            // Esta función ya no es necesaria, se reemplazó por configurarDragDropEntrePlanteles
        }
        
        function configurarDragDropEntrePlanteles() {
            console.log('Configurando drag & drop entre planteles...');
            
            // Limpiar eventos anteriores
            $('.plantel-container').off('dragover.planteles dragenter.planteles dragleave.planteles drop.planteles');
            
            // Asegurar que los contenedores de planteles sean drop zones
            $('.plantel-container').each(function() {
                var $container = $(this);
                var plantelId = $container.data('plantel-id');
                
                console.log('Configurando drop zone para plantel:', plantelId);
                
                $container.on('dragover.planteles dragenter.planteles', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if(draggedNode && draggedFromPlantel && $(this).data('plantel-id') != draggedFromPlantel) {
                        var targetPlantelId = $(this).data('plantel-id');
                        var targetPlantel = planteles.find(p => p.id_pla == targetPlantelId);
                        
                        // Solo log la primera vez que entra a la zona
                        if(!$(this).hasClass('drop-zone')) {
                            console.log('🎯 PLANTEL DESTINO:', targetPlantel ? targetPlantel.nom_pla : 'Desconocido');
                            
                            // Actualizar mensaje con plantel destino
                            var nombreEjecutivo = draggedExecutivo ? draggedExecutivo.nom_eje : 'ejecutivo';
                            var nombrePlantel = targetPlantel ? targetPlantel.nom_pla : 'plantel';
                            mostrarMensajeDragDrop(nombreEjecutivo + ' → ' + nombrePlantel, false, false);
                            
                            // Resaltar visualmente el plantel destino
                            $(this).addClass('plantel-destino-highlight');
                        }
                        
                        $(this).addClass('drop-zone');
                    }
                });
                
                // También agregar eventos a los elementos internos del plantel (jsTree nodes)
                $container.on('dragover.planteles dragenter.planteles', '.jstree-node, .jstree-anchor, .plantel-tree', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Propagar el evento al contenedor padre
                    var container = $(this).closest('.plantel-container');
                    if(draggedNode && draggedFromPlantel && container.data('plantel-id') != draggedFromPlantel) {
                        var targetPlantelId = container.data('plantel-id');
                        var targetPlantel = planteles.find(p => p.id_pla == targetPlantelId);
                        
                        if(!container.hasClass('drop-zone')) {
                            console.log('🎯 PLANTEL DESTINO (sobre nodo):', targetPlantel ? targetPlantel.nom_pla : 'Desconocido');
                            
                            // Actualizar mensaje con plantel destino
                            var nombreEjecutivo = draggedExecutivo ? draggedExecutivo.nom_eje : 'ejecutivo';
                            var nombrePlantelDestino = targetPlantel ? targetPlantel.nom_pla : 'plantel';
                            mostrarMensajeDragDrop(nombreEjecutivo + ' → ' + nombrePlantelDestino, false, false);
                            
                            container.addClass('plantel-destino-highlight');
                        }
                        
                        container.addClass('drop-zone');
                    }
                });
                
                $container.on('drop.planteles', '.jstree-node, .jstree-anchor, .plantel-tree', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Propagar el drop al contenedor padre
                    var container = $(this).closest('.plantel-container');
                    container.trigger('drop.planteles');
                });
                
                $container.on('dragleave.planteles', function(e) {
                    e.stopPropagation();
                    
                    // Solo remover la clase si realmente salimos del contenedor
                    var rect = this.getBoundingClientRect();
                    var x = e.originalEvent.clientX;
                    var y = e.originalEvent.clientY;
                    
                    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                        $(this).removeClass('drop-zone plantel-destino-highlight');
                    }
                });
                
                // También manejar dragleave en elementos internos
                $container.on('dragleave.planteles', '.jstree-node, .jstree-anchor, .plantel-tree', function(e) {
                    // Solo actuar si salimos completamente del contenedor padre
                    var container = $(this).closest('.plantel-container');
                    var rect = container[0].getBoundingClientRect();
                    var x = e.originalEvent.clientX;
                    var y = e.originalEvent.clientY;
                    
                    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                        container.removeClass('drop-zone plantel-destino-highlight');
                    }
                });
                
                $container.on('drop.planteles', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('📍 DROP EJECUTADO');
                    $(this).removeClass('drop-zone plantel-destino-highlight');
                    
                    var targetPlantel = $(this).data('plantel-id');
                    var plantelDestinoInfo = planteles.find(p => p.id_pla == targetPlantel);
                    
                    // LOG DEL PLANTEL DESTINO
                    console.log('🎯 PLANTEL DESTINO:', plantelDestinoInfo ? plantelDestinoInfo.nom_pla : 'Desconocido');
                    
                    if(draggedExecutivo && plantelDestinoInfo) {
                        console.log('👤 EJECUTIVO MOVIDO A:', plantelDestinoInfo.nom_pla);
                    }
                    
                    if(draggedNode && draggedFromPlantel && targetPlantel != draggedFromPlantel) {
                        var ejecutivoId = draggedNode;
                        
                        if(draggedExecutivo) {
                            console.log('Iniciando movimiento de ejecutivo:', {
                                nombre: draggedExecutivo.nom_eje,
                                id: ejecutivoId,
                                desde: draggedFromPlantel,
                                hacia: targetPlantel
                            });
                            
                            // Mover ejecutivo a nuevo plantel (sin padre - será raíz en el nuevo plantel)
                            moverEjecutivo(ejecutivoId, null, targetPlantel);
                        } else {
                            console.error('No se encontró información del ejecutivo');
                            mostrarMensajeDragDrop('Error: No se encontró información del ejecutivo', false, true);
                        }
                    } else if(targetPlantel == draggedFromPlantel) {
                        console.log('No se mueve porque es el mismo plantel');
                    } else {
                        console.warn('Faltan datos para el movimiento:', {
                            draggedNode: !!draggedNode,
                            draggedFromPlantel: !!draggedFromPlantel,
                            targetPlantel: !!targetPlantel
                        });
                    }
                });
            });
            
            console.log('Drag & drop entre planteles configurado');
            
            // Configuración adicional usando delegación de eventos para mayor robustez
            // Esto asegura que los drops funcionen en cualquier elemento hijo del contenedor
            $(document).on('dragover.delegado dragenter.delegado', '.plantel-container', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if(draggedNode && draggedFromPlantel && $(this).data('plantel-id') != draggedFromPlantel) {
                    var targetPlantelId = $(this).data('plantel-id');
                    var targetPlantel = planteles.find(p => p.id_pla == targetPlantelId);
                    
                    if(!$(this).hasClass('drop-zone')) {
                        console.log('🎯 PLANTEL DESTINO (delegado):', targetPlantel ? targetPlantel.nom_pla : 'Desconocido');
                        
                        // Actualizar mensaje con plantel destino
                        var nombreEjecutivo = draggedExecutivo ? draggedExecutivo.nom_eje : 'ejecutivo';
                        var nombrePlantelDestino = targetPlantel ? targetPlantel.nom_pla : 'plantel';
                        mostrarMensajeDragDrop(nombreEjecutivo + ' → ' + nombrePlantelDestino, false, false);
                        
                        $(this).addClass('plantel-destino-highlight');
                    }
                    $(this).addClass('drop-zone');
                }
            });
            
            $(document).on('drop.delegado', '.plantel-container', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Solo procesar si tenemos las variables necesarias
                if(!draggedNode || !draggedFromPlantel) {
                    return;
                }
                
                var targetPlantel = $(this).data('plantel-id');
                var plantelDestinoInfo = planteles.find(p => p.id_pla == targetPlantel);
                
                console.log('📍 DROP DELEGADO EN PLANTEL:', plantelDestinoInfo ? plantelDestinoInfo.nom_pla : 'Desconocido');
                $(this).removeClass('drop-zone plantel-destino-highlight');
                
                if(targetPlantel && targetPlantel != draggedFromPlantel && draggedExecutivo) {
                    console.log('👤 EJECUTIVO MOVIDO A (delegado):', plantelDestinoInfo.nom_pla);
                    
                    // Resetear variables antes de mover
                    var tempEjecutivoId = draggedNode;
                    var tempTargetPlantel = targetPlantel;
                    
                    draggedNode = null;
                    draggedFromPlantel = null;
                    draggedExecutivo = null;
                    
                    // Mover ejecutivo a nuevo plantel
                    moverEjecutivo(tempEjecutivoId, null, tempTargetPlantel);
                }
            });
        }
        
        function moverEjecutivo(ejecutivoId, nuevoPadreId, nuevoPlantelId) {
            console.log('=== FUNCIÓN MOVER EJECUTIVO ===');
            console.log('moverEjecutivo llamada con:', {
                ejecutivoId: ejecutivoId,
                nuevoPadreId: nuevoPadreId,
                nuevoPlantelId: nuevoPlantelId
            });
            
            // Validar que tenemos un ID válido
            if(!ejecutivoId || ejecutivoId === '') {
                console.error('ID de ejecutivo no válido:', ejecutivoId);
                mostrarMensajeDragDrop('Error: ID de ejecutivo no válido', false, true);
                return;
            }
            
            // Validar que tenemos un plantel válido
            if(!nuevoPlantelId || nuevoPlantelId === '') {
                console.error('ID de plantel no válido:', nuevoPlantelId);
                mostrarMensajeDragDrop('Error: ID de plantel no válido', false, true);
                return;
            }
            
            // Buscar información del ejecutivo para validación
            var ejecutivo = ejecutivos.find(ej => ej.id_eje == ejecutivoId);
            if(!ejecutivo) {
                console.error('No se encontró el ejecutivo con ID:', ejecutivoId);
                mostrarMensajeDragDrop('Error: No se encontró el ejecutivo', false, true);
                return;
            }
            
            // Buscar información del plantel destino
            var plantelDestino = planteles.find(p => p.id_pla == nuevoPlantelId);
            if(!plantelDestino) {
                console.error('No se encontró el plantel con ID:', nuevoPlantelId);
                mostrarMensajeDragDrop('Error: No se encontró el plantel destino', false, true);
                return;
            }
            
            console.log('Validación exitosa - Moviendo:', {
                ejecutivo: ejecutivo.nom_eje,
                plantelAnterior: ejecutivo.id_pla,
                plantelDestino: plantelDestino.nom_pla
            });
            
            // === APLICAR CAMBIO VISUAL INSTANTÁNEO ===
            // Actualizar datos locales inmediatamente para feedback visual instantáneo
            var padreAnterior = ejecutivo.id_padre;
            var plantelAnterior = ejecutivo.id_pla;
            
            ejecutivo.id_padre = nuevoPadreId;
            ejecutivo.id_pla = nuevoPlantelId;
            
            // Regenerar vista inmediatamente sin delay
            generarArbolesPorPlantel();
            
            // Aplicar imágenes después de regenerar los árboles
            setTimeout(function() {
                aplicarImagenesEjecutivos();
            }, 100);
            
            setTimeout(function() {
                aplicarImagenesEjecutivos();
            }, 300);
            
            limpiarEstadoDrag();
            
            // Mostrar mensaje de éxito instantáneo y sutil
            mostrarMensajeDragDrop('✓ Movido', true, false);
            
            // === SINCRONIZAR CON SERVIDOR EN BACKGROUND ===
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: {
                    action: 'mover_ejecutivo',
                    id_eje: ejecutivoId,
                    id_padre: nuevoPadreId,
                    id_pla: nuevoPlantelId
                },
                dataType: 'json',
                success: function(response) {
                    console.log('=== RESPUESTA DEL SERVIDOR ===');
                    console.log('Respuesta completa:', response);
                    
                    if(response.success) {
                        console.log('Movimiento confirmado por servidor');
                        
                        // Enviar mensaje WebSocket de movimiento
                        enviarMensajeWebSocket('ejecutivo_movido', {
                            id_eje: ejecutivoId,
                            nuevo_padre: nuevoPadreId,
                            nuevo_plantel: nuevoPlantelId,
                            padre_anterior: padreAnterior,
                            plantel_anterior: plantelAnterior,
                            nombre_ejecutivo: ejecutivo.nom_eje
                        });
                        
                        // Recargar citas para actualizar conteos
                        cargarCitasPorPlantel();
                        
                        // Aplicar feedback visual de confirmación - INMEDIATO
                        aplicarFeedbackVisualEjecutivo(ejecutivoId, 'movimiento');
                    } else {
                        console.error('Error del servidor:', response.message);
                        
                        // Revertir cambios locales si el servidor falló
                        ejecutivo.id_padre = padreAnterior;
                        ejecutivo.id_pla = plantelAnterior;
                        generarArbolesPorPlantel();
                        
                        // Reaplicar imágenes después de revertir
                        setTimeout(function() {
                            aplicarImagenesEjecutivos();
                        }, 100);
                        
                        mostrarMensajeDragDrop('✗ Error: ' + response.message, false, true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== ERROR DE CONEXIÓN ===');
                    console.error('Error AJAX:', error);
                    console.error('Status:', status);
                    console.error('XHR readyState:', xhr.readyState);
                    console.error('XHR status:', xhr.status);
                    console.error('Respuesta completa:', xhr.responseText);
                    
                    // Revertir cambios locales por error de conexión
                    ejecutivo.id_padre = padreAnterior;
                    ejecutivo.id_pla = plantelAnterior;
                    generarArbolesPorPlantel();
                    
                    // Reaplicar imágenes después de revertir por error
                    setTimeout(function() {
                        aplicarImagenesEjecutivos();
                    }, 100);
                    
                    // Manejo de errores más robusto
                    var mensajeError = 'Error de conexión';
                    
                    if (xhr.responseText) {
                        try {
                            // Intentar extraer el JSON válido incluso si hay warnings PHP al inicio
                            var match = xhr.responseText.match(/\{.*\}$/);
                            if (match) {
                                var errorResponse = JSON.parse(match[0]);
                                if (errorResponse && errorResponse.message) {
                                    mensajeError = errorResponse.message;
                                }
                            } else {
                                // Si no hay JSON válido, mostrar un mensaje genérico
                                mensajeError = 'Error del servidor (respuesta malformada)';
                            }
                        } catch(e) {
                            console.error('No se pudo parsear la respuesta de error:', e);
                            // Verificar si contiene algún mensaje de error conocido
                            if (xhr.responseText.includes('404 Not Found')) {
                                mensajeError = 'Error de WebSocket (ignorado)';
                                console.warn('Error de WebSocket detectado, pero el movimiento puede haber sido exitoso');
                                // No revertir los cambios si es solo un error de WebSocket
                                ejecutivo.id_padre = nuevoPadreId;
                                ejecutivo.id_pla = nuevoPlantelId;
                                generarArbolesPorPlantel();
                                
                                // Reaplicar imágenes después de mantener cambios
                                setTimeout(function() {
                                    aplicarImagenesEjecutivos();
                                }, 100);
                                
                                mostrarMensajeDragDrop('✓ Movido', true, false);
                                return;
                            } else {
                                mensajeError = 'Error de conexión: ' + (error || 'Desconocido');
                            }
                        }
                    }
                    
                    mostrarMensajeDragDrop('✗ ' + mensajeError, false, true);
                }
            });
        }
        
        // =====================================
        // FUNCIONES DE INTERFAZ
        // =====================================
        
        function mostrarInformacionEjecutivo(ejecutivo) {
            var plantel = planteles.find(p => p.id_pla == ejecutivo.id_pla);
            var padre = ejecutivos.find(e => e.id_eje == ejecutivo.id_padre);
            
            // Información del semáforo de sesión
            var semaforoInfo = '';
            if (ejecutivo.semaforo_sesion) {
                var semaforoClass = ejecutivo.semaforo_sesion;
                var semaforoTexto = '';
                
                switch(semaforoClass) {
                    case 'verde':
                        semaforoTexto = 'Sesión reciente (≤1 día)';
                        break;
                    case 'amarillo':
                        semaforoTexto = 'Sesión moderada (2-3 días)';
                        break;
                    case 'rojo':
                        semaforoTexto = 'Sesión antigua (≥4 días)';
                        break;
                    case 'sin_sesion':
                        semaforoTexto = 'Sin registro de sesión';
                        break;
                }
                
                var ultimaSesion = ejecutivo.ult_eje ? new Date(ejecutivo.ult_eje).toLocaleString('es-ES') : 'Nunca';
                var diasDesde = ejecutivo.dias_desde_ultima_sesion !== null ? ejecutivo.dias_desde_ultima_sesion + ' día(s)' : 'N/A';
                
                semaforoInfo = `
                    <div class="col-12 mt-3">
                        <h6><i class="fas fa-traffic-light"></i> Estado de Sesión</h6>
                        <div class="d-flex align-items-center mb-2">
                            <span class="semaforo-sesion ${semaforoClass} mr-2"></span>
                            <span>${semaforoTexto}</span>
                        </div>
                        <small class="text-muted">
                            <strong>Última sesión:</strong> ${ultimaSesion}<br>
                            <strong>Tiempo transcurrido:</strong> ${diasDesde}
                        </small>
                    </div>
                `;
            }
            
            var html = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre:</strong> ${ejecutivo.nom_eje}<br>
                        <strong>Teléfono:</strong> ${ejecutivo.tel_eje}<br>
                        <strong>Plantel:</strong> ${plantel ? plantel.nom_pla : 'Sin plantel'}
                    </div>
                    <div class="col-md-6">
                        <strong>Jefe:</strong> ${padre ? padre.nom_eje : 'Sin jefe (Raíz)'}<br>
                        <strong>Estado:</strong> ${ejecutivo.eli_eje == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>'
                        }
                    </div>
                    ${semaforoInfo}
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary" onclick="mostrarModalEditar()">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-${ejecutivo.eli_eje == 1 ? 'warning' : 'success'}" onclick="toggleEstado()">
                        <i class="fas fa-${ejecutivo.eli_eje == 1 ? 'eye-slash' : 'eye'}"></i> 
                        ${ejecutivo.eli_eje == 1 ? 'Ocultar' : 'Mostrar'}
                    </button>
                </div>
            `;
            
            $('#ejecutivo-info').html(html);
            $('#info-panel').show();
        }
        
        // =====================================
        // P32 - CARD DE RESUMEN DE EJECUTIVO
        // =====================================
        
        function mostrarCardEjecutivo(ejecutivo, event) {
            // Ocultar cualquier card existente
            $('.ejecutivo-card').remove();
            
            // Buscar información del padre y plantel
            var padre = ejecutivos.find(e => e.id_eje == ejecutivo.id_padre);
            var nombrePadre = padre ? padre.nom_eje : 'Sin responsable (Raíz)';
            var plantel = planteles.find(p => p.id_pla == ejecutivo.id_pla);
            var nombrePlantel = plantel ? plantel.nom_pla : 'Sin plantel';
            
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
                            <span class="ejecutivo-card-label">Plantel:</span>
                            <span class="ejecutivo-card-value">${nombrePlantel}</span>
                        </div>
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
        
        function actualizarEstadisticas() {
            var total = ejecutivos.length;
            var activos = ejecutivos.filter(e => e.eli_eje == 1).length;
            var ocultos = ejecutivos.filter(e => e.eli_eje == 0).length;
            
            // Estadísticas del semáforo P15
            var verde = ejecutivos.filter(e => e.semaforo_sesion === 'verde').length;
            var amarillo = ejecutivos.filter(e => e.semaforo_sesion === 'amarillo').length;
            var rojo = ejecutivos.filter(e => e.semaforo_sesion === 'rojo').length;
            var sinSesion = ejecutivos.filter(e => e.semaforo_sesion === 'sin_sesion').length;
            
            var html = `
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="total-ejecutivos">${total}</div>
                    <div class="stat-label">Total Ejecutivos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number text-success" id="ejecutivos-activos">${activos}</div>
                    <div class="stat-label">Activos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number text-danger" id="ejecutivos-ocultos">${ocultos}</div>
                    <div class="stat-label">Ocultos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number text-info">${planteles.length}</div>
                    <div class="stat-label">Planteles</div>
                </div>
            `;
            
            // Agregar estadísticas del semáforo
            html += `
                <div class="col-12 mt-3">
                    <h6 class="text-center mb-3">Estado de Sesiones</h6>
                    <div class="stats-semaforo justify-content-center">
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion verde"></span>
                            <span>${verde} Verde</span>
                        </div>
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion amarillo"></span>
                            <span>${amarillo} Amarillo</span>
                        </div>
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion rojo"></span>
                            <span>${rojo} Rojo</span>
                        </div>
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion sin_sesion"></span>
                            <span>${sinSesion} Sin sesión</span>
                        </div>
                    </div>
                </div>
            `;
            
            $('#estadisticas').html(html);
        }
        
        function recargarTodos() {
            cargarEjecutivos().then(function() {
                cargarCitasPorPlantel(); // Actualizar conteo de citas por plantel
            });
        }
        
        // =====================================
        // FUNCIONES DE CRUD
        // =====================================
        
        function mostrarModalCrear() {
            modoEdicion = false;
            $('#modalTitulo').text('Crear Ejecutivo');
            $('#formEjecutivo')[0].reset();
            $('#ejecutivo_id').val('');
            $('#ejecutivo_activo').prop('checked', true);
            
            cargarSelectPadres();
            $('#modalEjecutivo').modal('show');
        }
        
        function mostrarModalEditar() {
            if(!nodoSeleccionado) {
                alert('Por favor selecciona un ejecutivo');
                return;
            }
            
            modoEdicion = true;
            $('#modalTitulo').text('Editar Ejecutivo');
            
            $('#ejecutivo_id').val(nodoSeleccionado.id_eje);
            $('#ejecutivo_nombre').val(nodoSeleccionado.nom_eje);
            $('#ejecutivo_telefono').val(nodoSeleccionado.tel_eje);
            $('#ejecutivo_plantel').val(nodoSeleccionado.id_pla);
            $('#ejecutivo_padre').val(nodoSeleccionado.id_padre);
            $('#ejecutivo_activo').prop('checked', nodoSeleccionado.eli_eje == 1);
            
            cargarSelectPadres(nodoSeleccionado.id_pla, nodoSeleccionado.id_eje);
            $('#modalEjecutivo').modal('show');
        }
        
        function cargarSelectPlanteles() {
            var html = '<option value="">Seleccione un plantel</option>';
            planteles.forEach(function(plantel) {
                html += `<option value="${plantel.id_pla}">${plantel.nom_pla}</option>`;
            });
            $('#ejecutivo_plantel').html(html);
        }
        
        function cargarSelectPadres(plantelSeleccionado = null, valorActual = null) {
            var html = '<option value="">Sin jefe (Raíz)</option>';
            
            var plantelId = plantelSeleccionado || $('#ejecutivo_plantel').val();
            
            if(plantelId) {
                var ejecutivosPlantel = ejecutivos.filter(e => e.id_pla == plantelId && e.eli_eje == 1);
                
                ejecutivosPlantel.forEach(function(ejecutivo) {
                    if(ejecutivo.id_eje != valorActual) {
                        html += `<option value="${ejecutivo.id_eje}">${ejecutivo.nom_eje}</option>`;
                    }
                });
            }
            
            $('#ejecutivo_padre').html(html);
        }
        
        // Evento para actualizar select de padres cuando cambia el plantel
        $('#ejecutivo_plantel').on('change', function() {
            cargarSelectPadres($(this).val(), $('#ejecutivo_id').val());
        });
        
        function guardarEjecutivo() {
            var formData = {
                action: modoEdicion ? 'actualizar_ejecutivo' : 'crear_ejecutivo',
                nom_eje: $('#ejecutivo_nombre').val(),
                tel_eje: $('#ejecutivo_telefono').val(),
                id_pla: $('#ejecutivo_plantel').val(),
                id_padre: $('#ejecutivo_padre').val() || null,
                eli_eje: $('#ejecutivo_activo').is(':checked') ? 1 : 0
            };
            
            if(modoEdicion) {
                formData.id_eje = $('#ejecutivo_id').val();
            }
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#modalEjecutivo').modal('hide');
                        recargarTodos();
                        
                        // Enviar mensaje WebSocket según el tipo de operación
                        if (modoEdicion) {
                            enviarMensajeWebSocket('ejecutivo_actualizado', {
                                id_eje: formData.id_eje,
                                datos: formData
                            });
                            mostrarBadgeWebSocket('success', 'Ejecutivo actualizado');
                        } else {
                            enviarMensajeWebSocket('ejecutivo_creado', {
                                id_eje: response.data.id_eje,
                                datos: formData
                            });
                            mostrarBadgeWebSocket('success', 'Ejecutivo creado');
                        }
                        
                        alert('Ejecutivo ' + (modoEdicion ? 'actualizado' : 'creado') + ' correctamente');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Error de conexión');
                }
            });
        }
        
        function toggleEstado() {
            if(!nodoSeleccionado) {
                alert('Por favor selecciona un ejecutivo');
                return;
            }
            
            var nuevoEstado = nodoSeleccionado.eli_eje == 1 ? 0 : 1;
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: {
                    action: 'toggle_estado_ejecutivo',
                    id_eje: nodoSeleccionado.id_eje,
                    eli_eje: nuevoEstado
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        recargarTodos();
                        $('#info-panel').hide();
                        
                        // Enviar mensaje WebSocket de cambio de estado
                        enviarMensajeWebSocket('ejecutivo_estado_cambiado', {
                            id_eje: nodoSeleccionado.id_eje,
                            nuevo_estado: nuevoEstado,
                            nombre_ejecutivo: nodoSeleccionado.nom_eje
                        });
                        
                        var estadoTexto = nuevoEstado == 1 ? 'activado' : 'desactivado';
                        mostrarBadgeWebSocket('info', 'Ejecutivo ' + estadoTexto);
                        
                        nodoSeleccionado = null;
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Error de conexión');
                }
            });
        }
        
        // =====================================
        // FUNCIONES AUXILIARES
        // =====================================
        
        function limpiarEstadoDrag() {
            // Limpiar todas las clases de drop-zone
            $('.plantel-container').removeClass('drop-zone');
            
            // Resetear variables globales
            draggedNode = null;
            draggedFromPlantel = null;
            draggedExecutivo = null;
            
            // Ocultar mensaje de estado
            $('#drag-status').hide();
            
            console.log('Estado de drag completamente limpiado');
        }
        
        function mostrarMensajeDragDrop(mensaje, exito, error) {
            var $status = $('#drag-status');
            $status.removeClass('success error');
            
            if(exito) {
                $status.addClass('success');
            } else if(error) {
                $status.addClass('error');
            }
            
            $status.text(mensaje).show();
            
            // Mensajes de éxito se ocultan muy rápido para movimientos instantáneos
            if(exito) {
                setTimeout(function() {
                    $status.fadeOut(150);
                }, 200); // Reducido de 800 a 200ms para ser más rápido
            } else if(error) {
                setTimeout(function() {
                    $status.fadeOut(300);
                }, 3000);
            }
            // Los mensajes de estado (no éxito ni error) se mantienen hasta el siguiente evento
        }
        
        // Variable para tracking de drag & drop entre planteles
        var draggedNode = null;
        var draggedFromPlantel = null;
        var draggedExecutivo = null;
        
        // Configurar eventos de drag & drop globales mejorados
        $(document).on('dnd_start.vakata', function(e, data) {
            console.log('=== INICIANDO DRAG ===');
            console.log('Evento completo:', e);
            console.log('Data completa:', data);
            
            // Obtener el nodo que se está arrastrando
            if(data.data && data.data.nodes && data.data.nodes.length > 0) {
                draggedNode = data.data.nodes[0];
                console.log('Nodo arrastrado:', draggedNode);
                
                // Buscar el ejecutivo en los datos
                draggedExecutivo = ejecutivos.find(ej => ej.id_eje == draggedNode);
                console.log('Ejecutivo encontrado:', draggedExecutivo);
                
                // Buscar el contenedor de plantel desde el cual se está arrastrando
                var sourceElement = $(data.element);
                var sourceTree = sourceElement.closest('.plantel-container');
                
                if(sourceTree.length > 0) {
                    draggedFromPlantel = sourceTree.data('plantel-id');
                    console.log('Arrastrando desde plantel:', draggedFromPlantel);
                    
                    // Resaltar zonas de drop (otros planteles)
                    $('.plantel-container').not('[data-plantel-id="' + draggedFromPlantel + '"]').addClass('drop-zone');
                    console.log('Zonas de drop resaltadas');
                    
                    // NO mostrar mensaje durante el arrastre para hacer el movimiento instantáneo
                    // El mensaje de éxito se mostrará solo cuando el movimiento se complete
                } else {
                    console.warn('No se pudo encontrar el contenedor de plantel origen');
                }
            } else {
                console.error('No se pudo obtener el nodo arrastrado');
            }
        });
        
        $(document).on('dnd_stop.vakata', function(e, data) {
            console.log('=== FINALIZANDO DRAG ===');
            
            // Limpiar inmediatamente sin delay
            limpiarEstadoDrag();
            console.log('Estado de drag limpiado desde dnd_stop');
            
            // Resetear variables inmediatamente
            draggedNode = null;
            draggedFromPlantel = null;
            draggedExecutivo = null;
            console.log('Variables de drag reseteadas');
        });
        
        // Mejorar la detección de drop en contenedores de planteles
        // (Estos listeners adicionales ayudan a capturar el drop si fallan los eventos internos)
        $(document).on('dragover', '.plantel-container', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if(draggedNode && $(this).data('plantel-id') != draggedFromPlantel) {
                $(this).addClass('drop-zone');
            }
        });
        
        $(document).on('dragleave', '.plantel-container', function(e) {
            // Solo remover la clase si realmente salimos del contenedor
            var rect = this.getBoundingClientRect();
            var x = e.originalEvent.clientX;
            var y = e.originalEvent.clientY;
            
            if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                $(this).removeClass('drop-zone');
            }
        });
        
        $(document).on('drop', '.plantel-container', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('=== DROP EVENT GLOBAL ===');
            
            var targetPlantel = $(this).data('plantel-id');
            console.log('Drop en plantel:', targetPlantel);
            console.log('Nodo arrastrado:', draggedNode);
            console.log('Plantel origen:', draggedFromPlantel);
            console.log('Ejecutivo arrastrado:', draggedExecutivo);
            
            // Limpiar TODAS las clases drop-zone inmediatamente
            $('.plantel-container').removeClass('drop-zone');
            
            if(draggedNode && draggedFromPlantel && targetPlantel != draggedFromPlantel) {
                var ejecutivoId = draggedNode;
                
                if(draggedExecutivo) {
                    console.log('Moviendo ejecutivo:', draggedExecutivo.nom_eje, 'ID:', ejecutivoId, 'del plantel:', draggedFromPlantel, 'al plantel:', targetPlantel);
                    
                    // Mover ejecutivo a nuevo plantel (sin padre - será raíz en el nuevo plantel)
                    moverEjecutivo(ejecutivoId, null, targetPlantel);
                } else {
                    console.error('No se encontró información del ejecutivo');
                    mostrarMensajeDragDrop('Error: No se encontró información del ejecutivo', false, true);
                }
            } else if(targetPlantel == draggedFromPlantel) {
                console.log('No se mueve porque es el mismo plantel');
            } else {
                console.log('Faltan datos para el movimiento:', {
                    draggedNode: draggedNode,
                    draggedFromPlantel: draggedFromPlantel,
                    targetPlantel: targetPlantel
                });
            }
            
            // Limpiar estado después del procesamiento - INMEDIATO
            limpiarEstadoDrag();
        });
        
        // =====================================
        // FUNCIONES DE WEBSOCKET
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
            url += '&origen=plantel';
            
            console.log('Navegando desde plantel a:', url);
            window.location.href = url;
        }
        
        function verDetallesCitasPlantel(idPlantel) {
            // Navegar al apartado de citas con filtro por plantel
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            var url = 'index.php?plantel=' + idPlantel;
            
            if (fechaInicio) {
                url += '&fecha_inicio=' + fechaInicio;
            }
            if (fechaFin) {
                url += '&fecha_fin=' + fechaFin;
            }
            
            url += '&tipo_conteo=plantel';
            url += '&origen=plantel';
            
            console.log('Navegando desde plantel completo a:', url);
            window.location.href = url;
        }
    </script>
</body>
</html>
