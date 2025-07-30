<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log de debugging
file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] REQUEST recibido: ' . print_r($_POST, true) . "\n", FILE_APPEND);

include '../inc/conexion.php';
include '../inc/websocket_utils.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Validar que action esté presente
	if (!isset($_POST['action'])) {
		echo respuestaError('Acción no especificada');
		exit;
	}
	
	$action = escape($_POST['action'], $connection);
	
	switch($action) {

		case 'test_conexion':
			echo respuestaExito(['timestamp' => date('Y-m-d H:i:s')], 'Controlador de ejecutivos funcionando correctamente');
		break;

		case 'obtener_ejecutivos_jerarquia':
			// Obtener todos los ejecutivos con sus relaciones jerárquicas y semáforo de sesión
			$query = "SELECT e.id_eje, e.nom_eje, e.tel_eje, e.fot_eje, e.eli_eje, e.id_padre, e.id_pla, e.ult_eje, 
					         p.nom_pla,
					         CASE 
					             WHEN e.ult_eje IS NULL THEN 'sin_sesion'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) <= 1 THEN 'verde'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) BETWEEN 2 AND 3 THEN 'amarillo'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) >= 4 THEN 'rojo'
					             ELSE 'sin_sesion'
					         END as semaforo_sesion,
					         TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) as dias_desde_ultima_sesion
					  FROM ejecutivo e 
					  LEFT JOIN plantel p ON e.id_pla = p.id_pla 
					  ORDER BY e.eli_eje DESC, e.nom_eje ASC";
			
			// Log para debugging
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Query ejecutivos jerarquía: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Ejecutivos jerarquía encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Ejecutivos obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL ejecutivos jerarquía: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar ejecutivos: ' . $error . ' Query: ' . $query);
			}
		break;

		case 'obtener_ejecutivos_con_planteles':
			// Obtener todos los ejecutivos con sus planteles asociados (permisos)
			$query = "SELECT e.id_eje, e.nom_eje, e.tel_eje, e.eli_eje, e.id_padre, e.id_pla, 
					         p.nom_pla as plantel_principal,
					         GROUP_CONCAT(DISTINCT CONCAT(pa.id_pla, ':', pa.nom_pla) SEPARATOR '|') as planteles_asociados,
					         COUNT(DISTINCT pe.id_pla) as total_planteles_asociados
					  FROM ejecutivo e 
					  LEFT JOIN plantel p ON e.id_pla = p.id_pla 
					  LEFT JOIN planteles_ejecutivo pe ON e.id_eje = pe.id_eje
					  LEFT JOIN plantel pa ON pe.id_pla = pa.id_pla
					  GROUP BY e.id_eje
					  ORDER BY e.eli_eje DESC, e.nom_eje ASC";
			
			// Log para debugging
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Query ejecutivos con planteles: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				// Procesar los datos para estructurar mejor los planteles asociados
				foreach($datos as &$ejecutivo) {
					$ejecutivo['planteles_asociados_array'] = [];
					if($ejecutivo['planteles_asociados']) {
						$planteles = explode('|', $ejecutivo['planteles_asociados']);
						foreach($planteles as $plantel) {
							$partes = explode(':', $plantel);
							if(count($partes) == 2) {
								$ejecutivo['planteles_asociados_array'][] = [
									'id_pla' => $partes[0],
									'nom_pla' => $partes[1]
								];
							}
						}
					}
				}
				
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Ejecutivos con planteles encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Ejecutivos con planteles obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL ejecutivos con planteles: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar ejecutivos con planteles: ' . $error . ' Query: ' . $query);
			}
		break;

		case 'crear_ejecutivo':
			$nom_eje = escape($_POST['nom_eje'], $connection);
			$tel_eje = escape($_POST['tel_eje'], $connection);
			$id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' && $_POST['id_padre'] !== 'null' ? escape($_POST['id_padre'], $connection) : null;
			$id_pla = isset($_POST['id_pla']) && $_POST['id_pla'] !== '' && $_POST['id_pla'] !== 'null' ? escape($_POST['id_pla'], $connection) : null;
			$eli_eje = isset($_POST['eli_eje']) ? intval($_POST['eli_eje']) : 1;
			
			// Validaciones backend
			if(empty($nom_eje)) {
				echo respuestaError('El nombre es requerido');
				break;
			}
			
			if(empty($tel_eje)) {
				echo respuestaError('El teléfono es requerido');
				break;
			}
			
			// Verificar que el padre existe si se especifica
			if($id_padre) {
				$queryPadre = "SELECT id_eje FROM ejecutivo WHERE id_eje = '$id_padre' AND eli_eje = 1";
				$padreExiste = ejecutarConsulta($queryPadre, $connection);
				
				if(!$padreExiste || empty($padreExiste)) {
					echo respuestaError('El ejecutivo padre especificado no existe o está inactivo');
					break;
				}
			}
			
			// Insertar ejecutivo PRIMERO para obtener el ID
			$query = "INSERT INTO ejecutivo (nom_eje, tel_eje, eli_eje, id_padre, id_pla) 
					  VALUES ('$nom_eje', '$tel_eje', $eli_eje, " . ($id_padre ? "'$id_padre'" : "NULL") . ", " . ($id_pla ? "'$id_pla'" : "NULL") . ")";
			
			if(!mysqli_query($connection, $query)) {
				echo respuestaError('Error al crear ejecutivo');
				break;
			}
			
			$nuevo_id = mysqli_insert_id($connection);
			
			// Procesar imagen si existe
			if(isset($_FILES['fot_eje']) && $_FILES['fot_eje']['error'] === UPLOAD_ERR_OK) {
				$archivo = $_FILES['fot_eje'];
				$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
				
				// Validaciones de imagen
				if(!in_array($extension, ['jpg', 'jpeg', 'png'])) {
					echo respuestaError('Solo se permiten archivos JPG y PNG');
					break;
				}
				
				if($archivo['size'] > 52428800) { // 50MB
					echo respuestaError('La imagen no debe exceder 50MB');
					break;
				}
				
				// Generar nombre único: ID + SHA-1 del contenido
				$contenido_archivo = file_get_contents($archivo['tmp_name']);
				$sha1_hash = sha1($contenido_archivo . $nuevo_id);
				$fot_eje = "foto-ejecutivo-{$nuevo_id}-{$sha1_hash}.{$extension}";
				
				$ruta = '../uploads/' . $fot_eje;
				
				// Mover archivo a carpeta uploads
				if(move_uploaded_file($archivo['tmp_name'], $ruta)) {
					// Actualizar ejecutivo con el nombre de la imagen
					$query_update = "UPDATE ejecutivo SET fot_eje = '$fot_eje' WHERE id_eje = '$nuevo_id'";
					mysqli_query($connection, $query_update);
				} else {
					echo respuestaError('Error al guardar imagen');
					break;
				}
			}
			
			// Registrar en historial de ejecutivos
			$descripcion = "Se creó nuevo ejecutivo: '$nom_eje'";
			registrarHistorialEjecutivo($connection, $nuevo_id, 'alta', $descripcion, $nom_eje);
			
			echo respuestaExito(['id' => $nuevo_id], 'Ejecutivo creado correctamente');
		break;

		case 'actualizar_ejecutivo':
			$id_eje = escape($_POST['id_eje'], $connection);
			$nom_eje = escape($_POST['nom_eje'], $connection);
			$tel_eje = escape($_POST['tel_eje'], $connection);
			$id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' && $_POST['id_padre'] !== 'null' ? escape($_POST['id_padre'], $connection) : null;
			// Solo actualizar plantel si se envía explícitamente, de lo contrario conservar el actual
			$actualizar_plantel = isset($_POST['id_pla']);
			$id_pla = $actualizar_plantel && $_POST['id_pla'] !== '' && $_POST['id_pla'] !== 'null' ? escape($_POST['id_pla'], $connection) : null;
			
			$eli_eje = isset($_POST['eli_eje']) ? intval($_POST['eli_eje']) : 1;
			
			// Validaciones backend
			if(empty($id_eje)) {
				echo respuestaError('ID del ejecutivo es requerido');
				break;
			}
			
			if(empty($nom_eje)) {
				echo respuestaError('El nombre es requerido');
				break;
			}
			
			if(empty($tel_eje)) {
				echo respuestaError('El teléfono es requerido');
				break;
			}
			
			// Obtener datos anteriores para el historial
			$queryAnterior = "SELECT * FROM ejecutivo WHERE id_eje = '$id_eje'";
			$datosAnteriores = ejecutarConsulta($queryAnterior, $connection);
			
			if(!$datosAnteriores || empty($datosAnteriores)) {
				echo respuestaError('El ejecutivo especificado no existe');
				break;
			}
			
			$datosAnt = $datosAnteriores[0];
			
			// Verificar que el padre existe si se especifica y no es el mismo ejecutivo
			if($id_padre && $id_padre != $id_eje) {
				$queryPadre = "SELECT id_eje FROM ejecutivo WHERE id_eje = '$id_padre' AND eli_eje = 1";
				$padreExiste = ejecutarConsulta($queryPadre, $connection);
				
				if(!$padreExiste || empty($padreExiste)) {
					echo respuestaError('El ejecutivo padre especificado no existe o está inactivo');
					break;
				}
				
				// Verificar que no se cree una referencia circular
				if(esReferenciaCircular($connection, $id_eje, $id_padre)) {
					echo respuestaError('No se puede establecer esta relación padre-hijo porque crearía una referencia circular');
					break;
				}
			} elseif($id_padre == $id_eje) {
				echo respuestaError('Un ejecutivo no puede ser padre de sí mismo');
				break;
			}
			
			// Actualizar datos básicos
			$query = "UPDATE ejecutivo SET nom_eje = '$nom_eje', tel_eje = '$tel_eje', eli_eje = $eli_eje, id_padre = " . ($id_padre ? "'$id_padre'" : "NULL");
			
			// Solo actualizar plantel si se envió explícitamente
			if($actualizar_plantel) {
				$query .= ", id_pla = " . ($id_pla ? "'$id_pla'" : "NULL");
			}
			
			$query .= " WHERE id_eje = '$id_eje'";
			
			if(!mysqli_query($connection, $query)) {
				echo respuestaError('Error al actualizar ejecutivo');
				break;
			}
			
			// Procesar nueva imagen si existe
			if(isset($_FILES['fot_eje']) && $_FILES['fot_eje']['error'] === UPLOAD_ERR_OK) {
				$archivo = $_FILES['fot_eje'];
				$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
				
				// Validaciones de imagen
				if(!in_array($extension, ['jpg', 'jpeg', 'png'])) {
					echo respuestaError('Solo se permiten archivos JPG y PNG');
					break;
				}
				
				if($archivo['size'] > 52428800) { // 50MB
					echo respuestaError('La imagen no debe exceder 50MB');
					break;
				}
				
				$fotoActual = $datosAnt['fot_eje'];
				
				// Eliminar foto anterior si existe
				if($fotoActual != NULL && file_exists("../uploads/$fotoActual")) {
					unlink("../uploads/$fotoActual");
				}
				
				// Generar NUEVO nombre único (IMPORTANTE: evita caché del navegador)
				$contenido_archivo = file_get_contents($archivo['tmp_name']);
				$sha1_hash = sha1($contenido_archivo . $id_eje . time()); // Agregamos time() para forzar nuevo hash
				$nueva_foto = "foto-ejecutivo-{$id_eje}-{$sha1_hash}.{$extension}";
				
				$ruta = '../uploads/' . $nueva_foto;
				
				// Mover nueva imagen
				if(move_uploaded_file($archivo['tmp_name'], $ruta)) {
					// Actualizar BD con nuevo nombre
					$query_update = "UPDATE ejecutivo SET fot_eje = '$nueva_foto' WHERE id_eje = '$id_eje'";
					mysqli_query($connection, $query_update);
				} else {
					echo respuestaError('Error al guardar nueva imagen');
					break;
				}
			}
			
			// Registrar cambios en historial
			$cambios = [];
			
			if($datosAnt['nom_eje'] != $nom_eje) {
				$cambios[] = "NOM_EJE de '{$datosAnt['nom_eje']}' a '$nom_eje'";
			}
			if($datosAnt['tel_eje'] != $tel_eje) {
				$cambios[] = "TEL_EJE de '{$datosAnt['tel_eje']}' a '$tel_eje'";
			}
			if($datosAnt['eli_eje'] != $eli_eje) {
				$estado_ant = $datosAnt['eli_eje'] == 1 ? 'activo' : 'inactivo';
				$estado_nuevo = $eli_eje == 1 ? 'activo' : 'inactivo';
				$cambios[] = "ELI_EJE de '$estado_ant' a '$estado_nuevo'";
			}
			if($datosAnt['id_padre'] != $id_padre) {
				$padre_ant = $datosAnt['id_padre'] ? $datosAnt['id_padre'] : '(sin padre)';
				$padre_nuevo = $id_padre ? $id_padre : '(sin padre)';
				$cambios[] = "ID_PADRE de '$padre_ant' a '$padre_nuevo'";
			}
			// Solo registrar cambio de plantel si se actualizó explícitamente
			if($actualizar_plantel && $datosAnt['id_pla'] != $id_pla) {
				$pla_ant = $datosAnt['id_pla'] ? $datosAnt['id_pla'] : '(sin plantel)';
				$pla_nuevo = $id_pla ? $id_pla : '(sin plantel)';
				$cambios[] = "ID_PLA de '$pla_ant' a '$pla_nuevo'";
			}
			
			if(!empty($cambios)) {
				$descripcion = "Se modificó " . implode(', ', $cambios) . " en el ejecutivo '$nom_eje'";
				registrarHistorialEjecutivo($connection, $id_eje, 'cambio', $descripcion, $nom_eje);
			}
			
			echo respuestaExito(null, 'Ejecutivo actualizado correctamente');
		break;
		break;

		case 'toggle_estado_ejecutivo':
			$id_eje = escape($_POST['id_eje'], $connection);
			$eli_eje = isset($_POST['eli_eje']) ? intval($_POST['eli_eje']) : 1;
			
			if(empty($id_eje)) {
				echo respuestaError('ID del ejecutivo es requerido');
				break;
			}
			
			// Obtener información del ejecutivo
			$queryInfo = "SELECT nom_eje, eli_eje FROM ejecutivo WHERE id_eje = '$id_eje'";
			$infoResult = ejecutarConsulta($queryInfo, $connection);
			
			if(!$infoResult || empty($infoResult)) {
				echo respuestaError('Ejecutivo no encontrado');
				break;
			}
			
			$nombreEjecutivo = $infoResult[0]['nom_eje'];
			$estadoAnterior = $infoResult[0]['eli_eje'];
			
			// Actualizar estado
			$query = "UPDATE ejecutivo SET eli_eje = $eli_eje WHERE id_eje = '$id_eje'";
			
			if(mysqli_query($connection, $query)) {
				// Registrar en historial
				$accion = $eli_eje == 1 ? 'mostró' : 'ocultó';
				$descripcion = "Se $accion el ejecutivo '$nombreEjecutivo'";
				registrarHistorialEjecutivo($connection, $id_eje, $eli_eje == 1 ? 'alta' : 'baja', $descripcion, $nombreEjecutivo);
				
				$mensaje = $eli_eje == 1 ? 'Ejecutivo mostrado correctamente' : 'Ejecutivo ocultado correctamente';
				echo respuestaExito(['nuevo_estado' => $eli_eje], $mensaje);
			} else {
				echo respuestaError('Error al actualizar estado: ' . mysqli_error($connection) . ' Query: ' . $query);
			}
		break;

		case 'mover_ejecutivo':
			$id_eje = escape($_POST['id_eje'], $connection);
			$id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' && $_POST['id_padre'] !== 'null' ? escape($_POST['id_padre'], $connection) : null;
			$id_pla = isset($_POST['id_pla']) && $_POST['id_pla'] !== '' && $_POST['id_pla'] !== 'null' ? escape($_POST['id_pla'], $connection) : null;
			
			// Log mejorado para debugging
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] ==============================================' . "\n", FILE_APPEND);
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] MOVER EJECUTIVO - REQUEST RECIBIDO' . "\n", FILE_APPEND);
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] POST data completo: ' . print_r($_POST, true) . "\n", FILE_APPEND);
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Valores procesados:' . "\n", FILE_APPEND);
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] - ID Ejecutivo: ' . $id_eje . "\n", FILE_APPEND);
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] - ID Padre: ' . ($id_padre ? $id_padre : 'NULL') . "\n", FILE_APPEND);
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] - ID Plantel: ' . ($id_pla ? $id_pla : 'NULL') . "\n", FILE_APPEND);
			
			if(empty($id_eje)) {
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] ERROR: ID del ejecutivo vacío' . "\n", FILE_APPEND);
				echo respuestaError('ID del ejecutivo es requerido');
				break;
			}
			
			// Verificar que el ejecutivo existe
			$queryExiste = "SELECT nom_eje, id_padre, id_pla FROM ejecutivo WHERE id_eje = '$id_eje'";
			$ejecutivoExiste = ejecutarConsulta($queryExiste, $connection);
			
			if(!$ejecutivoExiste || empty($ejecutivoExiste)) {
				echo respuestaError('El ejecutivo especificado no existe');
				break;
			}
			
			$nombreEjecutivo = $ejecutivoExiste[0]['nom_eje'];
			$padreAnterior = $ejecutivoExiste[0]['id_padre'];
			$plantelAnterior = $ejecutivoExiste[0]['id_pla'];
			
			// Log de valores anteriores
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] ANTES - Ejecutivo: ' . $nombreEjecutivo . ', Padre anterior: ' . $padreAnterior . ', Plantel anterior: ' . $plantelAnterior . "\n", FILE_APPEND);
			
			// Verificar que no se mueva a sí mismo
			if($id_padre == $id_eje) {
				echo respuestaError('Un ejecutivo no puede ser padre de sí mismo');
				break;
			}
			
			// Verificar que el nuevo padre existe si se especifica
			if($id_padre) {
				$queryPadre = "SELECT nom_eje FROM ejecutivo WHERE id_eje = '$id_padre' AND eli_eje = 1";
				$padreExiste = ejecutarConsulta($queryPadre, $connection);
				
				if(!$padreExiste || empty($padreExiste)) {
					echo respuestaError('El ejecutivo padre especificado no existe o está inactivo');
					break;
				}
				
				$nombrePadre = $padreExiste[0]['nom_eje'];
				
				// Verificar referencias circulares
				if(esReferenciaCircular($connection, $id_eje, $id_padre)) {
					echo respuestaError('No se puede mover a esta posición porque crearía una referencia circular');
					break;
				}
				
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] NUEVO PADRE: ' . $nombrePadre . ' (ID: ' . $id_padre . ')' . "\n", FILE_APPEND);
			}
			
			// Verificar que el plantel existe si se especifica
			if($id_pla) {
				$queryPlantel = "SELECT nom_pla FROM plantel WHERE id_pla = '$id_pla'";
				$plantelExiste = ejecutarConsulta($queryPlantel, $connection);
				
				if(!$plantelExiste || empty($plantelExiste)) {
					echo respuestaError('El plantel especificado no existe');
					break;
				}
				
				$nombrePlantel = $plantelExiste[0]['nom_pla'];
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] NUEVO PLANTEL: ' . $nombrePlantel . ' (ID: ' . $id_pla . ')' . "\n", FILE_APPEND);
			}
			
			// Mover ejecutivo
			$query = "UPDATE ejecutivo SET id_padre = " . ($id_padre ? "'$id_padre'" : "NULL") . ", id_pla = " . ($id_pla ? "'$id_pla'" : "NULL") . " WHERE id_eje = '$id_eje'";
			
			// Log de la query
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] QUERY MOVER: ' . $query . "\n", FILE_APPEND);
			
			if(mysqli_query($connection, $query)) {
				// Verificar que el cambio se aplicó
				$queryVerificar = "SELECT id_padre, id_pla FROM ejecutivo WHERE id_eje = '$id_eje'";
				$resultVerificar = ejecutarConsulta($queryVerificar, $connection);
				
				if($resultVerificar && !empty($resultVerificar)) {
					$nuevoPadreReal = $resultVerificar[0]['id_padre'];
					$nuevoPlantelReal = $resultVerificar[0]['id_pla'];
					file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] VERIFICACIÓN - Nuevo padre: ' . $nuevoPadreReal . ', Nuevo plantel: ' . $nuevoPlantelReal . "\n", FILE_APPEND);
				}
				
				// =====================================
				// NOTIFICACIONES WEBSOCKET P25/P26
				// =====================================
				
				// Enviar notificación de movimiento jerárquico si cambió el padre
				if ($padreAnterior != $id_padre) {
					notificarMovimientoEjecutivo($id_eje, $padreAnterior, $id_padre, $nombreEjecutivo);
					file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] WebSocket movimiento enviado' . "\n", FILE_APPEND);
				}
				
				// Enviar notificación de cambio de plantel si cambió el plantel
				if ($plantelAnterior != $id_pla) {
					notificarCambioPlantelEjecutivo($id_eje, $plantelAnterior, $id_pla, $nombreEjecutivo, $connection);
					
					// Actualizar estadísticas del plantel anterior si existía
					if ($plantelAnterior) {
						$estadisticasAnterior = obtenerEstadisticasPlantel($plantelAnterior, $connection);
						notificarActualizacionCitasPlantel($plantelAnterior, "Plantel ID $plantelAnterior", $estadisticasAnterior);
					}
					
					// Actualizar estadísticas del plantel nuevo si existe
					if ($id_pla) {
						$estadisticasNuevo = obtenerEstadisticasPlantel($id_pla, $connection);
						notificarActualizacionCitasPlantel($id_pla, "Plantel ID $id_pla", $estadisticasNuevo);
					}
					
					file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] WebSocket cambio plantel enviado' . "\n", FILE_APPEND);
				}
				
				// Registrar en historial si existe
				if(function_exists('registrarHistorial')) {
					$descripcionPadre = $id_padre ? "bajo ejecutivo ID $id_padre" : "como nodo raíz";
					$descripcionPlantel = $id_pla ? " en plantel ID $id_pla" : "";
					$descripcion = "Se movió el ejecutivo '$nombreEjecutivo' $descripcionPadre$descripcionPlantel";
					registrarHistorial($connection, 0, 'cambio', $descripcion, $nombreEjecutivo);
				}
				
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] EJECUTIVO MOVIDO EXITOSAMENTE' . "\n", FILE_APPEND);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] RESPUESTA ENVIADA: SUCCESS' . "\n", FILE_APPEND);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] ==============================================' . "\n", FILE_APPEND);
				echo respuestaExito(null, 'Ejecutivo movido correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] ERROR AL MOVER: ' . $error . "\n", FILE_APPEND);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] RESPUESTA ENVIADA: ERROR' . "\n", FILE_APPEND);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] ==============================================' . "\n", FILE_APPEND);
				echo respuestaError('Error al mover ejecutivo: ' . $error);
			}
		break;

		case 'obtener_estadisticas':
			$queryStats = "SELECT 
							COUNT(*) as total,
							SUM(CASE WHEN eli_eje = 1 THEN 1 ELSE 0 END) as activos,
							SUM(CASE WHEN eli_eje = 0 THEN 1 ELSE 0 END) as ocultos,
							SUM(CASE WHEN id_padre IS NULL THEN 1 ELSE 0 END) as raiz
						   FROM ejecutivo";
			
			$stats = ejecutarConsulta($queryStats, $connection);
			
			if($stats !== false && !empty($stats)) {
				echo respuestaExito($stats[0], 'Estadísticas obtenidas correctamente');
			} else {
				echo respuestaError('Error al obtener estadísticas: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_planteles':
			// Obtener todos los planteles
			$query = "SELECT id_pla, nom_pla, fec_pla FROM plantel ORDER BY nom_pla ASC";
			
			// Log para debugging
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Query planteles: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Planteles encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Planteles obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar planteles: ' . $error);
			}
		break;

		case 'obtener_ejecutivos_por_plantel':
			// Obtener ejecutivos agrupados por plantel con jerarquía, planteles asociados y semáforo de sesión
			$query = "SELECT e.id_eje, e.nom_eje, e.tel_eje, e.eli_eje, e.id_padre, e.id_pla, e.ult_eje,
					         p.nom_pla,
					         GROUP_CONCAT(DISTINCT CONCAT(pa.id_pla, ':', pa.nom_pla) SEPARATOR '|') as planteles_asociados,
					         COUNT(DISTINCT pe.id_pla) as total_planteles_asociados,
					         CASE 
					             WHEN e.ult_eje IS NULL THEN 'sin_sesion'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) <= 1 THEN 'verde'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) BETWEEN 2 AND 3 THEN 'amarillo'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) >= 4 THEN 'rojo'
					             ELSE 'sin_sesion'
					         END as semaforo_sesion,
					         TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) as dias_desde_ultima_sesion
					  FROM ejecutivo e 
					  LEFT JOIN plantel p ON e.id_pla = p.id_pla 
					  LEFT JOIN planteles_ejecutivo pe ON e.id_eje = pe.id_eje
					  LEFT JOIN plantel pa ON pe.id_pla = pa.id_pla
					  GROUP BY e.id_eje
					  ORDER BY e.id_pla, e.eli_eje DESC, e.nom_eje ASC";
			
			// Log para debugging
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Query ejecutivos por plantel: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				// Procesar los datos para estructurar mejor los planteles asociados
				foreach($datos as &$ejecutivo) {
					$ejecutivo['planteles_asociados_array'] = [];
					if($ejecutivo['planteles_asociados']) {
						$planteles = explode('|', $ejecutivo['planteles_asociados']);
						foreach($planteles as $plantel) {
							$partes = explode(':', $plantel);
							if(count($partes) == 2) {
								$ejecutivo['planteles_asociados_array'][] = [
									'id_pla' => $partes[0],
									'nom_pla' => $partes[1]
								];
							}
						}
					}
				}
				
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Ejecutivos por plantel encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Ejecutivos por plantel obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL ejecutivos por plantel: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar ejecutivos por plantel: ' . $error);
			}
		break;

		case 'obtener_historial_ejecutivo':
			$id_eje = escape($_POST['id_eje'], $connection);
			
			if(empty($id_eje)) {
				echo respuestaError('ID del ejecutivo es requerido');
				break;
			}
			
			$historial = obtenerHistorialEjecutivo($connection, $id_eje);
			
			if($historial !== false) {
				echo respuestaExito($historial, 'Historial de ejecutivo obtenido correctamente');
			} else {
				echo respuestaError('Error al obtener historial del ejecutivo: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_ejecutivos_con_citas':
			// Obtener fecha de filtro si se proporciona
			$fecha_inicio = isset($_POST['fecha_inicio']) ? escape($_POST['fecha_inicio'], $connection) : null;
			$fecha_fin = isset($_POST['fecha_fin']) ? escape($_POST['fecha_fin'], $connection) : null;
			
			// Construir condición de fecha
			$condicion_fecha = '';
			if ($fecha_inicio && $fecha_fin) {
				$condicion_fecha = "AND c.cit_cit BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			} elseif ($fecha_inicio) {
				$condicion_fecha = "AND c.cit_cit >= '$fecha_inicio'";
			} elseif ($fecha_fin) {
				$condicion_fecha = "AND c.cit_cit <= '$fecha_fin'";
			}
			
			// Obtener todos los ejecutivos con conteo de citas particulares
			$query = "SELECT e.id_eje, e.nom_eje, e.tel_eje, e.fot_eje, e.eli_eje, e.id_padre, e.id_pla, e.ult_eje,
					         p.nom_pla as plantel_principal,
					         COUNT(DISTINCT c.id_cit) as citas_propias,
					         GROUP_CONCAT(DISTINCT CONCAT(pa.id_pla, ':', pa.nom_pla) SEPARATOR '|') as planteles_asociados,
					         COUNT(DISTINCT pe.id_pla) as total_planteles_asociados,
					         CASE 
					             WHEN e.ult_eje IS NULL THEN 'sin_sesion'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) <= 1 THEN 'verde'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) BETWEEN 2 AND 3 THEN 'amarillo'
					             WHEN TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) >= 4 THEN 'rojo'
					             ELSE 'sin_sesion'
					         END as semaforo_sesion,
					         TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) as dias_desde_ultima_sesion
					  FROM ejecutivo e 
					  LEFT JOIN plantel p ON e.id_pla = p.id_pla 
					  LEFT JOIN cita c ON e.id_eje = c.id_eje2 AND c.eli_cit = 1 $condicion_fecha
					  LEFT JOIN planteles_ejecutivo pe ON e.id_eje = pe.id_eje
					  LEFT JOIN plantel pa ON pe.id_pla = pa.id_pla
					  GROUP BY e.id_eje, e.nom_eje, e.tel_eje, e.fot_eje, e.eli_eje, e.id_padre, e.id_pla, e.ult_eje, p.nom_pla
					  ORDER BY e.eli_eje DESC, e.nom_eje ASC";
			
			// Log para debugging
			file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Query ejecutivos con citas: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				// Procesar los datos para estructurar mejor los planteles asociados
				foreach($datos as &$ejecutivo) {
					$ejecutivo['planteles_asociados_array'] = [];
					if($ejecutivo['planteles_asociados']) {
						$planteles = explode('|', $ejecutivo['planteles_asociados']);
						foreach($planteles as $plantel) {
							$partes = explode(':', $plantel);
							if(count($partes) == 2) {
								$ejecutivo['planteles_asociados_array'][] = [
									'id_pla' => $partes[0],
									'nom_pla' => $partes[1]
								];
							}
						}
					}
					// Calcular los conteos recursivos
					$ejecutivo['citas_recursivas'] = calcularCitasRecursivas($connection, $ejecutivo['id_eje'], $fecha_inicio, $fecha_fin);
				}
				
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Ejecutivos con citas encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Ejecutivos con conteo de citas obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL ejecutivos con citas: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar ejecutivos con citas: ' . $error . ' Query: ' . $query);
			}
		break;

		case 'obtener_citas_totales_por_plantel':
			$id_pla = isset($_POST['id_pla']) ? intval($_POST['id_pla']) : null;
			if(!$id_pla) {
				echo respuestaError('ID del plantel es requerido');
				break;
			}
			
			// Obtener fechas de filtro si se proporcionan
			$fecha_inicio = isset($_POST['fecha_inicio']) ? escape($_POST['fecha_inicio'], $connection) : null;
			$fecha_fin = isset($_POST['fecha_fin']) ? escape($_POST['fecha_fin'], $connection) : null;
			
			// Construir condición de fecha
			$condicion_fecha = '';
			if ($fecha_inicio && $fecha_fin) {
				$condicion_fecha = "AND c.cit_cit BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			} elseif ($fecha_inicio) {
				$condicion_fecha = "AND c.cit_cit >= '$fecha_inicio'";
			} elseif ($fecha_fin) {
				$condicion_fecha = "AND c.cit_cit <= '$fecha_fin'";
			}
			
			// NUEVA LÓGICA P25/P26: Contar citas directamente asociadas al plantel
			// Esto funciona aún si id_eje2 es NULL (citas huérfanas)
			$query = "SELECT 
						COUNT(*) as total_citas,
						COUNT(CASE WHEN c.id_eje2 IS NOT NULL THEN 1 END) as citas_con_ejecutivo,
						COUNT(CASE WHEN c.id_eje2 IS NULL THEN 1 END) as citas_sin_ejecutivo,
						p.nom_pla
					  FROM cita c 
					  LEFT JOIN plantel p ON c.pla_cit = p.id_pla 
					  WHERE c.pla_cit = $id_pla AND c.eli_cit = 1 $condicion_fecha
					  GROUP BY p.nom_pla";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				if (!empty($datos)) {
					$resultado = $datos[0];
				} else {
					// Si no hay citas, obtener nombre del plantel
					$queryPlantel = "SELECT nom_pla FROM plantel WHERE id_pla = $id_pla";
					$plantelResult = ejecutarConsulta($queryPlantel, $connection);
					$resultado = [
						'total_citas' => 0,
						'citas_con_ejecutivo' => 0,
						'citas_sin_ejecutivo' => 0,
						'nom_pla' => $plantelResult ? $plantelResult[0]['nom_pla'] : 'Plantel desconocido'
					];
				}
				echo respuestaExito($resultado, 'Total de citas del plantel obtenidas correctamente');
			} else {
				$error = mysqli_error($connection);
				echo respuestaError('Error al consultar total de citas: ' . $error . ' Query: ' . $query);
			}
		break;

		default:
			echo respuestaError('Acción no válida');
		break;
	}

	mysqli_close($connection);
	exit;
}

// =====================================
// FUNCIONES AUXILIARES
// =====================================

function esReferenciaCircular($connection, $hijo_id, $padre_id) {
	// Verificar si establecer padre_id como padre de hijo_id crearía una referencia circular
	
	$nodo_actual = $padre_id;
	$visitados = [];
	
	while($nodo_actual && !in_array($nodo_actual, $visitados)) {
		$visitados[] = $nodo_actual;
		
		// Si encontramos que el padre propuesto es descendiente del hijo, hay circularidad
		if($nodo_actual == $hijo_id) {
			return true;
		}
		
		// Obtener el padre del nodo actual
		$query = "SELECT id_padre FROM ejecutivo WHERE id_eje = '" . escape($nodo_actual, $connection) . "'";
		$result = ejecutarConsulta($query, $connection);
		
		if($result && !empty($result)) {
			$nodo_actual = $result[0]['id_padre'];
		} else {
			break;
		}
	}
	
	return false;
}

function obtenerDescendientes($connection, $id_eje) {
	// Función recursiva para obtener todos los descendientes de un ejecutivo
	$descendientes = [];
	
	$query = "SELECT id_eje, nom_eje FROM ejecutivo WHERE id_padre = '" . escape($id_eje, $connection) . "'";
	$hijos = ejecutarConsulta($query, $connection);
	
	if($hijos) {
		foreach($hijos as $hijo) {
			$descendientes[] = $hijo;
			$descendientes = array_merge($descendientes, obtenerDescendientes($connection, $hijo['id_eje']));
		}
	}
	
	return $descendientes;
}

// =====================================
// FUNCIONES DE HISTORIAL DE EJECUTIVOS
// =====================================

function registrarHistorialEjecutivo($connection, $id_eje, $tipo_movimiento, $descripcion, $responsable = null) {
	// Si no se proporciona responsable, seleccionar uno aleatorio
	if (!$responsable) {
		$queryEjecutivo = "SELECT nom_eje FROM ejecutivo WHERE eli_eje = 1 ORDER BY RAND() LIMIT 1";
		$ejecutivoResult = ejecutarConsulta($queryEjecutivo, $connection);
		$responsable = $ejecutivoResult ? $ejecutivoResult[0]['nom_eje'] : 'Sistema';
	}
	
	$id_eje_escaped = escape($id_eje, $connection);
	$tipo_escaped = escape($tipo_movimiento, $connection);
	$desc_escaped = escape($descripcion, $connection);
	$resp_escaped = escape($responsable, $connection);
	
	$query = "INSERT INTO historial_ejecutivo (fec_his_eje, res_his_eje, mov_his_eje, des_his_eje, id_eje11) 
			  VALUES (NOW(), '$resp_escaped', '$tipo_escaped', '$desc_escaped', '$id_eje_escaped')";
	
	return mysqli_query($connection, $query);
}

function obtenerHistorialEjecutivo($connection, $id_eje) {
	$id_eje_escaped = escape($id_eje, $connection);
	
	$query = "SELECT * FROM historial_ejecutivo 
			  WHERE id_eje11 = '$id_eje_escaped' 
			  ORDER BY fec_his_eje DESC";
	
	return ejecutarConsulta($query, $connection);
}

// =====================================
// FUNCIONES DE CONTEO DE CITAS
// =====================================

function calcularCitasRecursivas($connection, $id_eje, $fecha_inicio = null, $fecha_fin = null) {
	// Función recursiva para calcular el total de citas del ejecutivo y todos sus descendientes
	
	// Construir condición de fecha
	$condicion_fecha = '';
	if ($fecha_inicio && $fecha_fin) {
		$condicion_fecha = "AND c.cit_cit BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	} elseif ($fecha_inicio) {
		$condicion_fecha = "AND c.cit_cit >= '$fecha_inicio'";
	} elseif ($fecha_fin) {
		$condicion_fecha = "AND c.cit_cit <= '$fecha_fin'";
	}
	
	// Obtener citas propias del ejecutivo
	$query_propias = "SELECT COUNT(*) as total 
					  FROM cita c 
					  WHERE c.id_eje2 = $id_eje 
					  AND c.eli_cit = 1 
					  $condicion_fecha";
	
	$result_propias = ejecutarConsulta($query_propias, $connection);
	$citas_propias = $result_propias ? (int)$result_propias[0]['total'] : 0;
	
	// Obtener hijos directos
	$query_hijos = "SELECT id_eje FROM ejecutivo WHERE id_padre = $id_eje AND eli_eje = 1";
	$hijos = ejecutarConsulta($query_hijos, $connection);
	
	$citas_hijos = 0;
	if ($hijos) {
		foreach ($hijos as $hijo) {
			// Llamada recursiva para obtener citas del hijo y sus descendientes
			$citas_hijos += calcularCitasRecursivas($connection, $hijo['id_eje'], $fecha_inicio, $fecha_fin);
		}
	}
	
	$total_recursivo = $citas_propias + $citas_hijos;
	
	// Log para debugging
	file_put_contents('debug_ejecutivos.log', '[' . date('Y-m-d H:i:s') . '] Ejecutivo ID: ' . $id_eje . ' - Citas propias: ' . $citas_propias . ' - Citas hijos: ' . $citas_hijos . ' - Total: ' . $total_recursivo . "\n", FILE_APPEND);
	
	return $total_recursivo;
}

function obtenerDescendientesRecursivos($connection, $id_eje, $visitados = []) {
	// Función recursiva para obtener todos los descendientes de un ejecutivo
	$descendientes = [];
	
	// Evitar recursión infinita
	if (in_array($id_eje, $visitados)) {
		return $descendientes;
	}
	
	$visitados[] = $id_eje;
	
	$query = "SELECT id_eje, nom_eje FROM ejecutivo WHERE id_padre = '" . escape($id_eje, $connection) . "' AND eli_eje = 1";
	$hijos = ejecutarConsulta($query, $connection);
	
	if($hijos) {
		foreach($hijos as $hijo) {
			$descendientes[] = $hijo;
			// Obtener descendientes del hijo recursivamente
			$descendientes_hijo = obtenerDescendientesRecursivos($connection, $hijo['id_eje'], $visitados);
			$descendientes = array_merge($descendientes, $descendientes_hijo);
		}
	}
	
	return $descendientes;
}
?>
