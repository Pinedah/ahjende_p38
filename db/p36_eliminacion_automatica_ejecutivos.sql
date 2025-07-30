-- =====================================================
-- PRÁCTICA 36: ELIMINACIÓN AUTOMÁTICA DE EJECUTIVOS INACTIVOS
-- =====================================================
-- Script para MySQL/MariaDB que implementa eliminación automática
-- de ejecutivos que lleven 7 días en semáforo rojo (11+ días sin sesión)
-- =====================================================

-- Paso 1: Crear el Stored Procedure
-- Este procedimiento busca ejecutivos con más de 11 días sin sesión
-- y los marca como eliminados (eli_eje = 0) usando borrado lógico

DELIMITER //

DROP PROCEDURE IF EXISTS sp_eliminar_ejecutivos_inactivos//

CREATE PROCEDURE sp_eliminar_ejecutivos_inactivos()
BEGIN
    DECLARE ejecutivos_eliminados INT DEFAULT 0;
    DECLARE log_message TEXT;
    
    -- Contar cuántos ejecutivos serán afectados antes de la eliminación
    SELECT COUNT(*) INTO ejecutivos_eliminados
    FROM ejecutivo 
    WHERE eli_eje = 1 
      AND ult_eje IS NOT NULL 
      AND TIMESTAMPDIFF(DAY, ult_eje, NOW()) >= 11;
    
    -- Realizar la eliminación lógica
    UPDATE ejecutivo 
    SET eli_eje = 0, ult_eje = NULL
    WHERE eli_eje = 1 
      AND ult_eje IS NOT NULL 
      AND TIMESTAMPDIFF(DAY, ult_eje, NOW()) >= 11;
    
    -- Preparar mensaje de log
    SET log_message = CONCAT(
        'P36 - Ejecutivos eliminados automáticamente: ', 
        ejecutivos_eliminados, 
        ' - Fecha: ', 
        NOW()
    );
    
    -- Insertar log en una tabla de auditoría (opcional)
    -- Si no tienes tabla de logs, puedes comentar esta línea
    -- INSERT INTO log_sistema (mensaje, fecha) VALUES (log_message, NOW());
    
    -- Retornar información del proceso
    SELECT 
        ejecutivos_eliminados as 'Ejecutivos_Eliminados',
        NOW() as 'Fecha_Ejecucion',
        'P36 - Eliminación automática ejecutivos inactivos' as 'Proceso';
        
END//

DELIMITER ;

-- =====================================================
-- Paso 2: Crear el EVENT de MySQL
-- Este evento se ejecutará cada 24 horas a las 02:00 AM
-- =====================================================

-- Verificar que el Event Scheduler esté habilitado
SET GLOBAL event_scheduler = ON;

-- Eliminar el evento si ya existe
DROP EVENT IF EXISTS evt_eliminar_ejecutivos_inactivos;

-- Crear el evento
CREATE EVENT evt_eliminar_ejecutivos_inactivos
ON SCHEDULE 
    EVERY 1 DAY 
    STARTS '2025-07-26 02:00:00'  -- Inicia mañana a las 2:00 AM
    ON COMPLETION PRESERVE
    ENABLE
    COMMENT 'P36 - Eliminación automática de ejecutivos inactivos cada 24hrs'
DO
    CALL sp_eliminar_ejecutivos_inactivos();

-- =====================================================
-- Paso 3: Verificaciones y pruebas
-- =====================================================

-- Verificar que el evento fue creado correctamente
SELECT 
    EVENT_NAME,
    EVENT_DEFINITION,
    INTERVAL_VALUE,
    INTERVAL_FIELD,
    STARTS,
    STATUS,
    EVENT_COMMENT
FROM information_schema.EVENTS 
WHERE EVENT_SCHEMA = DATABASE() 
  AND EVENT_NAME = 'evt_eliminar_ejecutivos_inactivos';

-- Verificar estado del Event Scheduler
SHOW VARIABLES LIKE 'event_scheduler';

-- =====================================================
-- Paso 4: Consulta para revisar ejecutivos candidatos a eliminación
-- =====================================================

-- Esta consulta te muestra qué ejecutivos serían eliminados actualmente
SELECT 
    id_eje,
    nom_eje,
    ult_eje,
    TIMESTAMPDIFF(DAY, ult_eje, NOW()) as dias_sin_sesion,
    CASE 
        WHEN ult_eje IS NULL THEN 'sin_sesion'
        WHEN TIMESTAMPDIFF(DAY, ult_eje, NOW()) <= 4 THEN 'verde'
        WHEN TIMESTAMPDIFF(DAY, ult_eje, NOW()) BETWEEN 5 AND 10 THEN 'amarillo'
        ELSE 'rojo'
    END as semaforo_actual,
    eli_eje as estado_actual
FROM ejecutivo 
WHERE eli_eje = 1 
  AND ult_eje IS NOT NULL 
  AND TIMESTAMPDIFF(DAY, ult_eje, NOW()) >= 11
ORDER BY TIMESTAMPDIFF(DAY, ult_eje, NOW()) DESC;

-- =====================================================
-- Paso 5: Prueba manual del stored procedure (OPCIONAL)
-- =====================================================

-- Descomenta la siguiente línea para probar el procedimiento manualmente
CALL sp_eliminar_ejecutivos_inactivos();

-- =====================================================
-- INSTRUCCIONES DE USO:
-- =====================================================
-- 1. Copia y pega todo este script en tu consola MySQL/MariaDB
-- 2. El evento se ejecutará automáticamente cada día a las 2:00 AM
-- 3. Puedes ejecutar manualmente el stored procedure con: CALL sp_eliminar_ejecutivos_inactivos();
-- 4. Para verificar los eventos activos: SHOW EVENTS;
-- 5. Para deshabilitar el evento: ALTER EVENT evt_eliminar_ejecutivos_inactivos DISABLE;
-- 6. Para eliminar el evento: DROP EVENT evt_eliminar_ejecutivos_inactivos;
-- =====================================================
