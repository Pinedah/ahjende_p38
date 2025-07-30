<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log de debugging
file_put_contents('debug_equipos.log', '[' . date('Y-m-d H:i:s') . '] REQUEST recibido: ' . print_r($_POST, true) . "\n", FILE_APPEND);

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
			echo respuestaExito(['timestamp' => date('Y-m-d H:i:s')], 'Controlador de equipos funcionando correctamente');
		break;

		// =====================================
		// OPERACIONES DE EQUIPOS
		// =====================================

		case 'obtener_equipos':
			$query = "SELECT * FROM vista_equipos_estadisticas ORDER BY nom_equipo";
			
			file_put_contents('debug_equipos.log', '[' . date('Y-m-d H:i:s') . '] Query equipos: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				file_put_contents('debug_equipos.log', '[' . date('Y-m-d H:i:s') . '] Equipos encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Equipos obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_equipos.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL equipos: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar equipos: ' . $error);
			}
		break;

		case 'crear_equipo':
			$nom_equipo = escape($_POST['nom_equipo'], $connection);
			$des_equipo = isset($_POST['des_equipo']) ? escape($_POST['des_equipo'], $connection) : '';
			$emoji_equipo = isset($_POST['emoji_equipo']) ? escape($_POST['emoji_equipo'], $connection) : '🔵';
			$id_cad = isset($_POST['id_cad']) ? intval($_POST['id_cad']) : 1; // Por defecto cadena AHJ ENDE
			$id_eje_creador = isset($_POST['id_eje_creador']) ? intval($_POST['id_eje_creador']) : 1;
			
			if(empty($nom_equipo)) {
				echo respuestaError('Nombre del equipo es obligatorio');
				break;
			}
			
			$query = "INSERT INTO equipo (nom_equipo, des_equipo, emoji_equipo, id_cad, id_eje_creador) 
					  VALUES ('$nom_equipo', '$des_equipo', '$emoji_equipo', $id_cad, $id_eje_creador)";
			
			file_put_contents('debug_equipos.log', '[' . date('Y-m-d H:i:s') . '] Query crear equipo: ' . $query . "\n", FILE_APPEND);
			
			if(mysqli_query($connection, $query)) {
				$nuevo_id = mysqli_insert_id($connection);
				
				// Agregar al creador como responsable del equipo
				$query_responsable = "INSERT INTO ejecutivo_equipo (id_eje, id_equipo, es_responsable, notas) 
									  VALUES ($id_eje_creador, $nuevo_id, 1, 'Creador y responsable inicial del equipo')";
				mysqli_query($connection, $query_responsable);
				
				echo respuestaExito(['id_equipo' => $nuevo_id], 'Equipo creado correctamente');
			} else {
				echo respuestaError('Error al crear equipo: ' . mysqli_error($connection));
			}
		break;

		case 'actualizar_equipo':
			$id_equipo = intval($_POST['id_equipo']);
			$nom_equipo = escape($_POST['nom_equipo'], $connection);
			$des_equipo = isset($_POST['des_equipo']) ? escape($_POST['des_equipo'], $connection) : '';
			$emoji_equipo = isset($_POST['emoji_equipo']) ? escape($_POST['emoji_equipo'], $connection) : '🔵';
			$activo_equipo = isset($_POST['activo_equipo']) ? intval($_POST['activo_equipo']) : 1;
			
			if(empty($nom_equipo) || !$id_equipo) {
				echo respuestaError('Nombre del equipo e ID son obligatorios');
				break;
			}
			
			$query = "UPDATE equipo SET 
						nom_equipo = '$nom_equipo',
						des_equipo = '$des_equipo',
						emoji_equipo = '$emoji_equipo',
						activo_equipo = $activo_equipo
					  WHERE id_equipo = $id_equipo";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito(['id_equipo' => $id_equipo], 'Equipo actualizado correctamente');
			} else {
				echo respuestaError('Error al actualizar equipo: ' . mysqli_error($connection));
			}
		break;

		case 'eliminar_equipo':
			$id_equipo = intval($_POST['id_equipo']);
			
			if(!$id_equipo) {
				echo respuestaError('ID del equipo es obligatorio');
				break;
			}
			
			// Primero eliminar todas las relaciones de ejecutivos
			$query_relaciones = "DELETE FROM ejecutivo_equipo WHERE id_equipo = $id_equipo";
			mysqli_query($connection, $query_relaciones);
			
			// Luego eliminar el equipo
			$query = "DELETE FROM equipo WHERE id_equipo = $id_equipo";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito(['id_equipo' => $id_equipo], 'Equipo eliminado correctamente');
			} else {
				echo respuestaError('Error al eliminar equipo: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// OPERACIONES DE EJECUTIVOS EN EQUIPOS
		// =====================================

		case 'obtener_ejecutivos_equipo':
			$id_equipo = intval($_POST['id_equipo']);
			
			if(!$id_equipo) {
				echo respuestaError('ID del equipo es obligatorio');
				break;
			}
			
			$query = "SELECT e.id_eje, e.nom_eje, e.tel_eje, e.fot_eje, p.nom_pla,
						     ee.es_responsable, ee.fec_ingreso, ee.notas, ee.activo
					  FROM ejecutivo_equipo ee
					  JOIN ejecutivo e ON ee.id_eje = e.id_eje
					  LEFT JOIN plantel p ON e.id_pla = p.id_pla
					  WHERE ee.id_equipo = $id_equipo AND ee.activo = 1 AND e.eli_eje = 1
					  ORDER BY ee.es_responsable DESC, e.nom_eje ASC";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Ejecutivos del equipo obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar ejecutivos del equipo: ' . mysqli_error($connection));
			}
		break;

		case 'agregar_ejecutivo_equipo':
			$id_eje = intval($_POST['id_eje']);
			$id_equipo = intval($_POST['id_equipo']);
			$es_responsable = isset($_POST['es_responsable']) ? intval($_POST['es_responsable']) : 0;
			$notas = isset($_POST['notas']) ? escape($_POST['notas'], $connection) : '';
			
			if(!$id_eje || !$id_equipo) {
				echo respuestaError('ID del ejecutivo y equipo son obligatorios');
				break;
			}
			
			// Verificar que no esté ya en el equipo
			$query_verificar = "SELECT COUNT(*) as count FROM ejecutivo_equipo 
								WHERE id_eje = $id_eje AND id_equipo = $id_equipo AND activo = 1";
			$resultado = ejecutarConsulta($query_verificar, $connection);
			
			if($resultado && $resultado[0]['count'] > 0) {
				echo respuestaError('El ejecutivo ya pertenece a este equipo');
				break;
			}
			
			$query = "INSERT INTO ejecutivo_equipo (id_eje, id_equipo, es_responsable, notas) 
					  VALUES ($id_eje, $id_equipo, $es_responsable, '$notas')";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito(['id_eje' => $id_eje, 'id_equipo' => $id_equipo], 'Ejecutivo agregado al equipo correctamente');
			} else {
				echo respuestaError('Error al agregar ejecutivo al equipo: ' . mysqli_error($connection));
			}
		break;

		case 'remover_ejecutivo_equipo':
			$id_eje = intval($_POST['id_eje']);
			$id_equipo = intval($_POST['id_equipo']);
			
			if(!$id_eje || !$id_equipo) {
				echo respuestaError('ID del ejecutivo y equipo son obligatorios');
				break;
			}
			
			$query = "UPDATE ejecutivo_equipo 
					  SET activo = 0, fec_salida = NOW() 
					  WHERE id_eje = $id_eje AND id_equipo = $id_equipo AND activo = 1";
			
			if(mysqli_query($connection, $query)) {
				if(mysqli_affected_rows($connection) > 0) {
					echo respuestaExito(['id_eje' => $id_eje, 'id_equipo' => $id_equipo], 'Ejecutivo removido del equipo correctamente');
				} else {
					echo respuestaError('El ejecutivo no pertenece a este equipo o ya fue removido');
				}
			} else {
				echo respuestaError('Error al remover ejecutivo del equipo: ' . mysqli_error($connection));
			}
		break;

		case 'cambiar_rol_ejecutivo':
			$id_eje = intval($_POST['id_eje']);
			$id_equipo = intval($_POST['id_equipo']);
			$es_responsable = intval($_POST['es_responsable']);
			
			if(!$id_eje || !$id_equipo) {
				echo respuestaError('ID del ejecutivo y equipo son obligatorios');
				break;
			}
			
			$query = "UPDATE ejecutivo_equipo 
					  SET es_responsable = $es_responsable 
					  WHERE id_eje = $id_eje AND id_equipo = $id_equipo AND activo = 1";
			
			if(mysqli_query($connection, $query)) {
				if(mysqli_affected_rows($connection) > 0) {
					$rol = $es_responsable ? 'Responsable' : 'Miembro';
					echo respuestaExito(['id_eje' => $id_eje, 'id_equipo' => $id_equipo, 'rol' => $rol], 'Rol del ejecutivo actualizado correctamente');
				} else {
					echo respuestaError('No se pudo actualizar el rol del ejecutivo');
				}
			} else {
				echo respuestaError('Error al cambiar rol del ejecutivo: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// CONSULTAS ESPECIALES
		// =====================================

		case 'obtener_ejecutivos_disponibles':
			// Obtener ejecutivos que NO están en un equipo específico
			$id_equipo = isset($_POST['id_equipo']) ? intval($_POST['id_equipo']) : 0;
			
			$condicion_equipo = '';
			if($id_equipo > 0) {
				$condicion_equipo = "AND e.id_eje NOT IN (
										SELECT ee.id_eje 
										FROM ejecutivo_equipo ee 
										WHERE ee.id_equipo = $id_equipo AND ee.activo = 1
									)";
			}
			
			$query = "SELECT e.id_eje, e.nom_eje, e.tel_eje, p.nom_pla, e.tipo
					  FROM ejecutivo e
					  LEFT JOIN plantel p ON e.id_pla = p.id_pla
					  WHERE e.eli_eje = 1 $condicion_equipo
					  ORDER BY e.nom_eje ASC";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Ejecutivos disponibles obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar ejecutivos disponibles: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_vista_equipos':
			// Vista completa de todos los ejecutivos con sus equipos
			$query = "SELECT * FROM vista_ejecutivos_equipos ORDER BY nom_eje, nom_equipo";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Vista de equipos obtenida correctamente');
			} else {
				echo respuestaError('Error al obtener vista de equipos: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_equipos_ejecutivo':
			// Obtener todos los equipos de un ejecutivo específico
			$id_eje = intval($_POST['id_eje']);
			
			if(!$id_eje) {
				echo respuestaError('ID del ejecutivo es obligatorio');
				break;
			}
			
			$query = "SELECT eq.id_equipo, eq.nom_equipo, eq.emoji_equipo, eq.des_equipo,
						     ee.es_responsable, ee.fec_ingreso, ee.notas
					  FROM ejecutivo_equipo ee
					  JOIN equipo eq ON ee.id_equipo = eq.id_equipo
					  WHERE ee.id_eje = $id_eje AND ee.activo = 1 AND eq.activo_equipo = 1
					  ORDER BY ee.es_responsable DESC, eq.nom_equipo ASC";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Equipos del ejecutivo obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar equipos del ejecutivo: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// OPERACIONES DE CADENAS
		// =====================================

		case 'obtener_cadenas':
			$query = "SELECT * FROM cadena WHERE eli_cad = 1 ORDER BY nom_cad";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Cadenas obtenidas correctamente');
			} else {
				echo respuestaError('Error al consultar cadenas: ' . mysqli_error($connection));
			}
		break;

		default:
			echo respuestaError('Acción no válida');
		break;
	}

	mysqli_close($connection);
	exit;
}

// Si no es POST, mostrar información
echo respuestaError('Método no permitido. Use POST.');
?>
