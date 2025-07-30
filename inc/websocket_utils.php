<?php

function notificarCambioPlantelEjecutivo($id_ejecutivo, $plantel_anterior, $plantel_nuevo, $nombre_ejecutivo, $connection = null) {
    // Obtener nombres de planteles para el mensaje
    $nombre_plantel_anterior = '';
    $nombre_plantel_nuevo = '';
    
    if ($connection) {
        if ($plantel_anterior) {
            $query = "SELECT nom_pla FROM plantel WHERE id_pla = $plantel_anterior";
            $result = mysqli_query($connection, $query);
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $nombre_plantel_anterior = $row['nom_pla'];
            }
        }
        
        if ($plantel_nuevo) {
            $query = "SELECT nom_pla FROM plantel WHERE id_pla = $plantel_nuevo";
            $result = mysqli_query($connection, $query);
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $nombre_plantel_nuevo = $row['nom_pla'];
            }
        }
    }
    
    $mensaje = [
        'tipo' => 'ejecutivo_cambio_plantel',
        'tabla' => 'ejecutivo',
        'accion' => 'cambio_plantel',
        'datos' => [
            'id_eje' => $id_ejecutivo,
            'nom_eje' => $nombre_ejecutivo,
            'plantel_anterior' => $plantel_anterior,
            'plantel_nuevo' => $plantel_nuevo,
            'nombre_plantel_anterior' => $nombre_plantel_anterior,
            'nombre_plantel_nuevo' => $nombre_plantel_nuevo,
            'nuevo_plantel' => $plantel_nuevo, // Para compatibilidad con código existente
            'mensaje' => "Ejecutivo $nombre_ejecutivo cambió del plantel " . ($nombre_plantel_anterior ?: "ID $plantel_anterior") . " al plantel " . ($nombre_plantel_nuevo ?: "ID $plantel_nuevo")
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}


function notificarActualizacionCitasPlantel($id_plantel, $nombre_plantel, $estadisticas) {
    $mensaje = [
        'tipo' => 'actualizacion_citas_plantel',
        'tabla' => 'plantel_citas',
        'accion' => 'actualizado',
        'datos' => [
            'id_pla' => $id_plantel,
            'nom_pla' => $nombre_plantel,
            'estadisticas' => $estadisticas,
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}


function notificarMovimientoEjecutivo($id_ejecutivo, $padre_anterior, $padre_nuevo, $nombre_ejecutivo) {
    $mensaje = [
        'tipo' => 'ejecutivo_movimiento',
        'tabla' => 'ejecutivo',
        'accion' => 'movido',
        'datos' => [
            'id_eje' => $id_ejecutivo,
            'nom_eje' => $nombre_ejecutivo,
            'padre_anterior' => $padre_anterior,
            'nuevo_padre' => $padre_nuevo,
            'mensaje' => "Ejecutivo $nombre_ejecutivo fue movido en la jerarquía"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

function notificarCambioPlantelCita($id_cita, $plantel_anterior, $plantel_nuevo, $motivo = null) {
    $mensaje = [
        'tipo' => 'cita_cambio_plantel',
        'tabla' => 'cita',
        'accion' => 'cambio_plantel',
        'datos' => [
            'id_cit' => $id_cita,
            'plantel_anterior' => $plantel_anterior,
            'plantel_nuevo' => $plantel_nuevo,
            'motivo' => $motivo,
            'mensaje' => "Cita #$id_cita migrada del plantel $plantel_anterior al plantel $plantel_nuevo"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}


function notificarDisociacionCitaEjecutivo($id_cita, $ejecutivo_anterior, $motivo = null) {
    $mensaje = [
        'tipo' => 'cita_disociacion',
        'tabla' => 'cita',
        'accion' => 'disociada',
        'datos' => [
            'id_cit' => $id_cita,
            'ejecutivo_anterior' => $ejecutivo_anterior,
            'motivo' => $motivo,
            'mensaje' => "Cita #$id_cita fue disociada del ejecutivo #$ejecutivo_anterior"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

function obtenerEstadisticasPlantel($id_plantel, $connection) {
    $query = "SELECT 
                COUNT(*) as total_citas,
                COUNT(CASE WHEN eli_cit = 1 THEN 1 END) as citas_activas,
                COUNT(CASE WHEN id_eje2 IS NOT NULL THEN 1 END) as citas_con_ejecutivo,
                COUNT(CASE WHEN id_eje2 IS NULL THEN 1 END) as citas_sin_ejecutivo,
                COUNT(CASE WHEN DATE(cit_cit) = CURDATE() THEN 1 END) as citas_hoy
              FROM cita 
              WHERE pla_cit = $id_plantel";
              
    $result = mysqli_query($connection, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    
    return [
        'total_citas' => 0,
        'citas_activas' => 0,
        'citas_con_ejecutivo' => 0,
        'citas_sin_ejecutivo' => 0,
        'citas_hoy' => 0
    ];
}

?>
