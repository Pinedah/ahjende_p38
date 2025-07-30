<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log de debugging
file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] REQUEST recibido: ' . print_r($_POST, true) . "\n", FILE_APPEND);

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
			echo respuestaExito(['timestamp' => date('Y-m-d H:i:s')], 'Controlador funcionando correctamente');
		break;

		case 'obtener_citas':
			$fecha_filtro = isset($_POST['fecha_filtro']) ? escape($_POST['fecha_filtro'], $connection) : null;
			$fecha_inicio = isset($_POST['fecha_inicio']) ? escape($_POST['fecha_inicio'], $connection) : null;
			$fecha_fin = isset($_POST['fecha_fin']) ? escape($_POST['fecha_fin'], $connection) : null;
			$id_ejecutivo = isset($_POST['id_ejecutivo']) ? intval($_POST['id_ejecutivo']) : null;
			$id_plantel = isset($_POST['id_plantel']) ? intval($_POST['id_plantel']) : null;
			$tipo_ejecutivo = isset($_POST['tipo_ejecutivo']) ? escape($_POST['tipo_ejecutivo'], $connection) : null;
			$incluir_planteles_asociados = isset($_POST['incluir_planteles_asociados']) && 
										($_POST['incluir_planteles_asociados'] === 'true' || $_POST['incluir_planteles_asociados'] === true || $_POST['incluir_planteles_asociados'] === 1 || $_POST['incluir_planteles_asociados'] === '1');
		
		// Log para debugging
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] FILTRO CITAS - Parámetros recibidos:' . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - fecha_inicio: ' . ($fecha_inicio ?: 'null') . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - fecha_fin: ' . ($fecha_fin ?: 'null') . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - id_ejecutivo: ' . ($id_ejecutivo ?: 'null') . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - id_plantel: ' . ($id_plantel ?: 'null') . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - tipo_ejecutivo: ' . ($tipo_ejecutivo ?: 'null') . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - incluir_planteles_asociados RAW: ' . (isset($_POST['incluir_planteles_asociados']) ? $_POST['incluir_planteles_asociados'] : 'not set') . "\n", FILE_APPEND);
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - incluir_planteles_asociados FINAL: ' . ($incluir_planteles_asociados ? 'true' : 'false') . "\n", FILE_APPEND);
			
			// Obtener todas las columnas de la tabla cita
			$queryColumnas = "SHOW COLUMNS FROM cita";
			$columnas = ejecutarConsulta($queryColumnas, $connection);
			
			$camposSelect = [];
			if ($columnas) {
				foreach ($columnas as $columna) {
					$camposSelect[] = 'c.' . $columna['Field'];
				}
			}
			
			// Agregar el campo del ejecutivo y plantel
			$camposSelect[] = 'e.nom_eje';
			$camposSelect[] = 'e.tipo as tipo_ejecutivo';
			$camposSelect[] = 'p.nom_pla';
			$selectFields = implode(', ', $camposSelect);
			
			// Construir la consulta base
			$whereConditions = ['c.eli_cit = 1'];
			
			// Manejar filtros de fecha
			if ($fecha_filtro) {
				// Filtro por fecha específica (compatibilidad con código anterior)
				$whereConditions[] = "c.cit_cit = '$fecha_filtro'";
			} elseif ($fecha_inicio || $fecha_fin) {
				// Filtro por rango de fechas
				if ($fecha_inicio && $fecha_fin) {
					$whereConditions[] = "c.cit_cit >= '$fecha_inicio' AND c.cit_cit <= '$fecha_fin'";
				} elseif ($fecha_inicio) {
					$whereConditions[] = "c.cit_cit >= '$fecha_inicio'";
				} elseif ($fecha_fin) {
					$whereConditions[] = "c.cit_cit <= '$fecha_fin'";
				}
			}
		// Manejo de filtro por ejecutivo
		if ($id_ejecutivo) {
			if ($incluir_planteles_asociados) {
				// Obtener todos los ejecutivos accesibles para este ejecutivo
				$ejecutivosAccesibles = obtenerEjecutivosAccesibles($id_ejecutivo, $connection, true);
				
				file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Ejecutivos accesibles: ' . implode(',', $ejecutivosAccesibles) . "\n", FILE_APPEND);
				
				if (!empty($ejecutivosAccesibles)) {
					$ejecutivosIds = implode(',', $ejecutivosAccesibles);
					$whereConditions[] = "c.id_eje2 IN ($ejecutivosIds)";
				} else {
					// Si no hay ejecutivos accesibles, solo mostrar propias
					$whereConditions[] = "c.id_eje2 = $id_ejecutivo";
				}
			} else {
				// Solo filtrar por el ejecutivo especificado (citas propias únicamente)
				$whereConditions[] = "c.id_eje2 = $id_ejecutivo";
				file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Solo citas propias del ejecutivo: ' . $id_ejecutivo . "\n", FILE_APPEND);
			}
		}
		
		// Manejo de filtro por plantel
		if ($id_plantel) {
			// NUEVA LÓGICA P25/P26: Filtrar por citas que pertenecen al plantel directamente
			// Esto permite ver citas que persisten en el plantel aún si el ejecutivo cambió
			$whereConditions[] = "c.pla_cit = $id_plantel";
			file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Filtro por plantel (nueva lógica): ' . $id_plantel . "\n", FILE_APPEND);
		}
		
		// PRÁCTICA 28: Filtro por tipo de ejecutivo
		if ($tipo_ejecutivo) {
			$whereConditions[] = "e.tipo = '$tipo_ejecutivo'";
			file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - P28: Filtro por tipo de ejecutivo: ' . $tipo_ejecutivo . "\n", FILE_APPEND);
		}
					$whereClause = implode(' AND ', $whereConditions);
		
		// Log para debugging
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - WHERE clause: ' . $whereClause . "\n", FILE_APPEND);
		
		if ($fecha_filtro || $fecha_inicio || $fecha_fin) {
			// Filtro por fecha específica o rango
			$query = "SELECT $selectFields 
					 FROM cita c
					 LEFT JOIN ejecutivo e ON c.id_eje2 = e.id_eje
					 LEFT JOIN plantel p ON c.pla_cit = p.id_pla
					 WHERE $whereClause
					 ORDER BY c.cit_cit ASC, c.hor_cit ASC";
		} else {
			// Todas las citas para búsqueda
			$query = "SELECT $selectFields 
					 FROM cita c
					 LEFT JOIN ejecutivo e ON c.id_eje2 = e.id_eje
					 LEFT JOIN plantel p ON c.pla_cit = p.id_pla
					 WHERE $whereClause
					 ORDER BY c.cit_cit DESC, c.hor_cit ASC";
		}
		
		// Log para debugging
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Query final: ' . $query . "\n", FILE_APPEND);

		$datos = ejecutarConsulta($query, $connection);

		if($datos !== false) {
			file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Citas encontradas: ' . count($datos) . "\n", FILE_APPEND);
			echo respuestaExito($datos, 'Citas obtenidas correctamente');
		} else {
			echo respuestaError('Error al consultar citas: ' . mysqli_error($connection) . ' Query: ' . $query);
		}
		break;

		case 'obtener_ejecutivos':
			$query = "SELECT id_eje, nom_eje, tipo FROM ejecutivo ORDER BY nom_eje ASC";
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Ejecutivos obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar ejecutivos: ' . mysqli_error($connection) . ' Query: ' . $query);
			}
		break;

		case 'obtener_planteles':
			$query = "SELECT id_pla, nom_pla FROM plantel ORDER BY nom_pla ASC";
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Planteles obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar planteles: ' . mysqli_error($connection) . ' Query: ' . $query);
			}
		break;

		case 'guardar_cita':
			// Obtener estructura de la tabla para inserción dinámica
			$estructuraQuery = "DESCRIBE cita";
			$columnas = ejecutarConsulta($estructuraQuery, $connection);
			
			if($columnas === false) {
				echo respuestaError('Error al obtener estructura de tabla');
				break;
			}
			
			$camposInsertar = [];
			$valoresInsertar = [];
			
			foreach($columnas as $columna) {
				$nombreCol = $columna['Field'];
				
				// Saltar campo auto-increment
				if($nombreCol === 'id_cit') continue;
				
				// Establecer eli_cit = 1 por defecto para nuevas citas
				if($nombreCol === 'eli_cit') {
					$camposInsertar[] = $nombreCol;
					$valoresInsertar[] = "1";
					continue;
				}
				
				if(isset($_POST[$nombreCol]) && $_POST[$nombreCol] !== '') {
					$valor = escape($_POST[$nombreCol], $connection);
					$camposInsertar[] = $nombreCol;
					$valoresInsertar[] = "'$valor'";
				} else {
					// Solo permitir NULL si la columna lo acepta
					if($columna['Null'] === 'YES') {
						$camposInsertar[] = $nombreCol;
						$valoresInsertar[] = "NULL";
					} else {
						// Para campos que no permiten NULL, usar valores por defecto
						$camposInsertar[] = $nombreCol;
						if(strpos($columna['Type'], 'int') !== false) {
							$valoresInsertar[] = "0";
						} else if(strpos($columna['Type'], 'date') !== false) {
							$valoresInsertar[] = "CURDATE()";
						} else if(strpos($columna['Type'], 'time') !== false) {
							$valoresInsertar[] = "CURTIME()";
						} else {
							$valoresInsertar[] = "''";
						}
					}
				}
			}
			
			if(empty($camposInsertar)) {
				echo respuestaError('No hay campos válidos para insertar');
				break;
			}
			
			$query = "INSERT INTO cita (" . implode(', ', $camposInsertar) . ") VALUES (" . implode(', ', $valoresInsertar) . ")";
			
			if(mysqli_query($connection, $query)) {
				$nuevo_id = mysqli_insert_id($connection);
				
				// Registrar en historial
				$nombreCita = isset($_POST['nom_cit']) ? $_POST['nom_cit'] : 'Sin nombre';
				$descripcion = "Se creó nueva cita: '$nombreCita'";
				registrarHistorial($connection, $nuevo_id, 'alta', $descripcion);
				
				echo respuestaExito(['id' => $nuevo_id], 'Cita guardada correctamente');
			} else {
				echo respuestaError('Error al guardar cita: ' . mysqli_error($connection) . ' Query: ' . $query);
			}
		break;

		case 'actualizar_cita':
			$campo = escape($_POST['campo'], $connection);
			$valor = $_POST['valor']; // No escapar aún
			$id_cit = escape($_POST['id_cit'], $connection);
			
			// Obtener valor anterior para el historial
			$queryAnterior = "SELECT $campo, nom_cit FROM cita WHERE id_cit = '$id_cit'";
			$anteriorResult = ejecutarConsulta($queryAnterior, $connection);
			$valorAnterior = $anteriorResult ? $anteriorResult[0][$campo] : '';
			$nombreCita = $anteriorResult ? ($anteriorResult[0]['nom_cit'] ?: 'Sin nombre') : 'Sin nombre';
			
			// Verificar que la columna existe en la tabla (permite columnas dinámicas)
			$queryCheck = "SHOW COLUMNS FROM cita WHERE Field = '$campo'";
			$existe = ejecutarConsulta($queryCheck, $connection);
			
			if (!$existe || empty($existe)) {
				echo respuestaError('Campo no válido para actualización');
				break;
			}
			
			$columnaInfo = $existe[0];
			
			// Manejar valores vacíos según las restricciones de la columna
			if ($valor === '' || $valor === null) {
				if ($columnaInfo['Null'] === 'YES') {
					$valorSQL = 'NULL';
				} else {
					// Para campos que no permiten NULL, usar valores por defecto
					if (strpos($columnaInfo['Type'], 'int') !== false) {
						$valorSQL = "0";
					} else {
						$valorSQL = "''";
					}
				}
			} else {
				$valorEscapado = escape($valor, $connection);
				$valorSQL = "'$valorEscapado'";
			}
			
			$query = "UPDATE cita SET $campo = $valorSQL WHERE id_cit = '$id_cit'";
			
			if(mysqli_query($connection, $query)) {
				// Registrar en historial solo si el valor cambió
				if($valorAnterior != $valor) {
					$campoLegible = strtoupper(str_replace('_', ' ', $campo));
					$valorAnteriorDisplay = $valorAnterior ?: '(vacío)';
					$valorNuevoDisplay = $valor ?: '(vacío)';
					$descripcion = "Se modificó $campoLegible de '$valorAnteriorDisplay' a '$valorNuevoDisplay' en la cita '$nombreCita'";
					registrarHistorial($connection, $id_cit, 'cambio', $descripcion);
				}
				
				echo respuestaExito(null, 'Cita actualizada correctamente');
			} else {
				echo respuestaError('Error al actualizar cita: ' . mysqli_error($connection) . ' Query: ' . $query);
			}
		break;

		case 'eliminar_cita':
			$id_cit = escape($_POST['id_cit'], $connection);
			
			if (!$id_cit) {
				echo respuestaError('ID de cita no proporcionado');
				break;
			}
			
			// Obtener información de la cita antes de eliminarla
			$queryInfo = "SELECT nom_cit FROM cita WHERE id_cit = '$id_cit' AND eli_cit = 1";
			$infoResult = ejecutarConsulta($queryInfo, $connection);
			$nombreCita = $infoResult ? ($infoResult[0]['nom_cit'] ?: 'Sin nombre') : 'Sin nombre';
			
			// Implementar eliminación suave (soft delete)
			$query = "UPDATE cita SET eli_cit = 0 WHERE id_cit = '$id_cit' AND eli_cit = 1";
			
			if(mysqli_query($connection, $query)) {
				if(mysqli_affected_rows($connection) > 0) {
					// Registrar en historial
					$descripcion = "Se eliminó (ocultó) la cita '$nombreCita'";
					registrarHistorial($connection, $id_cit, 'baja', $descripcion);
					
					echo respuestaExito(['id_eliminado' => $id_cit], 'Cita ocultada correctamente');
				} else {
					echo respuestaError('No se encontró la cita a eliminar o ya está eliminada');
				}
			} else {
				echo respuestaError('Error al eliminar cita: ' . mysqli_error($connection) . ' Query: ' . $query);
			}
		break;

		case 'obtener_estructura_tabla':
			$nombreTabla = 'cita';
			$query = "SHOW COLUMNS FROM $nombreTabla";
			$columnas = ejecutarConsulta($query, $connection);
			
			if($columnas !== false) {
				// Procesar columnas para el frontend
				$columnasConfig = [];
				
				foreach($columnas as $columna) {
					$campo = $columna['Field'];
					$tipo = $columna['Type'];
					
					// Ocultar el campo eli_cit de la interfaz (campo interno)
					if($campo === 'eli_cit') {
						continue;
					}
					
					// Configuración por defecto
					$config = [
						'key' => $campo,
						'header' => strtoupper(str_replace('_', ' ', $campo)),
						'type' => 'text',
						'width' => 120
					];
					
					// Configuraciones específicas por campo
					switch($campo) {
						case 'id_cit':
							$config['header'] = 'ID';
							$config['readOnly'] = true;
							$config['width'] = 60;
							break;
						case 'cit_cit':
							$config['header'] = 'FECHA';
							$config['type'] = 'date';
							$config['dateFormat'] = 'YYYY-MM-DD';
							$config['width'] = 120;
							break;
						case 'hor_cit':
							$config['header'] = 'HORA';
							$config['type'] = 'time';
							$config['timeFormat'] = 'HH:mm';
							$config['width'] = 100;
							break;
						case 'nom_cit':
							$config['header'] = 'NOMBRE';
							$config['width'] = 200;
							break;
						case 'tel_cit':
							$config['header'] = 'TELÉFONO';
							$config['width'] = 150;
							break;
						case 'id_eje2':
							$config['header'] = 'EJECUTIVO';
							$config['type'] = 'dropdown';
							$config['width'] = 180;
							break;
						case 'id_eje_admin':
							$config['header'] = 'ADMINISTRATIVO';
							$config['type'] = 'dropdown';
							$config['width'] = 180;
							break;
						case 'id_eje_admision':
							$config['header'] = 'ADMISIÓN';
							$config['type'] = 'dropdown';
							$config['width'] = 180;
							break;
						case 'est_cit':
							$config['header'] = 'ESTATUS';
							$config['type'] = 'dropdown';
							$config['width'] = 160;
							// Catálogo de estatus según Práctica 22
							$config['source'] = [
								'CITA AGENDADA',
								'INVASIÓN DE CICLO',
								'CITA REAGENDADA',
								'CITA NO ATENDIDA',
								'PAGO ESPERADO',
								'PERDIDO POR PRECIO',
								'PERDIDO POR HORARIO',
								'REGISTRO',
								'NO LE INTERESA',
								'ASESORÍA REALIZADA',
								'CITA CONFIRMADA'
							];
							break;
						case 'efe_cit':
							$config['header'] = 'EFECTIVIDAD';
							$config['type'] = 'dropdown';
							$config['width'] = 160;
							$config['source'] = [
								'CITA EFECTIVA',
								'CITA NO EFECTIVA'
							];
							break;
						case 'pla_cit':
							$config['header'] = 'PLANTEL';
							$config['type'] = 'dropdown';
							$config['width'] = 180;
							// Se cargará dinámicamente desde planteles
							break;
						default:
							// Detectar tipo automáticamente
							if(strpos($tipo, 'date') !== false) {
								$config['type'] = 'date';
								$config['dateFormat'] = 'YYYY-MM-DD';
							} elseif(strpos($tipo, 'time') !== false) {
								$config['type'] = 'time';
								$config['timeFormat'] = 'HH:mm';
							} elseif(strpos($tipo, 'int') !== false) {
								$config['type'] = 'numeric';
							} elseif(strpos($tipo, 'decimal') !== false || strpos($tipo, 'float') !== false) {
								$config['type'] = 'numeric';
							}
							break;
					}
					
					$columnasConfig[] = $config;
				}
				
				// Agregar columna de horario al inicio
				array_unshift($columnasConfig, [
					'key' => 'horario',
					'header' => 'HORARIO',
					'type' => 'text',
					'readOnly' => true,
					'className' => 'horario-column',
					'width' => 150
				]);
				
				echo respuestaExito($columnasConfig, 'Estructura de tabla obtenida correctamente');
			} else {
				echo respuestaError('Error al obtener estructura de tabla: ' . mysqli_error($connection));
			}
		break;

		case 'crear_nueva_columna':
			$nombreTabla = 'cita';
			$nombreNuevaColumna = isset($_POST['nombre_columna']) ? escape($_POST['nombre_columna'], $connection) : '';
			$tipoColumna = isset($_POST['tipo_columna']) ? escape($_POST['tipo_columna'], $connection) : 'VARCHAR(100)';
			
			if(empty($nombreNuevaColumna)) {
				echo respuestaError("Nombre de columna no proporcionado");
				break;
			}
			
			// Validar que el nombre no contenga caracteres especiales
			if(!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $nombreNuevaColumna)) {
				echo respuestaError("Nombre de columna no válido. Use solo letras, números y guiones bajos.");
				break;
			}
			
			$query = "ALTER TABLE $nombreTabla ADD COLUMN $nombreNuevaColumna $tipoColumna";

			if(mysqli_query($connection, $query)){
				echo respuestaExito([
					'nombre_columna' => $nombreNuevaColumna,
					'tipo_columna' => $tipoColumna
				], "Columna '$nombreNuevaColumna' agregada correctamente");
			}else{
				echo respuestaError("Error al crear la nueva columna: " . mysqli_error($connection));
			}
		break;

		case 'obtener_historial_cita':
			$id_cit = escape($_POST['id_cit'], $connection);
			
			if (!$id_cit) {
				echo respuestaError('ID de cita no proporcionado');
				break;
			}
			
			$historial = obtenerHistorialCita($connection, $id_cit);
			
			if($historial !== false) {
				echo respuestaExito($historial, 'Historial obtenido correctamente');
			} else {
				echo respuestaError('Error al obtener historial: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_comentarios':
			$id_cit = escape($_POST['id_cit'], $connection);
			
			if (!$id_cit) {
				echo respuestaError('ID de cita no proporcionado');
				break;
			}
			
			$comentarios = obtenerComentariosCita($id_cit, $connection);
			
			if($comentarios !== false) {
				echo respuestaExito($comentarios, 'Comentarios obtenidos correctamente');
			} else {
				echo respuestaError('Error al obtener comentarios: ' . mysqli_error($connection));
			}
		break;

		case 'guardar_comentario':
			$id_cit = escape($_POST['id_cit'], $connection);
			$fila = escape($_POST['fila'], $connection);
			$columna = escape($_POST['columna'], $connection);
			$contenido = escape($_POST['contenido'], $connection);
			$id_ejecutivo = escape($_POST['id_ejecutivo'], $connection);
			
			if (!$id_cit || !$fila || !$columna || !$contenido || !$id_ejecutivo) {
				echo respuestaError('Datos incompletos para guardar comentario');
				break;
			}
			
			$id_comentario = guardarComentario($id_cit, $fila, $columna, $contenido, $id_ejecutivo, $connection);
			
			if($id_comentario !== false) {
				echo respuestaExito(['id_comentario' => $id_comentario], 'Comentario guardado correctamente');
			} else {
				echo respuestaError('Error al guardar comentario: ' . mysqli_error($connection));
			}
		break;

		case 'eliminar_comentario':
			$id_comentario = escape($_POST['id_comentario'], $connection);
			
			if (!$id_comentario) {
				echo respuestaError('ID de comentario no proporcionado');
				break;
			}
			
			if(eliminarComentario($id_comentario, $connection)) {
				echo respuestaExito(null, 'Comentario eliminado correctamente');
			} else {
				echo respuestaError('Error al eliminar comentario: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_colores':
			$id_cit = escape($_POST['id_cit'], $connection);
			
			if (!$id_cit) {
				echo respuestaError('ID de cita no proporcionado');
				break;
			}
			
			$colores = obtenerColoresCita($id_cit, $connection);
			
			if($colores !== false) {
				echo respuestaExito($colores, 'Colores obtenidos correctamente');
			} else {
				echo respuestaError('Error al obtener colores: ' . mysqli_error($connection));
			}
		break;

		case 'guardar_color':
			$id_cit = escape($_POST['id_cit'], $connection);
			$fila = escape($_POST['fila'], $connection);
			$columna = escape($_POST['columna'], $connection);
			$color_fondo = escape($_POST['color_fondo'], $connection);
			$color_texto = isset($_POST['color_texto']) ? escape($_POST['color_texto'], $connection) : '#000000';
			$id_ejecutivo = escape($_POST['id_ejecutivo'], $connection);
			
			if (!$id_cit || !$fila || !$columna || !$color_fondo || !$id_ejecutivo) {
				echo respuestaError('Datos incompletos para guardar color');
				break;
			}
			
			$id_color = guardarColor($id_cit, $fila, $columna, $color_fondo, $color_texto, $id_ejecutivo, $connection);
			
			if($id_color !== false) {
				echo respuestaExito(['id_color' => $id_color], 'Color guardado correctamente');
			} else {
				echo respuestaError('Error al guardar color: ' . mysqli_error($connection));
			}
		break;

		case 'eliminar_color':
			$id_color = escape($_POST['id_color'], $connection);
			
			if (!$id_color) {
				echo respuestaError('ID de color no proporcionado');
				break;
			}
			
			if(eliminarColor($id_color, $connection)) {
				echo respuestaExito(null, 'Color eliminado correctamente');
			} else {
				echo respuestaError('Error al eliminar color: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_estadisticas_embudo':
			// Consulta para obtener estadísticas globales del embudo
			$totalCitas = 0;
			$citasEfectivas = 0;
			$registros = 0;
			
			// Obtener total de citas
			$queryTotal = "SELECT COUNT(*) as total FROM cita WHERE id_cit IS NOT NULL AND id_cit != ''";
			$resultTotal = mysqli_query($connection, $queryTotal);
			if ($resultTotal && $rowTotal = mysqli_fetch_assoc($resultTotal)) {
				$totalCitas = intval($rowTotal['total']);
			}
			
			// Obtener citas efectivas
			$queryEfectivas = "SELECT COUNT(*) as efectivas FROM cita 
							  WHERE efe_cit = 'CITA EFECTIVA' AND id_cit IS NOT NULL AND id_cit != ''";
			$resultEfectivas = mysqli_query($connection, $queryEfectivas);
			if ($resultEfectivas && $rowEfectivas = mysqli_fetch_assoc($resultEfectivas)) {
				$citasEfectivas = intval($rowEfectivas['efectivas']);
			}
			
			// Obtener registros
			$queryRegistros = "SELECT COUNT(*) as registros FROM cita 
							  WHERE est_cit = 'REGISTRO' AND id_cit IS NOT NULL AND id_cit != ''";
			$resultRegistros = mysqli_query($connection, $queryRegistros);
			if ($resultRegistros && $rowRegistros = mysqli_fetch_assoc($resultRegistros)) {
				$registros = intval($rowRegistros['registros']);
			}
			
			// Log para debugging
			file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] EMBUDO ESTADÍSTICAS - Total: ' . $totalCitas . ', Efectivas: ' . $citasEfectivas . ', Registros: ' . $registros . "\n", FILE_APPEND);
			
			echo respuestaExito([
				'total_citas' => $totalCitas,
				'citas_efectivas' => $citasEfectivas,
				'registros' => $registros
			], 'Estadísticas del embudo obtenidas correctamente');
		break;

		case 'obtener_citas_por_plantel':
			$id_plantel = isset($_POST['id_plantel']) ? intval($_POST['id_plantel']) : null;
			$fecha_inicio = isset($_POST['fecha_inicio']) ? escape($_POST['fecha_inicio'], $connection) : null;
			$fecha_fin = isset($_POST['fecha_fin']) ? escape($_POST['fecha_fin'], $connection) : null;
			$incluir_sin_ejecutivo = isset($_POST['incluir_sin_ejecutivo']) && $_POST['incluir_sin_ejecutivo'] === 'true';

			if (!$id_plantel) {
				echo respuestaError('ID de plantel requerido');
				break;
			}

			// Construir WHERE clause
			$whereConditions = ['c.eli_cit = 1', "c.pla_cit = $id_plantel"];

			// Agregar filtros de fecha
			if ($fecha_inicio) {
				$whereConditions[] = "c.cit_cit >= '$fecha_inicio'";
			}
			if ($fecha_fin) {
				$whereConditions[] = "c.cit_cit <= '$fecha_fin'";
			}

			// Filtrar por ejecutivos si se requiere
			if (!$incluir_sin_ejecutivo) {
				$whereConditions[] = "c.id_eje2 IS NOT NULL";
			}

			$whereClause = implode(' AND ', $whereConditions);

			$query = "SELECT c.*, e.nom_eje, p.nom_pla
					  FROM cita c
					  LEFT JOIN ejecutivo e ON c.id_eje2 = e.id_eje
					  LEFT JOIN plantel p ON c.pla_cit = p.id_pla
					  WHERE $whereClause
					  ORDER BY c.cit_cit ASC, c.hor_cit ASC";

			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Citas del plantel obtenidas correctamente');
			} else {
				echo respuestaError('Error al consultar citas del plantel: ' . mysqli_error($connection));
			}
		break;

		case 'contar_citas_por_plantel':
			$id_plantel = isset($_POST['id_plantel']) ? intval($_POST['id_plantel']) : null;
			$fecha_inicio = isset($_POST['fecha_inicio']) ? escape($_POST['fecha_inicio'], $connection) : null;
			$fecha_fin = isset($_POST['fecha_fin']) ? escape($_POST['fecha_fin'], $connection) : null;

			if (!$id_plantel) {
				echo respuestaError('ID de plantel requerido');
				break;
			}

			// Construir WHERE clause
			$whereConditions = ['c.eli_cit = 1', "c.pla_cit = $id_plantel"];

			// Agregar filtros de fecha
			if ($fecha_inicio) {
				$whereConditions[] = "c.cit_cit >= '$fecha_inicio'";
			}
			if ($fecha_fin) {
				$whereConditions[] = "c.cit_cit <= '$fecha_fin'";
			}

			$whereClause = implode(' AND ', $whereConditions);

			$query = "SELECT 
						COUNT(*) as total_citas,
						COUNT(CASE WHEN c.id_eje2 IS NOT NULL THEN 1 END) as citas_con_ejecutivo,
						COUNT(CASE WHEN c.id_eje2 IS NULL THEN 1 END) as citas_sin_ejecutivo,
						COUNT(CASE WHEN DATE(c.cit_cit) = CURDATE() THEN 1 END) as citas_hoy,
						COUNT(CASE WHEN DATE(c.cit_cit) >= CURDATE() THEN 1 END) as citas_futuras,
						COUNT(CASE WHEN DATE(c.cit_cit) < CURDATE() THEN 1 END) as citas_pasadas,
						p.nom_pla
					  FROM cita c
					  LEFT JOIN plantel p ON c.pla_cit = p.id_pla
					  WHERE $whereClause
					  GROUP BY c.pla_cit, p.nom_pla";

			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				// Si no hay datos, devolver estructura vacía
				if (empty($datos)) {
					$queryPlantel = "SELECT nom_pla FROM plantel WHERE id_pla = $id_plantel";
					$plantelResult = ejecutarConsulta($queryPlantel, $connection);
					$nomPlantel = $plantelResult ? $plantelResult[0]['nom_pla'] : 'Plantel desconocido';
					
					$datos = [[
						'total_citas' => 0,
						'citas_con_ejecutivo' => 0,
						'citas_sin_ejecutivo' => 0,
						'citas_hoy' => 0,
						'citas_futuras' => 0,
						'citas_pasadas' => 0,
						'nom_pla' => $nomPlantel
					]];
				}
				echo respuestaExito($datos[0], 'Conteo de citas del plantel obtenido correctamente');
			} else {
				echo respuestaError('Error al contar citas del plantel: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_estadisticas_planteles':
			$fecha_inicio = isset($_POST['fecha_inicio']) ? escape($_POST['fecha_inicio'], $connection) : null;
			$fecha_fin = isset($_POST['fecha_fin']) ? escape($_POST['fecha_fin'], $connection) : null;

			// Construir WHERE clause
			$whereConditions = ['c.eli_cit = 1'];

			// Agregar filtros de fecha
			if ($fecha_inicio) {
				$whereConditions[] = "c.cit_cit >= '$fecha_inicio'";
			}
			if ($fecha_fin) {
				$whereConditions[] = "c.cit_cit <= '$fecha_fin'";
			}

			$whereClause = implode(' AND ', $whereConditions);

			$query = "SELECT 
						p.id_pla,
						p.nom_pla,
						COUNT(c.id_cit) as total_citas,
						COUNT(CASE WHEN c.id_eje2 IS NOT NULL THEN 1 END) as citas_con_ejecutivo,
						COUNT(CASE WHEN c.id_eje2 IS NULL THEN 1 END) as citas_sin_ejecutivo,
						COUNT(CASE WHEN DATE(c.cit_cit) = CURDATE() THEN 1 END) as citas_hoy,
						COUNT(CASE WHEN DATE(c.cit_cit) >= CURDATE() THEN 1 END) as citas_futuras,
						COUNT(CASE WHEN DATE(c.cit_cit) < CURDATE() THEN 1 END) as citas_pasadas
					  FROM plantel p
					  LEFT JOIN cita c ON p.id_pla = c.pla_cit AND $whereClause
					  GROUP BY p.id_pla, p.nom_pla
					  ORDER BY p.nom_pla ASC";

			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Estadísticas de planteles obtenidas correctamente');
			} else {
				echo respuestaError('Error al obtener estadísticas de planteles: ' . mysqli_error($connection));
			}
		break;

		case 'migrar_cita_plantel':
			$id_cit = isset($_POST['id_cit']) ? intval($_POST['id_cit']) : null;
			$nuevo_plantel = isset($_POST['nuevo_plantel']) ? intval($_POST['nuevo_plantel']) : null;
			$motivo = isset($_POST['motivo']) ? escape($_POST['motivo'], $connection) : 'Migración manual';

			if (!$id_cit || !$nuevo_plantel) {
				echo respuestaError('ID de cita y nuevo plantel son requeridos');
				break;
			}

			// Obtener información actual de la cita
			$queryActual = "SELECT c.*, p.nom_pla as plantel_actual, pn.nom_pla as plantel_nuevo
							FROM cita c
							LEFT JOIN plantel p ON c.pla_cit = p.id_pla
							LEFT JOIN plantel pn ON pn.id_pla = $nuevo_plantel
							WHERE c.id_cit = $id_cit";
			
			$citaActual = ejecutarConsulta($queryActual, $connection);
			
			if (!$citaActual) {
				echo respuestaError('Cita no encontrada');
				break;
			}

			$cita = $citaActual[0];

			// Actualizar plantel de la cita
			$query = "UPDATE cita SET pla_cit = $nuevo_plantel WHERE id_cit = $id_cit";

			if(mysqli_query($connection, $query)) {
				// =====================================
				// NOTIFICACIÓN WEBSOCKET P25/P26
				// =====================================
				$plantel_anterior_id = $cita['pla_cit'];
				notificarCambioPlantelCita($id_cit, $plantel_anterior_id, $nuevo_plantel, $motivo);
				
				// Actualizar estadísticas de ambos planteles
				if ($plantel_anterior_id) {
					$estadisticasAnterior = obtenerEstadisticasPlantel($plantel_anterior_id, $connection);
					notificarActualizacionCitasPlantel($plantel_anterior_id, $cita['plantel_actual'], $estadisticasAnterior);
				}
				
				$estadisticasNuevo = obtenerEstadisticasPlantel($nuevo_plantel, $connection);
				notificarActualizacionCitasPlantel($nuevo_plantel, $cita['plantel_nuevo'], $estadisticasNuevo);
				
				// Registrar en historial
				$nombreCita = $cita['nom_cit'] ?: 'Sin nombre';
				$plantelAnterior = $cita['plantel_actual'] ?: 'Sin plantel';
				$plantelNuevo = $cita['plantel_nuevo'] ?: 'Plantel desconocido';
				$descripcion = "Se migró la cita '$nombreCita' del plantel '$plantelAnterior' al plantel '$plantelNuevo'. Motivo: $motivo";
				registrarHistorial($connection, $id_cit, 'migración', $descripcion);

				echo respuestaExito([
					'cita_id' => $id_cit,
					'plantel_anterior' => $plantelAnterior,
					'plantel_nuevo' => $plantelNuevo
				], 'Cita migrada correctamente');
			} else {
				echo respuestaError('Error al migrar cita: ' . mysqli_error($connection));
			}
		break;

		case 'desasociar_cita_ejecutivo':
			// =====================================
			// P26: DESASOCIAR CITA DE EJECUTIVO
			// =====================================
			$id_cit = isset($_POST['id_cit']) ? intval($_POST['id_cit']) : null;
			$motivo = isset($_POST['motivo']) ? escape($_POST['motivo'], $connection) : 'Desasociación por regla de negocio';
			$mantener_plantel = isset($_POST['mantener_plantel']) ? (bool)$_POST['mantener_plantel'] : true;

			if (!$id_cit) {
				echo respuestaError('ID de cita es requerido');
				break;
			}

			// Obtener información actual de la cita
			$queryActual = "SELECT c.*, e.nom_eje, p.nom_pla 
							FROM cita c
							LEFT JOIN ejecutivo e ON c.id_eje2 = e.id_eje
							LEFT JOIN plantel p ON c.pla_cit = p.id_pla
							WHERE c.id_cit = $id_cit";
			
			$citaActual = ejecutarConsulta($queryActual, $connection);
			
			if (!$citaActual) {
				echo respuestaError('Cita no encontrada');
				break;
			}

			$cita = $citaActual[0];
			$ejecutivo_anterior = $cita['id_eje2'];
			$nombre_ejecutivo = $cita['nom_eje'];

			if (!$ejecutivo_anterior) {
				echo respuestaError('La cita ya está desasociada de cualquier ejecutivo');
				break;
			}

			// Desasociar la cita del ejecutivo (pero mantener el plantel por P25)
			$query = "UPDATE cita SET id_eje2 = NULL WHERE id_cit = $id_cit";

			if(mysqli_query($connection, $query)) {
				// =====================================
				// NOTIFICACIÓN WEBSOCKET P26
				// =====================================
				notificarDisociacionCitaEjecutivo($id_cit, $ejecutivo_anterior, $motivo);
				
				// Actualizar estadísticas del plantel (ahora tendrá una cita sin ejecutivo más)
				if ($cita['pla_cit']) {
					$estadisticasPlantel = obtenerEstadisticasPlantel($cita['pla_cit'], $connection);
					notificarActualizacionCitasPlantel($cita['pla_cit'], $cita['nom_pla'], $estadisticasPlantel);
				}
				
				// Registrar en historial
				$nombreCita = $cita['nom_cit'] ?: 'Sin nombre';
				$descripcion = "Se desasoció la cita '$nombreCita' del ejecutivo '$nombre_ejecutivo'. El plantel se mantiene para persistencia. Motivo: $motivo";
				registrarHistorial($connection, $id_cit, 'desasociación', $descripcion);

				echo respuestaExito([
					'cita_id' => $id_cit,
					'ejecutivo_anterior' => $nombre_ejecutivo,
					'plantel_mantenido' => $cita['nom_pla'],
					'mensaje' => 'Cita desasociada correctamente. La cita permanece visible en el plantel.'
				], 'Cita desasociada del ejecutivo correctamente');
			} else {
				echo respuestaError('Error al desasociar cita: ' . mysqli_error($connection));
			}
		break;

		case 'reasociar_cita_ejecutivo':
			// =====================================
			// P26: REASOCIAR CITA A NUEVO EJECUTIVO
			// =====================================
			$id_cit = isset($_POST['id_cit']) ? intval($_POST['id_cit']) : null;
			$nuevo_ejecutivo = isset($_POST['nuevo_ejecutivo']) ? intval($_POST['nuevo_ejecutivo']) : null;
			$motivo = isset($_POST['motivo']) ? escape($_POST['motivo'], $connection) : 'Reasociación de cita';

			if (!$id_cit || !$nuevo_ejecutivo) {
				echo respuestaError('ID de cita y nuevo ejecutivo son requeridos');
				break;
			}

			// Verificar que el nuevo ejecutivo existe
			$queryEjecutivo = "SELECT nom_eje, id_pla FROM ejecutivo WHERE id_eje = $nuevo_ejecutivo AND eli_eje = 1";
			$ejecutivoInfo = ejecutarConsulta($queryEjecutivo, $connection);
			
			if (!$ejecutivoInfo) {
				echo respuestaError('El ejecutivo especificado no existe o está inactivo');
				break;
			}

			// Obtener información actual de la cita
			$queryActual = "SELECT c.*, p.nom_pla 
							FROM cita c
							LEFT JOIN plantel p ON c.pla_cit = p.id_pla
							WHERE c.id_cit = $id_cit";
			
			$citaActual = ejecutarConsulta($queryActual, $connection);
			
			if (!$citaActual) {
				echo respuestaError('Cita no encontrada');
				break;
			}

			$cita = $citaActual[0];
			$ejecutivo_anterior = $cita['id_eje2'];

			// Reasociar la cita al nuevo ejecutivo (mantener plantel original por P25)
			$query = "UPDATE cita SET id_eje2 = $nuevo_ejecutivo WHERE id_cit = $id_cit";

			if(mysqli_query($connection, $query)) {
				// =====================================
				// NOTIFICACIÓN WEBSOCKET P26
				// =====================================
				$mensaje_ws = [
					'tipo' => 'cita_reasociacion',
					'tabla' => 'cita',
					'accion' => 'reasociada',
					'datos' => [
						'id_cit' => $id_cit,
						'ejecutivo_anterior' => $ejecutivo_anterior,
						'nuevo_ejecutivo' => $nuevo_ejecutivo,
						'motivo' => $motivo,
						'mensaje' => "Cita #$id_cit fue reasociada al ejecutivo {$ejecutivoInfo[0]['nom_eje']}"
					]
				];
				enviarMensajeWebSocket($mensaje_ws);
				
				// Actualizar estadísticas del plantel
				if ($cita['pla_cit']) {
					$estadisticasPlantel = obtenerEstadisticasPlantel($cita['pla_cit'], $connection);
					notificarActualizacionCitasPlantel($cita['pla_cit'], $cita['nom_pla'], $estadisticasPlantel);
				}
				
				// Registrar en historial
				$nombreCita = $cita['nom_cit'] ?: 'Sin nombre';
				$descripcion = "Se reasignó la cita '$nombreCita' al ejecutivo '{$ejecutivoInfo[0]['nom_eje']}'. El plantel original se mantiene. Motivo: $motivo";
				registrarHistorial($connection, $id_cit, 'reasociación', $descripcion);

				echo respuestaExito([
					'cita_id' => $id_cit,
					'nuevo_ejecutivo' => $ejecutivoInfo[0]['nom_eje'],
					'plantel_mantenido' => $cita['nom_pla'],
					'mensaje' => 'Cita reasociada correctamente. El plantel original se mantiene.'
				], 'Cita reasociada correctamente');
			} else {
				echo respuestaError('Error al reasociar cita: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// P29: ACCIONES PARA PAPELERA DE CITAS
		// =====================================
		
		case 'obtener_ejecutivos_administrativos':
			// Obtener solo ejecutivos con tipo 'Administrativo'
			$query = "SELECT id_eje, nom_eje, tipo 
					 FROM ejecutivo 
					 WHERE tipo = 'Administrativo' AND eli_eje = 1 
					 ORDER BY nom_eje ASC";
			
			$datos = ejecutarConsulta($query, $connection);
			
			if($datos !== false) {
				file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] P29: Ejecutivos administrativos encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Ejecutivos administrativos obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar ejecutivos administrativos: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_citas_papelera':
			$id_ejecutivo_admin = escape($_POST['id_ejecutivo_admin'], $connection);
			
			if (!$id_ejecutivo_admin) {
				echo respuestaError('ID de ejecutivo administrativo no proporcionado');
				break;
			}
			
			// Verificar que el ejecutivo sea administrativo
			$queryVerificar = "SELECT tipo FROM ejecutivo WHERE id_eje = '$id_ejecutivo_admin' AND eli_eje = 1";
			$verificacion = ejecutarConsulta($queryVerificar, $connection);
			
			if (!$verificacion || $verificacion[0]['tipo'] !== 'Administrativo') {
				echo respuestaError('El ejecutivo especificado no es de tipo Administrativo o no existe');
				break;
			}
			
			// Obtener todas las columnas de la tabla cita
			$queryColumnas = "SHOW COLUMNS FROM cita";
			$columnas = ejecutarConsulta($queryColumnas, $connection);
			
			$camposSelect = [];
			if ($columnas) {
				foreach ($columnas as $columna) {
					$camposSelect[] = 'c.' . $columna['Field'];
				}
			}
			
			// Agregar campos adicionales
			$camposSelect[] = 'e.nom_eje';
			$camposSelect[] = 'e.tipo as tipo_ejecutivo';
			$camposSelect[] = 'p.nom_pla';
			$selectFields = implode(', ', $camposSelect);
			
			// Consulta para obtener citas eliminadas (eli_cit = 0)
			// Se pueden obtener citas eliminadas de cualquier ejecutivo para que el administrativo las vea
			$query = "SELECT $selectFields 
					 FROM cita c
					 LEFT JOIN ejecutivo e ON c.id_eje2 = e.id_eje
					 LEFT JOIN plantel p ON c.pla_cit = p.id_pla
					 WHERE c.eli_cit = 0
					 ORDER BY c.cit_cit DESC, c.hor_cit ASC";
			
			file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] P29: Query papelera: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);
			
			if($datos !== false) {
				file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] P29: Citas eliminadas encontradas: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Citas de la papelera obtenidas correctamente');
			} else {
				echo respuestaError('Error al consultar citas de la papelera: ' . mysqli_error($connection));
			}
		break;

		case 'restaurar_citas':
			$ids_citas_json = $_POST['ids_citas'] ?? '';
			
			if (!$ids_citas_json) {
				echo respuestaError('No se proporcionaron IDs de citas para restaurar');
				break;
			}
			
			$ids_citas = json_decode($ids_citas_json, true);
			
			if (!is_array($ids_citas) || empty($ids_citas)) {
				echo respuestaError('Lista de IDs de citas inválida');
				break;
			}
			
			$restauradas = 0;
			$errores = 0;
			$detalles = [];
			
			foreach ($ids_citas as $id_cit) {
				$id_cit_escaped = escape($id_cit, $connection);
				
				// Verificar que la cita existe y está eliminada
				$queryVerificar = "SELECT nom_cit, eli_cit FROM cita WHERE id_cit = '$id_cit_escaped'";
				$verificacion = ejecutarConsulta($queryVerificar, $connection);
				
				if (!$verificacion || empty($verificacion)) {
					$errores++;
					$detalles[] = "Cita ID $id_cit no encontrada";
					continue;
				}
				
				if ($verificacion[0]['eli_cit'] != 0) {
					$errores++;
					$detalles[] = "Cita ID $id_cit no está eliminada";
					continue;
				}
				
				// Restaurar la cita (cambiar eli_cit de 0 a 1)
				$queryRestaurar = "UPDATE cita SET eli_cit = 1 WHERE id_cit = '$id_cit_escaped'";
				
				if (mysqli_query($connection, $queryRestaurar)) {
					$restauradas++;
					$nombreCita = $verificacion[0]['nom_cit'] ?: 'Sin nombre';
					$detalles[] = "Restaurada: $nombreCita (ID: $id_cit)";
					
					// Registrar en historial
					registrarHistorial($connection, $id_cit, 'cambio', "Cita restaurada desde la papelera por ejecutivo administrativo", 'Sistema P29');
					
					file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] P29: Cita restaurada ID: ' . $id_cit . "\n", FILE_APPEND);
				} else {
					$errores++;
					$detalles[] = "Error al restaurar cita ID $id_cit: " . mysqli_error($connection);
				}
			}
			
			$resultado = [
				'restauradas' => $restauradas,
				'errores' => $errores,
				'detalles' => $detalles
			];
			
			if ($restauradas > 0) {
				echo respuestaExito($resultado, "Se restauraron $restauradas cita(s) exitosamente");
			} else {
				echo respuestaError("No se pudieron restaurar las citas: " . implode(', ', $detalles));
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
// FUNCIONES DE HISTORIAL DE CITAS
// =====================================

function registrarHistorial($connection, $id_cit, $tipo_movimiento, $descripcion, $responsable = null) {
	// Si no se proporciona responsable, seleccionar uno aleatorio
	if (!$responsable) {
		$queryEjecutivo = "SELECT nom_eje FROM ejecutivo ORDER BY RAND() LIMIT 1";
		$ejecutivoResult = ejecutarConsulta($queryEjecutivo, $connection);
		$responsable = $ejecutivoResult ? $ejecutivoResult[0]['nom_eje'] : 'Sistema';
	}
	
	$id_cit_escaped = escape($id_cit, $connection);
	$tipo_escaped = escape($tipo_movimiento, $connection);
	$desc_escaped = escape($descripcion, $connection);
	$resp_escaped = escape($responsable, $connection);
	
	$query = "INSERT INTO historial_cita (fec_his_cit, res_his_cit, mov_his_cit, des_his_cit, id_cit11) 
			  VALUES (NOW(), '$resp_escaped', '$tipo_escaped', '$desc_escaped', '$id_cit_escaped')";
	
	return mysqli_query($connection, $query);
}

function obtenerHistorialCita($connection, $id_cit) {
	$id_cit_escaped = escape($id_cit, $connection);
	
	$query = "SELECT * FROM historial_cita 
			  WHERE id_cit11 = '$id_cit_escaped' 
			  ORDER BY fec_his_cit DESC";
	
	return ejecutarConsulta($query, $connection);
}

// =====================================
// FUNCIONES DE PLANTELES ASOCIADOS
// =====================================

function obtenerEjecutivosAccesibles($id_ejecutivo, $connection, $incluir_planteles = false) {
	$ejecutivosAccesibles = [];
	
	// Log para debugging
	file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] obtenerEjecutivosAccesibles - id_ejecutivo: ' . $id_ejecutivo . ', incluir_planteles: ' . ($incluir_planteles ? 'true' : 'false') . "\n", FILE_APPEND);
	
	// 1. Incluir al ejecutivo principal siempre
	$ejecutivosAccesibles[] = $id_ejecutivo;
	
	// 2. Si se incluyen planteles asociados, obtener ejecutivos adicionales
	if ($incluir_planteles) {
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Obteniendo hijos recursivos...' . "\n", FILE_APPEND);
		
		// Obtener hijos recursivos del ejecutivo
		$hijosArbol = obtenerHijosRecursivos($id_ejecutivo, $connection);
		$ejecutivosAccesibles = array_merge($ejecutivosAccesibles, $hijosArbol);
		
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Hijos recursivos: ' . implode(',', $hijosArbol) . "\n", FILE_APPEND);
		
		// Obtener ejecutivos de planteles asociados
		$ejecutivosPlanteles = obtenerEjecutivosPlanteles($id_ejecutivo, $connection);
		$ejecutivosAccesibles = array_merge($ejecutivosAccesibles, $ejecutivosPlanteles);
		
		file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Ejecutivos de planteles: ' . implode(',', $ejecutivosPlanteles) . "\n", FILE_APPEND);
	}
	
	// Eliminar duplicados y retornar
	$resultado = array_unique($ejecutivosAccesibles);
	file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] - Resultado final: ' . implode(',', $resultado) . "\n", FILE_APPEND);
	
	return $resultado;
}

function obtenerHijosRecursivos($id_ejecutivo, $connection, $visitados = []) {
	$hijos = [];
	
	// Evitar recursión infinita
	if (in_array($id_ejecutivo, $visitados)) {
		return $hijos;
	}
	
	$visitados[] = $id_ejecutivo;
	
	// Obtener hijos directos
	$query = "SELECT id_eje FROM ejecutivo WHERE id_padre = $id_ejecutivo AND eli_eje = 1";
	$hijosDirectos = ejecutarConsulta($query, $connection);
	
	if ($hijosDirectos) {
		foreach ($hijosDirectos as $hijo) {
			$hijos[] = $hijo['id_eje'];
			// Recursivamente obtener hijos de este hijo
			$nietosHijos = obtenerHijosRecursivos($hijo['id_eje'], $connection, $visitados);
			$hijos = array_merge($hijos, $nietosHijos);
		}
	}
	
	return $hijos;
}

function obtenerEjecutivosPlanteles($id_ejecutivo, $connection) {
	$ejecutivosPlanteles = [];
	
	// Obtener planteles asociados al ejecutivo
	$queryPlanteles = "SELECT DISTINCT pe.id_pla 
					   FROM planteles_ejecutivo pe 
					   WHERE pe.id_eje = $id_ejecutivo";
	
	$plantelesAsociados = ejecutarConsulta($queryPlanteles, $connection);
	
	if ($plantelesAsociados) {
		foreach ($plantelesAsociados as $plantel) {
			$id_pla = $plantel['id_pla'];
			
			// Obtener todos los ejecutivos de este plantel
			$queryEjecutivos = "SELECT id_eje FROM ejecutivo WHERE id_pla = $id_pla AND eli_eje = 1";
			$ejecutivosPlantel = ejecutarConsulta($queryEjecutivos, $connection);
			
			if ($ejecutivosPlantel) {
				foreach ($ejecutivosPlantel as $ejecutivo) {
					$ejecutivosPlanteles[] = $ejecutivo['id_eje'];
					
					// También incluir el árbol de cada ejecutivo del plantel
					$hijosEjecutivo = obtenerHijosRecursivos($ejecutivo['id_eje'], $connection);
					$ejecutivosPlanteles = array_merge($ejecutivosPlanteles, $hijosEjecutivo);
				}
			}
		}
	}
	
	return $ejecutivosPlanteles;
}

// =====================================
// FUNCIONES PARA COMENTARIOS
// =====================================

// Función para obtener comentarios de una cita
function obtenerComentariosCita($id_cit, $connection) {
	$query = "SELECT cc.*, e.nom_eje 
			 FROM comentarios_cita cc 
			 LEFT JOIN ejecutivo e ON cc.id_eje_com = e.id_eje 
			 WHERE cc.id_cit = '$id_cit' AND cc.eli_com = 1 
			 ORDER BY cc.fecha_com ASC";
	
	return ejecutarConsulta($query, $connection);
}

// Función para crear/actualizar comentario
function guardarComentario($id_cit, $fila, $columna, $contenido, $id_ejecutivo, $connection) {
	// Verificar si ya existe un comentario para esta celda
	$queryExistente = "SELECT id_com FROM comentarios_cita 
					   WHERE id_cit = '$id_cit' AND fila_com = '$fila' AND columna_com = '$columna' AND eli_com = 1";
	$existente = ejecutarConsulta($queryExistente, $connection);
	
	if ($existente && count($existente) > 0) {
		// Actualizar comentario existente
		$id_com = $existente[0]['id_com'];
		$query = "UPDATE comentarios_cita 
				 SET contenido_com = '$contenido', fecha_edit_com = CURRENT_TIMESTAMP 
				 WHERE id_com = '$id_com'";
	} else {
		// Crear nuevo comentario
		$query = "INSERT INTO comentarios_cita (id_cit, fila_com, columna_com, contenido_com, id_eje_com) 
				 VALUES ('$id_cit', '$fila', '$columna', '$contenido', '$id_ejecutivo')";
	}
	
	if (mysqli_query($connection, $query)) {
		if (!$existente || count($existente) == 0) {
			return mysqli_insert_id($connection);
		} else {
			return $existente[0]['id_com'];
		}
	} else {
		return false;
	}
}

// Función para eliminar comentario
function eliminarComentario($id_com, $connection) {
	$query = "UPDATE comentarios_cita SET eli_com = 0 WHERE id_com = '$id_com'";
	return mysqli_query($connection, $query);
}

// =====================================
// FUNCIONES PARA COLORES DE CELDAS
// =====================================

// Función para obtener colores de una cita
function obtenerColoresCita($id_cit, $connection) {
	$query = "SELECT cc.*, e.nom_eje 
			 FROM colores_celda cc 
			 LEFT JOIN ejecutivo e ON cc.id_eje_color = e.id_eje 
			 WHERE cc.id_cit = '$id_cit' AND cc.activo = 1 
			 ORDER BY cc.fecha_color ASC";
	
	return ejecutarConsulta($query, $connection);
}

// Función para crear/actualizar color de celda
function guardarColor($id_cit, $fila, $columna, $color_fondo, $color_texto, $id_ejecutivo, $connection) {
	// Verificar si ya existe un color para esta celda
	$queryExistente = "SELECT id_color FROM colores_celda 
					   WHERE id_cit = '$id_cit' AND fila_color = '$fila' AND columna_color = '$columna' AND activo = 1";
	$existente = ejecutarConsulta($queryExistente, $connection);
	
	if ($existente && count($existente) > 0) {
		// Actualizar color existente
		$id_color = $existente[0]['id_color'];
		$query = "UPDATE colores_celda 
				 SET color_fondo = '$color_fondo', color_texto = '$color_texto', 
				 	 id_eje_color = '$id_ejecutivo', fecha_modificacion = CURRENT_TIMESTAMP 
				 WHERE id_color = '$id_color'";
		
		if (mysqli_query($connection, $query)) {
			return $id_color;
		} else {
			return false;
		}
	} else {
		// Crear nuevo color
		$query = "INSERT INTO colores_celda (id_cit, fila_color, columna_color, color_fondo, color_texto, id_eje_color) 
				 VALUES ('$id_cit', '$fila', '$columna', '$color_fondo', '$color_texto', '$id_ejecutivo')";
		
		if (mysqli_query($connection, $query)) {
			return mysqli_insert_id($connection);
		} else {
			return false;
		}
	}
}

// Función para eliminar color de celda
function eliminarColor($id_color, $connection) {
	$query = "UPDATE colores_celda SET activo = 0 WHERE id_color = '$id_color'";
	return mysqli_query($connection, $query);
}
?>
