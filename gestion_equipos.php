<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 38 - Gestión de Equipos</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Estilos principales siguiendo el patrón de los otros módulos */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 20px 0;
        }
        
        .header-section {
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .header-section h3 {
            color: #007bff;
            font-weight: bold;
            margin: 0;
        }
        
        .header-section small {
            color: #6c757d;
        }
        
        /* Navegación de pestañas */
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            background: none;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        
        .nav-tabs .nav-link:hover {
            background-color: #e9ecef;
            border-radius: 5px;
        }
        
        /* Cards de equipos */
        .equipo-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            background: white;
        }
        
        .equipo-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .equipo-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            position: relative;
        }
        
        .equipo-emoji {
            font-size: 2rem;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .equipo-body {
            padding: 15px;
        }
        
        .equipo-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        /* Tabla de miembros */
        .miembros-table {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
        }
        
        .badge-responsable {
            background-color: #28a745;
        }
        
        .badge-miembro {
            background-color: #17a2b8;
        }
        
        /* Botones de acción */
        .btn-equipo {
            margin: 2px;
            font-size: 0.85rem;
        }
        
        /* Modal styles */
        .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        
        .modal-header .close {
            color: white;
            opacity: 0.8;
        }
        
        .modal-header .close:hover {
            opacity: 1;
        }
        
        /* Formularios */
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* Estados de equipo */
        .equipo-activo {
            border-left: 4px solid #28a745;
        }
        
        .equipo-inactivo {
            border-left: 4px solid #dc3545;
            opacity: 0.7;
        }
        
        /* Vista de ejecutivos con equipos */
        .ejecutivo-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background: white;
        }
        
        .ejecutivo-foto {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        
        .equipos-badges {
            margin-top: 5px;
        }
        
        .equipo-badge {
            margin-right: 5px;
            margin-bottom: 3px;
        }
        
        /* Emoji picker simple */
        .emoji-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        
        .emoji-option {
            font-size: 1.5rem;
            padding: 5px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .emoji-option:hover,
        .emoji-option.selected {
            background-color: #007bff;
            color: white;
            transform: scale(1.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .equipo-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .main-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header-section">
                <h3><i class="fas fa-users"></i> Gestión de Equipos</h3>
                <small>Sistema de equipos transversales a planteles - Práctica 38</small>
            </div>
            
            <!-- Navegación por pestañas -->
            <ul class="nav nav-tabs" id="equiposTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="equipos-tab" data-toggle="tab" href="#equipos" role="tab">
                        <i class="fas fa-users-cog"></i> Gestión de Equipos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vista-tab" data-toggle="tab" href="#vista" role="tab">
                        <i class="fas fa-eye"></i> Vista por Equipos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="ejecutivos-tab" data-toggle="tab" href="#ejecutivos" role="tab">
                        <i class="fas fa-user-friends"></i> Ejecutivos y Equipos
                    </a>
                </li>
            </ul>
            
            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="equiposTabContent">
                
                <!-- Pestaña: Gestión de Equipos -->
                <div class="tab-pane fade show active" id="equipos" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h5><i class="fas fa-users-cog"></i> Equipos Registrados</h5>
                        <button class="btn btn-primary" onclick="mostrarModalEquipo()">
                            <i class="fas fa-plus"></i> Nuevo Equipo
                        </button>
                    </div>
                    
                    <div id="equipos-container" class="row">
                        <!-- Los equipos se cargarán aquí dinámicamente -->
                    </div>
                </div>
                
                <!-- Pestaña: Vista por Equipos -->
                <div class="tab-pane fade" id="vista" role="tabpanel">
                    <div class="mt-3">
                        <h5><i class="fas fa-eye"></i> Vista Jerárquica por Equipos</h5>
                        <div id="vista-equipos-container">
                            <!-- La vista jerárquica se cargará aquí -->
                        </div>
                    </div>
                </div>
                
                <!-- Pestaña: Ejecutivos y Equipos -->
                <div class="tab-pane fade" id="ejecutivos" role="tabpanel">
                    <div class="mt-3">
                        <h5><i class="fas fa-user-friends"></i> Todos los Ejecutivos con sus Equipos</h5>
                        <div id="ejecutivos-equipos-container">
                            <!-- Lista de ejecutivos con sus equipos se cargará aquí -->
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Equipo -->
    <div class="modal fade" id="modalEquipo" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-users-cog"></i> <span id="modal-title">Crear Equipo</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formEquipo">
                    <div class="modal-body">
                        <input type="hidden" id="equipo-id" name="id_equipo">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="equipo-nombre">Nombre del Equipo *</label>
                                    <input type="text" class="form-control" id="equipo-nombre" name="nom_equipo" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="equipo-estado">Estado</label>
                                    <select class="form-control" id="equipo-estado" name="activo_equipo">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="equipo-descripcion">Descripción</label>
                            <textarea class="form-control" id="equipo-descripcion" name="des_equipo" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Emoji Característico</label>
                            <input type="hidden" id="equipo-emoji" name="emoji_equipo" value="🔵">
                            <div class="emoji-picker">
                                <span class="emoji-option selected" data-emoji="🔵">🔵</span>
                                <span class="emoji-option" data-emoji="🔴">🔴</span>
                                <span class="emoji-option" data-emoji="🟢">🟢</span>
                                <span class="emoji-option" data-emoji="🟡">🟡</span>
                                <span class="emoji-option" data-emoji="🟠">🟠</span>
                                <span class="emoji-option" data-emoji="🟣">🟣</span>
                                <span class="emoji-option" data-emoji="⚡">⚡</span>
                                <span class="emoji-option" data-emoji="🚀">🚀</span>
                                <span class="emoji-option" data-emoji="⭐">⭐</span>
                                <span class="emoji-option" data-emoji="🎯">🎯</span>
                                <span class="emoji-option" data-emoji="🏆">🏆</span>
                                <span class="emoji-option" data-emoji="💎">💎</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Gestionar Miembros del Equipo -->
    <div class="modal fade" id="modalMiembros" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-friends"></i> Gestionar Miembros: <span id="modal-equipo-nombre"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-equipo-id">
                    
                    <div class="row">
                        <!-- Miembros actuales -->
                        <div class="col-md-6">
                            <h6><i class="fas fa-users"></i> Miembros Actuales</h6>
                            <div id="miembros-actuales">
                                <!-- Se carga dinámicamente -->
                            </div>
                        </div>
                        
                        <!-- Agregar nuevos miembros -->
                        <div class="col-md-6">
                            <h6><i class="fas fa-user-plus"></i> Agregar Miembro</h6>
                            <form id="formAgregarMiembro">
                                <div class="form-group">
                                    <label for="nuevo-ejecutivo">Ejecutivo</label>
                                    <select class="form-control" id="nuevo-ejecutivo" required>
                                        <option value="">Seleccionar ejecutivo...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nuevo-rol">Rol en el Equipo</label>
                                    <select class="form-control" id="nuevo-rol">
                                        <option value="0">Miembro</option>
                                        <option value="1">Responsable/Líder</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nuevas-notas">Notas (opcional)</label>
                                    <input type="text" class="form-control" id="nuevas-notas" placeholder="Ej: Especialista en ventas">
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Agregar al Equipo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let equipos = [];
        let ejecutivos = [];
        let equipoActual = null;
        
        // Inicialización
        $(document).ready(function() {
            inicializarEventos();
            cargarEquipos();
            cargarEjecutivos();
            
            // Configurar cambio de pestañas
            $('#equiposTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
                
                // Cargar contenido específico de cada pestaña
                let target = $(this).attr('href');
                if (target === '#vista') {
                    cargarVistaEquipos();
                } else if (target === '#ejecutivos') {
                    cargarEjecutivosEquipos();
                }
            });
        });
        
        // =====================================
        // INICIALIZACIÓN DE EVENTOS
        // =====================================
        
        function inicializarEventos() {
            // Evento para seleccionar emoji
            $(document).on('click', '.emoji-option', function() {
                $('.emoji-option').removeClass('selected');
                $(this).addClass('selected');
                $('#equipo-emoji').val($(this).data('emoji'));
            });
            
            // Formulario de equipo
            $('#formEquipo').on('submit', function(e) {
                e.preventDefault();
                guardarEquipo();
            });
            
            // Formulario agregar miembro
            $('#formAgregarMiembro').on('submit', function(e) {
                e.preventDefault();
                agregarMiembroEquipo();
            });
        }
        
        // =====================================
        // FUNCIONES DE COMUNICACIÓN CON SERVIDOR
        // =====================================
        
        function llamarServidor(action, data = {}, callback = null, errorCallback = null) {
            data.action = action;
            
            $.ajax({
                url: 'server/controlador_equipos.php',
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
                            mostrarNotificacion(response.message, 'error');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error de conexión:', error);
                    console.error('Respuesta del servidor:', xhr.responseText);
                    if (errorCallback) {
                        errorCallback('Error de conexión: ' + error);
                    } else {
                        mostrarNotificacion('Error de conexión: ' + error, 'error');
                    }
                }
            });
        }
        
        // =====================================
        // FUNCIONES DE CARGA DE DATOS
        // =====================================
        
        function cargarEquipos() {
            llamarServidor('obtener_equipos', {}, function(data, message) {
                equipos = data;
                mostrarEquipos();
            });
        }
        
        function cargarEjecutivos() {
            llamarServidor('obtener_ejecutivos_disponibles', {}, function(data, message) {
                ejecutivos = data;
                actualizarSelectEjecutivos();
            });
        }
        
        function cargarVistaEquipos() {
            llamarServidor('obtener_vista_equipos', {}, function(data, message) {
                mostrarVistaEquipos(data);
            });
        }
        
        function cargarEjecutivosEquipos() {
            llamarServidor('obtener_vista_equipos', {}, function(data, message) {
                mostrarEjecutivosEquipos(data);
            });
        }
        
        // =====================================
        // FUNCIONES DE VISUALIZACIÓN
        // =====================================
        
        function mostrarEquipos() {
            let html = '';
            
            if (equipos.length === 0) {
                html = `
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> No hay equipos registrados.
                            <br><button class="btn btn-primary mt-2" onclick="mostrarModalEquipo()">
                                <i class="fas fa-plus"></i> Crear primer equipo
                            </button>
                        </div>
                    </div>
                `;
            } else {
                equipos.forEach(function(equipo) {
                    let estadoClass = equipo.activo_equipo == 1 ? 'equipo-activo' : 'equipo-inactivo';
                    let estadoBadge = equipo.activo_equipo == 1 ? 
                        '<span class="badge badge-success">Activo</span>' : 
                        '<span class="badge badge-secondary">Inactivo</span>';
                    
                    html += `
                        <div class="col-md-6 col-lg-4">
                            <div class="equipo-card ${estadoClass}">
                                <div class="equipo-header">
                                    <h6 class="mb-1">${equipo.nom_equipo}</h6>
                                    <small>${estadoBadge}</small>
                                    <div class="equipo-emoji">${equipo.emoji_equipo}</div>
                                </div>
                                <div class="equipo-body">
                                    <p class="text-muted small">${equipo.des_equipo || 'Sin descripción'}</p>
                                    
                                    <div class="equipo-stats">
                                        <div class="stat-item">
                                            <div class="stat-number">${equipo.total_miembros || 0}</div>
                                            <div class="stat-label">Miembros</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number">${equipo.total_responsables || 0}</div>
                                            <div class="stat-label">Líderes</div>
                                        </div>
                                    </div>
                                    
                                    ${equipo.responsables ? `
                                        <div class="mb-2">
                                            <small class="text-muted">Responsables:</small><br>
                                            <small>${equipo.responsables}</small>
                                        </div>
                                    ` : ''}
                                    
                                    <div class="text-center">
                                        <button class="btn btn-sm btn-outline-primary btn-equipo" onclick="gestionarMiembros(${equipo.id_equipo})">
                                            <i class="fas fa-users"></i> Miembros
                                        </button>
                                        <button class="btn btn-sm btn-outline-info btn-equipo" onclick="editarEquipo(${equipo.id_equipo})">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-equipo" onclick="confirmarEliminarEquipo(${equipo.id_equipo})">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            $('#equipos-container').html(html);
        }
        
        function mostrarVistaEquipos(data) {
            let html = '';
            let equiposAgrupados = {};
            
            // Agrupar por equipo
            data.forEach(function(item) {
                if (item.id_equipo) {
                    if (!equiposAgrupados[item.id_equipo]) {
                        equiposAgrupados[item.id_equipo] = {
                            equipo: item,
                            miembros: []
                        };
                    }
                    equiposAgrupados[item.id_equipo].miembros.push(item);
                }
            });
            
            // Generar HTML
            if (Object.keys(equiposAgrupados).length === 0) {
                html = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay equipos con miembros para mostrar.
                    </div>
                `;
            } else {
                for (let equipoId in equiposAgrupados) {
                    let grupo = equiposAgrupados[equipoId];
                    let equipo = grupo.equipo;
                    
                    html += `
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    ${equipo.emoji_equipo} ${equipo.nom_equipo}
                                    <span class="badge badge-light ml-2">${grupo.miembros.length} miembros</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                    `;
                    
                    grupo.miembros.forEach(function(miembro) {
                        let rolBadge = miembro.es_responsable == 1 ? 
                            '<span class="badge badge-success">Responsable</span>' :
                            '<span class="badge badge-info">Miembro</span>';
                        
                        let foto = miembro.fot_eje ? 
                            `<img src="uploads/${miembro.fot_eje}" class="ejecutivo-foto" alt="Foto">` :
                            `<div class="ejecutivo-foto bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-user"></i>
                            </div>`;
                        
                        html += `
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    ${foto}
                                    <div>
                                        <strong>${miembro.nom_eje}</strong>
                                        ${rolBadge}
                                        <br><small class="text-muted">${miembro.nom_pla || 'Sin plantel'}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            $('#vista-equipos-container').html(html);
        }
        
        function mostrarEjecutivosEquipos(data) {
            let html = '';
            let ejecutivosAgrupados = {};
            
            // Agrupar por ejecutivo
            data.forEach(function(item) {
                if (!ejecutivosAgrupados[item.id_eje]) {
                    ejecutivosAgrupados[item.id_eje] = {
                        ejecutivo: item,
                        equipos: []
                    };
                }
                if (item.id_equipo) {
                    ejecutivosAgrupados[item.id_eje].equipos.push(item);
                }
            });
            
            // Generar HTML
            for (let ejecutivoId in ejecutivosAgrupados) {
                let grupo = ejecutivosAgrupados[ejecutivoId];
                let ejecutivo = grupo.ejecutivo;
                
                let foto = ejecutivo.fot_eje ? 
                    `<img src="uploads/${ejecutivo.fot_eje}" class="ejecutivo-foto" alt="Foto">` :
                    `<div class="ejecutivo-foto bg-secondary d-flex align-items-center justify-content-center text-white">
                        <i class="fas fa-user"></i>
                    </div>`;
                
                html += `
                    <div class="ejecutivo-item">
                        <div class="d-flex align-items-center">
                            ${foto}
                            <div class="flex-grow-1">
                                <strong>${ejecutivo.nom_eje}</strong>
                                <br><small class="text-muted">${ejecutivo.nom_pla || 'Sin plantel'}</small>
                                <div class="equipos-badges">
                `;
                
                if (grupo.equipos.length > 0) {
                    grupo.equipos.forEach(function(equipoInfo) {
                        let rolClass = equipoInfo.es_responsable == 1 ? 'badge-success' : 'badge-info';
                        let rolTexto = equipoInfo.es_responsable == 1 ? 'Responsable' : 'Miembro';
                        
                        html += `
                            <span class="badge ${rolClass} equipo-badge">
                                ${equipoInfo.emoji_equipo} ${equipoInfo.nom_equipo} (${rolTexto})
                            </span>
                        `;
                    });
                } else {
                    html += '<span class="badge badge-secondary">Sin equipos asignados</span>';
                }
                
                html += `
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            $('#ejecutivos-equipos-container').html(html);
        }
        
        // =====================================
        // FUNCIONES DE GESTIÓN DE EQUIPOS
        // =====================================
        
        function mostrarModalEquipo(id = null) {
            if (id) {
                // Editar equipo existente
                let equipo = equipos.find(e => e.id_equipo == id);
                if (equipo) {
                    $('#modal-title').text('Editar Equipo');
                    $('#equipo-id').val(equipo.id_equipo);
                    $('#equipo-nombre').val(equipo.nom_equipo);
                    $('#equipo-descripcion').val(equipo.des_equipo);
                    $('#equipo-estado').val(equipo.activo_equipo);
                    $('#equipo-emoji').val(equipo.emoji_equipo);
                    
                    // Seleccionar emoji visual
                    $('.emoji-option').removeClass('selected');
                    $(`.emoji-option[data-emoji="${equipo.emoji_equipo}"]`).addClass('selected');
                }
            } else {
                // Crear nuevo equipo
                $('#modal-title').text('Crear Equipo');
                $('#formEquipo')[0].reset();
                $('#equipo-id').val('');
                $('#equipo-emoji').val('🔵');
                $('.emoji-option').removeClass('selected');
                $('.emoji-option[data-emoji="🔵"]').addClass('selected');
            }
            
            $('#modalEquipo').modal('show');
        }
        
        function guardarEquipo() {
            let formData = new FormData($('#formEquipo')[0]);
            let action = $('#equipo-id').val() ? 'actualizar_equipo' : 'crear_equipo';
            
            // Convertir FormData a objeto simple para llamarServidor
            let data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            // Agregar ID del ejecutivo creador (por ahora usuario 1)
            if (!data.id_equipo) {
                data.id_eje_creador = 1;
            }
            
            llamarServidor(action, data, function(responseData, message) {
                mostrarNotificacion(message, 'success');
                $('#modalEquipo').modal('hide');
                cargarEquipos();
            });
        }
        
        function editarEquipo(id) {
            mostrarModalEquipo(id);
        }
        
        function confirmarEliminarEquipo(id) {
            let equipo = equipos.find(e => e.id_equipo == id);
            if (equipo) {
                if (confirm(`¿Está seguro de que desea eliminar el equipo "${equipo.nom_equipo}"?\n\nEsta acción también eliminará todas las relaciones de miembros del equipo.`)) {
                    eliminarEquipo(id);
                }
            }
        }
        
        function eliminarEquipo(id) {
            llamarServidor('eliminar_equipo', {id_equipo: id}, function(data, message) {
                mostrarNotificacion(message, 'success');
                cargarEquipos();
            });
        }
        
        // =====================================
        // FUNCIONES DE GESTIÓN DE MIEMBROS
        // =====================================
        
        function gestionarMiembros(equipoId) {
            equipoActual = equipos.find(e => e.id_equipo == equipoId);
            if (!equipoActual) return;
            
            $('#modal-equipo-id').val(equipoId);
            $('#modal-equipo-nombre').text(equipoActual.nom_equipo);
            
            // Cargar miembros actuales
            cargarMiembrosEquipo(equipoId);
            
            // Cargar ejecutivos disponibles
            cargarEjecutivosDisponibles(equipoId);
            
            $('#modalMiembros').modal('show');
        }
        
        function cargarMiembrosEquipo(equipoId) {
            llamarServidor('obtener_ejecutivos_equipo', {id_equipo: equipoId}, function(data, message) {
                let html = '';
                
                if (data.length === 0) {
                    html = '<div class="alert alert-info">No hay miembros en este equipo.</div>';
                } else {
                    html = '<div class="table-responsive"><table class="table table-sm">';
                    html += '<thead><tr><th>Ejecutivo</th><th>Plantel</th><th>Rol</th><th>Acciones</th></tr></thead><tbody>';
                    
                    data.forEach(function(miembro) {
                        let rolBadge = miembro.es_responsable == 1 ? 
                            '<span class="badge badge-responsable">Responsable</span>' :
                            '<span class="badge badge-miembro">Miembro</span>';
                        
                        html += `
                            <tr>
                                <td>
                                    ${miembro.fot_eje ? `<img src="uploads/${miembro.fot_eje}" class="rounded-circle" style="width:25px;height:25px;margin-right:5px;">` : ''}
                                    ${miembro.nom_eje}
                                </td>
                                <td>${miembro.nom_pla || 'Sin plantel'}</td>
                                <td>${rolBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning" onclick="cambiarRol(${miembro.id_eje}, ${equipoId}, ${1 - miembro.es_responsable})">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removerMiembro(${miembro.id_eje}, ${equipoId})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += '</tbody></table></div>';
                }
                
                $('#miembros-actuales').html(html);
            });
        }
        
        function cargarEjecutivosDisponibles(equipoId) {
            llamarServidor('obtener_ejecutivos_disponibles', {id_equipo: equipoId}, function(data, message) {
                let options = '<option value="">Seleccionar ejecutivo...</option>';
                
                data.forEach(function(ejecutivo) {
                    options += `<option value="${ejecutivo.id_eje}">${ejecutivo.nom_eje} - ${ejecutivo.nom_pla || 'Sin plantel'}</option>`;
                });
                
                $('#nuevo-ejecutivo').html(options);
            });
        }
        
        function agregarMiembroEquipo() {
            let equipoId = $('#modal-equipo-id').val();
            let ejecutivoId = $('#nuevo-ejecutivo').val();
            let esResponsable = $('#nuevo-rol').val();
            let notas = $('#nuevas-notas').val();
            
            if (!ejecutivoId) {
                mostrarNotificacion('Debe seleccionar un ejecutivo', 'error');
                return;
            }
            
            llamarServidor('agregar_ejecutivo_equipo', {
                id_eje: ejecutivoId,
                id_equipo: equipoId,
                es_responsable: esResponsable,
                notas: notas
            }, function(data, message) {
                mostrarNotificacion(message, 'success');
                
                // Limpiar formulario
                $('#formAgregarMiembro')[0].reset();
                
                // Recargar listas
                cargarMiembrosEquipo(equipoId);
                cargarEjecutivosDisponibles(equipoId);
                cargarEquipos(); // Para actualizar contadores
            });
        }
        
        function removerMiembro(ejecutivoId, equipoId) {
            if (confirm('¿Está seguro de que desea remover este miembro del equipo?')) {
                llamarServidor('remover_ejecutivo_equipo', {
                    id_eje: ejecutivoId,
                    id_equipo: equipoId
                }, function(data, message) {
                    mostrarNotificacion(message, 'success');
                    cargarMiembrosEquipo(equipoId);
                    cargarEjecutivosDisponibles(equipoId);
                    cargarEquipos(); // Para actualizar contadores
                });
            }
        }
        
        function cambiarRol(ejecutivoId, equipoId, nuevoRol) {
            let rolTexto = nuevoRol == 1 ? 'Responsable' : 'Miembro';
            
            if (confirm(`¿Cambiar el rol de este ejecutivo a "${rolTexto}"?`)) {
                llamarServidor('cambiar_rol_ejecutivo', {
                    id_eje: ejecutivoId,
                    id_equipo: equipoId,
                    es_responsable: nuevoRol
                }, function(data, message) {
                    mostrarNotificacion(message, 'success');
                    cargarMiembrosEquipo(equipoId);
                    cargarEquipos(); // Para actualizar contadores
                });
            }
        }
        
        // =====================================
        // FUNCIONES AUXILIARES
        // =====================================
        
        function actualizarSelectEjecutivos() {
            let options = '<option value="">Seleccionar ejecutivo...</option>';
            
            ejecutivos.forEach(function(ejecutivo) {
                options += `<option value="${ejecutivo.id_eje}">${ejecutivo.nom_eje} - ${ejecutivo.nom_pla || 'Sin plantel'}</option>`;
            });
            
            $('#nuevo-ejecutivo').html(options);
        }
        
        function mostrarNotificacion(mensaje, tipo = 'info') {
            let alertClass = 'alert-info';
            let icono = 'fas fa-info-circle';
            
            switch(tipo) {
                case 'success':
                    alertClass = 'alert-success';
                    icono = 'fas fa-check-circle';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    icono = 'fas fa-exclamation-circle';
                    break;
                case 'warning':
                    alertClass = 'alert-warning';
                    icono = 'fas fa-exclamation-triangle';
                    break;
            }
            
            let notification = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="${icono}"></i> ${mensaje}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            `;
            
            $('body').append(notification);
            
            // Auto-remover después de 5 segundos
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }
    </script>
</body>
</html>
