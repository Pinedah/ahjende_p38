<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log de debugging
file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] REQUEST recibido: ' . print_r($_POST, true) . "\n", FILE_APPEND);

include '../inc/conexion.php';
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
			echo respuestaExito(['timestamp' => date('Y-m-d H:i:s')], 'Controlador de e-learning funcionando correctamente');
		break;

		case 'obtener_cursos':
			$query = "SELECT c.id_curso, c.nom_curso, c.des_curso, c.fec_creacion_curso, c.id_eje_creador,
					         e.nom_eje,
					         (SELECT COUNT(*) FROM clase cl WHERE cl.id_curso = c.id_curso AND cl.eli_clase = 1) as total_clases
					  FROM curso c
					  LEFT JOIN ejecutivo e ON c.id_eje_creador = e.id_eje
					  WHERE c.eli_curso = 1
					  ORDER BY c.fec_creacion_curso DESC";
			
			file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Query cursos: ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Cursos encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Cursos obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL cursos: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar cursos: ' . $error);
			}
		break;

		case 'obtener_clases_curso':
			$id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;
			
			if (!$id_curso) {
				echo respuestaError('ID de curso requerido');
				break;
			}
			
			$query = "SELECT cl.id_clase, cl.tit_clase, cl.des_clase, cl.ord_clase,
					         e.nom_eje as creador_clase, cl.fec_creacion_clase
					  FROM clase cl
					  JOIN ejecutivo e ON cl.id_eje_creador = e.id_eje
					  WHERE cl.id_curso = $id_curso AND cl.eli_clase = 1
					  ORDER BY cl.ord_clase ASC";
			
			file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Query clases curso ' . $id_curso . ': ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Clases encontradas: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($datos, 'Clases obtenidas correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL clases: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar clases: ' . $error);
			}
		break;

		case 'obtener_contenido_clase':
			$id_clase = isset($_POST['id_clase']) ? intval($_POST['id_clase']) : 0;
			
			if (!$id_clase) {
				echo respuestaError('ID de clase requerido');
				break;
			}
			
			// Obtener información de la clase y curso
			$query_clase = "SELECT cl.tit_clase, cl.des_clase, cl.fec_creacion_clase,
							       e_clase.nom_eje as creador_clase,
							       c.nom_curso, e_curso.nom_eje as creador_curso, c.fec_creacion_curso
							FROM clase cl
							JOIN curso c ON cl.id_curso = c.id_curso
							JOIN ejecutivo e_clase ON cl.id_eje_creador = e_clase.id_eje
							JOIN ejecutivo e_curso ON c.id_eje_creador = e_curso.id_eje
							WHERE cl.id_clase = $id_clase AND cl.eli_clase = 1";
			
			$info_clase = ejecutarConsulta($query_clase, $connection);
			
			if (!$info_clase || count($info_clase) === 0) {
				echo respuestaError('Clase no encontrada');
				break;
			}
			
			// Obtener contenido de la clase
			$query_contenido = "SELECT con.id_contenido, con.tit_contenido, con.tip_contenido,
								       con.arc_contenido, con.url_contenido, con.des_contenido,
								       con.ord_contenido, con.fec_creacion_contenido,
								       e.nom_eje as creador_contenido
								FROM contenido con
								JOIN ejecutivo e ON con.id_eje_creador = e.id_eje
								WHERE con.id_clase = $id_clase AND con.eli_contenido = 1
								ORDER BY con.ord_contenido ASC";
			
			file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Query contenido clase ' . $id_clase . ': ' . $query_contenido . "\n", FILE_APPEND);
			
			$contenidos = ejecutarConsulta($query_contenido, $connection);

			if($contenidos !== false) {
				$resultado = [
					'clase' => $info_clase[0],
					'contenidos' => $contenidos
				];
				
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Contenidos encontrados: ' . count($contenidos) . "\n", FILE_APPEND);
				echo respuestaExito($resultado, 'Contenido obtenido correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL contenido: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar contenido: ' . $error);
			}
		break;

		case 'obtener_comentarios_contenido':
			$id_contenido = isset($_POST['id_contenido']) ? intval($_POST['id_contenido']) : 0;
			
			if (!$id_contenido) {
				echo respuestaError('ID de contenido requerido');
				break;
			}
			
			$query = "SELECT ce.id_comentario, ce.tex_comentario, ce.fec_comentario,
					         ce.id_comentario_padre, e.nom_eje as autor_comentario,
					         e.id_eje as id_autor
					  FROM comentario_elearning ce
					  JOIN ejecutivo e ON ce.id_eje_comentario = e.id_eje
					  WHERE ce.id_contenido = $id_contenido AND ce.eli_comentario = 1
					  ORDER BY COALESCE(ce.id_comentario_padre, ce.id_comentario), ce.fec_comentario ASC";
			
			file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Query comentarios contenido ' . $id_contenido . ': ' . $query . "\n", FILE_APPEND);
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				// Organizar comentarios en estructura jerárquica
				$comentarios_organizados = organizarComentariosJerarquicos($datos);
				
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Comentarios encontrados: ' . count($datos) . "\n", FILE_APPEND);
				echo respuestaExito($comentarios_organizados, 'Comentarios obtenidos correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL comentarios: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al consultar comentarios: ' . $error);
			}
		break;

		case 'agregar_comentario':
			$id_contenido = isset($_POST['id_contenido']) ? intval($_POST['id_contenido']) : 0;
			$tex_comentario = isset($_POST['comentario']) ? escape($_POST['comentario'], $connection) : '';
			$id_eje_comentario = isset($_POST['id_ejecutivo']) ? intval($_POST['id_ejecutivo']) : 1; // Default user
			$id_comentario_padre = isset($_POST['id_comentario_padre']) && $_POST['id_comentario_padre'] !== '' ? intval($_POST['id_comentario_padre']) : null;
			
			if (!$id_contenido || !$tex_comentario) {
				echo respuestaError('Datos requeridos: contenido y comentario');
				break;
			}
			
			$valores_padre = $id_comentario_padre ? "'$id_comentario_padre'" : 'NULL';
			
			$query = "INSERT INTO comentario_elearning (id_contenido, tex_comentario, id_eje_comentario, id_comentario_padre)
					  VALUES ($id_contenido, '$tex_comentario', $id_eje_comentario, $valores_padre)";
			
			file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Query agregar comentario: ' . $query . "\n", FILE_APPEND);
			
			if (mysqli_query($connection, $query)) {
				$nuevo_id = mysqli_insert_id($connection);
				
				// Obtener el comentario recién creado con información del autor
				$query_nuevo = "SELECT ce.id_comentario, ce.tex_comentario, ce.fec_comentario,
								       ce.id_comentario_padre, e.nom_eje as autor_comentario,
								       e.id_eje as id_autor
								FROM comentario_elearning ce
								JOIN ejecutivo e ON ce.id_eje_comentario = e.id_eje
								WHERE ce.id_comentario = $nuevo_id";
				
				$comentario_nuevo = ejecutarConsulta($query_nuevo, $connection);
				
				if ($comentario_nuevo && count($comentario_nuevo) > 0) {
					file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Comentario agregado exitosamente ID: ' . $nuevo_id . "\n", FILE_APPEND);
					echo respuestaExito($comentario_nuevo[0], 'Comentario agregado correctamente');
				} else {
					echo respuestaExito(['id' => $nuevo_id], 'Comentario agregado correctamente');
				}
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL agregar comentario: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al agregar comentario: ' . $error);
			}
		break;

		// =====================================
		// CASOS PRINCIPALES PARA VISUALIZACIÓN
		// =====================================

		case 'subir_contenido':
			$id_clase = isset($_POST['id_clase']) ? intval($_POST['id_clase']) : 0;
			$tit_contenido = isset($_POST['titulo']) ? escape($_POST['titulo'], $connection) : '';
			$tip_contenido = isset($_POST['tipo']) ? escape($_POST['tipo'], $connection) : '';
			$des_contenido = isset($_POST['descripcion']) ? escape($_POST['descripcion'], $connection) : '';
			$ord_contenido = isset($_POST['orden']) ? intval($_POST['orden']) : 1;
			$id_eje_creador = isset($_POST['id_ejecutivo']) ? intval($_POST['id_ejecutivo']) : 1;
			
			$arc_contenido = null;
			$url_contenido = null;
			
			if (!$id_clase || !$tit_contenido || !$tip_contenido) {
				echo respuestaError('Clase, título y tipo de contenido requeridos');
				break;
			}
			
			// Manejar archivo o URL según el tipo
			if ($tip_contenido === 'video_youtube') {
				$url_contenido = isset($_POST['url']) ? escape($_POST['url'], $connection) : '';
				if (!$url_contenido) {
					echo respuestaError('URL requerida para videos de YouTube');
					break;
				}
			} else {
				// Manejar subida de archivo
				if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
					$archivo = $_FILES['archivo'];
					$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
					
					// Validar tipos de archivo
					$extensiones_permitidas = [
						'video_archivo' => ['mp4'],
						'audio' => ['mp3'],
						'pdf' => ['pdf'],
						'imagen' => ['jpg', 'jpeg', 'png']
					];
					
					if (!isset($extensiones_permitidas[$tip_contenido]) || 
						!in_array($extension, $extensiones_permitidas[$tip_contenido])) {
						echo respuestaError('Tipo de archivo no válido para este tipo de contenido');
						break;
					}
					
					// Generar nombre único para el archivo
					$timestamp = time();
					$hash = md5($archivo['name'] . $timestamp);
					$arc_contenido = "elearning_{$tip_contenido}_{$timestamp}_{$hash}.{$extension}";
					
					$ruta_destino = '../uploads/' . $arc_contenido;
					
					if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
						echo respuestaError('Error al subir el archivo');
						break;
					}
				} else {
					echo respuestaError('Archivo requerido para este tipo de contenido');
					break;
				}
			}
			
			// Preparar valores para la query
			$arc_valor = $arc_contenido ? "'$arc_contenido'" : 'NULL';
			$url_valor = $url_contenido ? "'$url_contenido'" : 'NULL';
			
			$query = "INSERT INTO contenido (id_clase, tit_contenido, tip_contenido, arc_contenido, url_contenido, des_contenido, ord_contenido, id_eje_creador)
					  VALUES ($id_clase, '$tit_contenido', '$tip_contenido', $arc_valor, $url_valor, '$des_contenido', $ord_contenido, $id_eje_creador)";
			
			file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Query crear contenido: ' . $query . "\n", FILE_APPEND);
			
			if (mysqli_query($connection, $query)) {
				$nuevo_id = mysqli_insert_id($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Contenido creado exitosamente ID: ' . $nuevo_id . "\n", FILE_APPEND);
				echo respuestaExito(['id' => $nuevo_id, 'archivo' => $arc_contenido], 'Contenido subido correctamente');
			} else {
				$error = mysqli_error($connection);
				file_put_contents('debug_elearning.log', '[' . date('Y-m-d H:i:s') . '] Error MySQL crear contenido: ' . $error . "\n", FILE_APPEND);
				echo respuestaError('Error al subir contenido: ' . $error);
			}
		break;

		// =====================================
		// ACCIONES CRUD PARA ADMIN
		// =====================================

		case 'obtener_ejecutivos':
			$query = "SELECT id_eje, nom_eje FROM ejecutivo WHERE eli_eje = 1 ORDER BY nom_eje";
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Ejecutivos obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar ejecutivos: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_estructura_completa':
			// Obtener cursos con sus clases y contenidos
			$query = "SELECT 
						c.id_curso, c.nom_curso, c.des_curso, c.fec_creacion_curso,
						cl.id_clase, cl.tit_clase, cl.des_clase, cl.ord_clase, cl.fec_creacion_clase,
						cnt.id_contenido, cnt.tit_contenido, cnt.tip_contenido, cnt.des_contenido, 
						cnt.ord_contenido, cnt.fec_creacion_contenido, cnt.arc_contenido, cnt.url_contenido
					  FROM curso c
					  LEFT JOIN clase cl ON c.id_curso = cl.id_curso AND cl.eli_clase = 1
					  LEFT JOIN contenido cnt ON cl.id_clase = cnt.id_clase AND cnt.eli_contenido = 1
					  WHERE c.eli_curso = 1
					  ORDER BY c.fec_creacion_curso DESC, cl.ord_clase ASC, cnt.ord_contenido ASC";
			
			$resultado = ejecutarConsulta($query, $connection);

			if($resultado !== false) {
				// Organizar datos jerárquicamente
				$estructura = [];
				$cursos_map = [];
				
				foreach($resultado as $fila) {
					$id_curso = $fila['id_curso'];
					
					// Crear curso si no existe
					if(!isset($cursos_map[$id_curso])) {
						$cursos_map[$id_curso] = [
							'id_curso' => $fila['id_curso'],
							'nom_curso' => $fila['nom_curso'],
							'des_curso' => $fila['des_curso'],
							'fec_creacion_curso' => $fila['fec_creacion_curso'],
							'clases' => []
						];
						$estructura[] = &$cursos_map[$id_curso];
					}
					
					// Si hay clase
					if($fila['id_clase']) {
						$id_clase = $fila['id_clase'];
						$clase_existe = false;
						
						// Buscar si la clase ya existe
						foreach($cursos_map[$id_curso]['clases'] as &$clase) {
							if($clase['id_clase'] == $id_clase) {
								$clase_existe = true;
								
								// Agregar contenido a la clase existente
								if($fila['id_contenido']) {
									$clase['contenidos'][] = [
										'id_contenido' => $fila['id_contenido'],
										'tit_contenido' => $fila['tit_contenido'],
										'tip_contenido' => $fila['tip_contenido'],
										'des_contenido' => $fila['des_contenido'],
										'ord_contenido' => $fila['ord_contenido'],
										'fec_creacion_contenido' => $fila['fec_creacion_contenido'],
										'arc_contenido' => $fila['arc_contenido'],
										'url_contenido' => $fila['url_contenido']
									];
								}
								break;
							}
						}
						
						// Si la clase no existe, crearla
						if(!$clase_existe) {
							$nueva_clase = [
								'id_clase' => $fila['id_clase'],
								'tit_clase' => $fila['tit_clase'],
								'des_clase' => $fila['des_clase'],
								'ord_clase' => $fila['ord_clase'],
								'fec_creacion_clase' => $fila['fec_creacion_clase'],
								'contenidos' => []
							];
							
							// Agregar contenido si existe
							if($fila['id_contenido']) {
								$nueva_clase['contenidos'][] = [
									'id_contenido' => $fila['id_contenido'],
									'tit_contenido' => $fila['tit_contenido'],
									'tip_contenido' => $fila['tip_contenido'],
									'des_contenido' => $fila['des_contenido'],
									'ord_contenido' => $fila['ord_contenido'],
									'fec_creacion_contenido' => $fila['fec_creacion_contenido'],
									'arc_contenido' => $fila['arc_contenido'],
									'url_contenido' => $fila['url_contenido']
								];
							}
							
							$cursos_map[$id_curso]['clases'][] = $nueva_clase;
						}
					}
				}
				
				echo respuestaExito($estructura, 'Estructura completa obtenida correctamente');
			} else {
				echo respuestaError('Error al consultar estructura: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// CRUD CURSOS
		// =====================================

		case 'crear_curso':
			$nom_curso = escape($_POST['nom_curso'], $connection);
			$des_curso = escape($_POST['des_curso'], $connection);
			$id_eje_creador = escape($_POST['id_eje_creador'], $connection);
			
			if(empty($nom_curso) || empty($id_eje_creador)) {
				echo respuestaError('Nombre del curso y ejecutivo creador son obligatorios');
				break;
			}
			
			$query = "INSERT INTO curso (nom_curso, des_curso, id_eje_creador, fec_creacion_curso, eli_curso) 
					  VALUES ('$nom_curso', '$des_curso', $id_eje_creador, NOW(), 1)";
			
			if(mysqli_query($connection, $query)) {
				$id_nuevo = mysqli_insert_id($connection);
				echo respuestaExito(['id_curso' => $id_nuevo], 'Curso creado correctamente');
			} else {
				echo respuestaError('Error al crear curso: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_curso':
			$id_curso = escape($_POST['id_curso'], $connection);
			
			$query = "SELECT * FROM curso WHERE id_curso = $id_curso AND eli_curso = 1";
			$datos = ejecutarConsulta($query, $connection);
			
			if($datos !== false && count($datos) > 0) {
				echo respuestaExito($datos[0], 'Curso obtenido correctamente');
			} else {
				echo respuestaError('Curso no encontrado');
			}
		break;

		case 'actualizar_curso':
			$id_curso = escape($_POST['id_curso'], $connection);
			$nom_curso = escape($_POST['nom_curso'], $connection);
			$des_curso = escape($_POST['des_curso'], $connection);
			$id_eje_creador = escape($_POST['id_eje_creador'], $connection);
			
			if(empty($id_curso) || empty($nom_curso) || empty($id_eje_creador)) {
				echo respuestaError('ID, nombre del curso y ejecutivo creador son obligatorios');
				break;
			}
			
			$query = "UPDATE curso SET 
						nom_curso = '$nom_curso',
						des_curso = '$des_curso',
						id_eje_creador = $id_eje_creador,
						fec_actualizacion_curso = NOW()
					  WHERE id_curso = $id_curso AND eli_curso = 1";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito([], 'Curso actualizado correctamente');
			} else {
				echo respuestaError('Error al actualizar curso: ' . mysqli_error($connection));
			}
		break;

		case 'eliminar_curso':
			$id_curso = escape($_POST['id_curso'], $connection);
			
			if(empty($id_curso)) {
				echo respuestaError('ID del curso es obligatorio');
				break;
			}
			
			// Eliminar contenidos primero
			$query1 = "UPDATE contenido SET eli_contenido = 0 
					   WHERE id_clase IN (SELECT id_clase FROM clase WHERE id_curso = $id_curso)";
			mysqli_query($connection, $query1);
			
			// Eliminar clases
			$query2 = "UPDATE clase SET eli_clase = 0 WHERE id_curso = $id_curso";
			mysqli_query($connection, $query2);
			
			// Eliminar curso
			$query3 = "UPDATE curso SET eli_curso = 0 WHERE id_curso = $id_curso";
			
			if(mysqli_query($connection, $query3)) {
				echo respuestaExito([], 'Curso eliminado correctamente');
			} else {
				echo respuestaError('Error al eliminar curso: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// CRUD CLASES
		// =====================================

		case 'obtener_clases':
			$where = "cl.eli_clase = 1";
			if(isset($_POST['id_curso']) && !empty($_POST['id_curso'])) {
				$id_curso = escape($_POST['id_curso'], $connection);
				$where .= " AND cl.id_curso = $id_curso";
			}
			
			$query = "SELECT cl.*, c.nom_curso, e.nom_eje,
						(SELECT COUNT(*) FROM contenido cnt WHERE cnt.id_clase = cl.id_clase AND cnt.eli_contenido = 1) as total_contenidos
					  FROM clase cl
					  JOIN curso c ON cl.id_curso = c.id_curso
					  LEFT JOIN ejecutivo e ON cl.id_eje_creador = e.id_eje
					  WHERE $where
					  ORDER BY c.nom_curso, cl.ord_clase";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Clases obtenidas correctamente');
			} else {
				echo respuestaError('Error al consultar clases: ' . mysqli_error($connection));
			}
		break;

		case 'crear_clase':
			$id_curso = escape($_POST['id_curso'], $connection);
			$tit_clase = escape($_POST['tit_clase'], $connection);
			$des_clase = escape($_POST['des_clase'], $connection);
			$ord_clase = escape($_POST['ord_clase'], $connection);
			$id_eje_creador = escape($_POST['id_eje_creador'], $connection);
			
			if(empty($id_curso) || empty($tit_clase) || empty($ord_clase) || empty($id_eje_creador)) {
				echo respuestaError('Curso, título, orden y ejecutivo creador son obligatorios');
				break;
			}
			
			$query = "INSERT INTO clase (id_curso, tit_clase, des_clase, ord_clase, id_eje_creador, fec_creacion_clase, eli_clase) 
					  VALUES ($id_curso, '$tit_clase', '$des_clase', $ord_clase, $id_eje_creador, NOW(), 1)";
			
			if(mysqli_query($connection, $query)) {
				$id_nuevo = mysqli_insert_id($connection);
				echo respuestaExito(['id_clase' => $id_nuevo], 'Clase creada correctamente');
			} else {
				echo respuestaError('Error al crear clase: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_clase':
			$id_clase = escape($_POST['id_clase'], $connection);
			
			$query = "SELECT * FROM clase WHERE id_clase = $id_clase AND eli_clase = 1";
			$datos = ejecutarConsulta($query, $connection);
			
			if($datos !== false && count($datos) > 0) {
				echo respuestaExito($datos[0], 'Clase obtenida correctamente');
			} else {
				echo respuestaError('Clase no encontrada');
			}
		break;

		case 'actualizar_clase':
			$id_clase = escape($_POST['id_clase'], $connection);
			$id_curso = escape($_POST['id_curso'], $connection);
			$tit_clase = escape($_POST['tit_clase'], $connection);
			$des_clase = escape($_POST['des_clase'], $connection);
			$ord_clase = escape($_POST['ord_clase'], $connection);
			$id_eje_creador = escape($_POST['id_eje_creador'], $connection);
			
			if(empty($id_clase) || empty($id_curso) || empty($tit_clase) || empty($ord_clase) || empty($id_eje_creador)) {
				echo respuestaError('Todos los campos obligatorios deben estar completos');
				break;
			}
			
			$query = "UPDATE clase SET 
						id_curso = $id_curso,
						tit_clase = '$tit_clase',
						des_clase = '$des_clase',
						ord_clase = $ord_clase,
						id_eje_creador = $id_eje_creador
					  WHERE id_clase = $id_clase AND eli_clase = 1";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito([], 'Clase actualizada correctamente');
			} else {
				echo respuestaError('Error al actualizar clase: ' . mysqli_error($connection));
			}
		break;

		case 'eliminar_clase':
			$id_clase = escape($_POST['id_clase'], $connection);
			
			if(empty($id_clase)) {
				echo respuestaError('ID de la clase es obligatorio');
				break;
			}
			
			// Eliminar contenidos primero
			$query1 = "UPDATE contenido SET eli_contenido = 0 WHERE id_clase = $id_clase";
			mysqli_query($connection, $query1);
			
			// Eliminar clase
			$query2 = "UPDATE clase SET eli_clase = 0 WHERE id_clase = $id_clase";
			
			if(mysqli_query($connection, $query2)) {
				echo respuestaExito([], 'Clase eliminada correctamente');
			} else {
				echo respuestaError('Error al eliminar clase: ' . mysqli_error($connection));
			}
		break;

		// =====================================
		// CRUD CONTENIDOS
		// =====================================

		case 'obtener_contenidos':
			$where = "cnt.eli_contenido = 1";
			if(isset($_POST['id_clase']) && !empty($_POST['id_clase'])) {
				$id_clase = escape($_POST['id_clase'], $connection);
				$where .= " AND cnt.id_clase = $id_clase";
			}
			
			$query = "SELECT cnt.*, cl.tit_clase, c.nom_curso, e.nom_eje
					  FROM contenido cnt
					  JOIN clase cl ON cnt.id_clase = cl.id_clase
					  JOIN curso c ON cl.id_curso = c.id_curso
					  LEFT JOIN ejecutivo e ON cnt.id_eje_creador = e.id_eje
					  WHERE $where
					  ORDER BY c.nom_curso, cl.ord_clase, cnt.ord_contenido";
			
			$datos = ejecutarConsulta($query, $connection);

			if($datos !== false) {
				echo respuestaExito($datos, 'Contenidos obtenidos correctamente');
			} else {
				echo respuestaError('Error al consultar contenidos: ' . mysqli_error($connection));
			}
		break;

		case 'crear_contenido':
			$id_clase = escape($_POST['id_clase'], $connection);
			$tit_contenido = escape($_POST['tit_contenido'], $connection);
			$tip_contenido = escape($_POST['tip_contenido'], $connection);
			$des_contenido = escape($_POST['des_contenido'], $connection);
			$ord_contenido = escape($_POST['ord_contenido'], $connection);
			$id_eje_creador = escape($_POST['id_eje_creador'], $connection);
			$url_contenido = escape($_POST['url_contenido'], $connection);
			
			if(empty($id_clase) || empty($tit_contenido) || empty($tip_contenido) || empty($ord_contenido) || empty($id_eje_creador)) {
				echo respuestaError('Clase, título, tipo, orden y ejecutivo creador son obligatorios');
				break;
			}
			
			$arc_contenido = '';
			
			// Manejar archivo subido
			if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
				$archivo = $_FILES['archivo'];
				$extensiones_permitidas = ['pdf', 'mp4', 'mp3', 'jpg', 'jpeg', 'png', 'gif'];
				$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
				
				if(!in_array($extension, $extensiones_permitidas)) {
					echo respuestaError('Tipo de archivo no permitido');
					break;
				}
				
				if($archivo['size'] > 50 * 1024 * 1024) { // 50MB
					echo respuestaError('El archivo es demasiado grande (máximo 50MB)');
					break;
				}
				
				$nombre_archivo = 'contenido_' . time() . '_' . uniqid() . '.' . $extension;
				$ruta_destino = '../uploads/' . $nombre_archivo;
				
				if(move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
					$arc_contenido = $nombre_archivo;
				} else {
					echo respuestaError('Error al subir archivo');
					break;
				}
			}
			
			$query = "INSERT INTO contenido (id_clase, tit_contenido, tip_contenido, arc_contenido, url_contenido, des_contenido, ord_contenido, id_eje_creador, fec_creacion_contenido, eli_contenido) 
					  VALUES ($id_clase, '$tit_contenido', '$tip_contenido', '$arc_contenido', '$url_contenido', '$des_contenido', $ord_contenido, $id_eje_creador, NOW(), 1)";
			
			if(mysqli_query($connection, $query)) {
				$id_nuevo = mysqli_insert_id($connection);
				echo respuestaExito(['id_contenido' => $id_nuevo], 'Contenido creado correctamente');
			} else {
				echo respuestaError('Error al crear contenido: ' . mysqli_error($connection));
			}
		break;

		case 'obtener_contenido':
			$id_contenido = escape($_POST['id_contenido'], $connection);
			
			$query = "SELECT * FROM contenido WHERE id_contenido = $id_contenido AND eli_contenido = 1";
			$datos = ejecutarConsulta($query, $connection);
			
			if($datos !== false && count($datos) > 0) {
				echo respuestaExito($datos[0], 'Contenido obtenido correctamente');
			} else {
				echo respuestaError('Contenido no encontrado');
			}
		break;

		case 'actualizar_contenido':
			$id_contenido = escape($_POST['id_contenido'], $connection);
			$id_clase = escape($_POST['id_clase'], $connection);
			$tit_contenido = escape($_POST['tit_contenido'], $connection);
			$tip_contenido = escape($_POST['tip_contenido'], $connection);
			$des_contenido = escape($_POST['des_contenido'], $connection);
			$ord_contenido = escape($_POST['ord_contenido'], $connection);
			$id_eje_creador = escape($_POST['id_eje_creador'], $connection);
			$url_contenido = escape($_POST['url_contenido'], $connection);
			
			if(empty($id_contenido) || empty($id_clase) || empty($tit_contenido) || empty($tip_contenido) || empty($ord_contenido) || empty($id_eje_creador)) {
				echo respuestaError('Todos los campos obligatorios deben estar completos');
				break;
			}
			
			// Obtener archivo actual
			$query_actual = "SELECT arc_contenido FROM contenido WHERE id_contenido = $id_contenido";
			$resultado_actual = ejecutarConsulta($query_actual, $connection);
			$arc_contenido_actual = ($resultado_actual && count($resultado_actual) > 0) ? $resultado_actual[0]['arc_contenido'] : '';
			
			$arc_contenido = $arc_contenido_actual; // Mantener archivo actual por defecto
			
			// Manejar nuevo archivo subido
			if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
				$archivo = $_FILES['archivo'];
				$extensiones_permitidas = ['pdf', 'mp4', 'mp3', 'jpg', 'jpeg', 'png', 'gif'];
				$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
				
				if(!in_array($extension, $extensiones_permitidas)) {
					echo respuestaError('Tipo de archivo no permitido');
					break;
				}
				
				if($archivo['size'] > 50 * 1024 * 1024) { // 50MB
					echo respuestaError('El archivo es demasiado grande (máximo 50MB)');
					break;
				}
				
				$nombre_archivo = 'contenido_' . time() . '_' . uniqid() . '.' . $extension;
				$ruta_destino = '../uploads/' . $nombre_archivo;
				
				if(move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
					// Eliminar archivo anterior si existe
					if($arc_contenido_actual && file_exists('../uploads/' . $arc_contenido_actual)) {
						unlink('../uploads/' . $arc_contenido_actual);
					}
					$arc_contenido = $nombre_archivo;
				} else {
					echo respuestaError('Error al subir nuevo archivo');
					break;
				}
			}
			
			$query = "UPDATE contenido SET 
						id_clase = $id_clase,
						tit_contenido = '$tit_contenido',
						tip_contenido = '$tip_contenido',
						arc_contenido = '$arc_contenido',
						url_contenido = '$url_contenido',
						des_contenido = '$des_contenido',
						ord_contenido = $ord_contenido,
						id_eje_creador = $id_eje_creador,
						fec_actualizacion_contenido = NOW()
					  WHERE id_contenido = $id_contenido AND eli_contenido = 1";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito([], 'Contenido actualizado correctamente');
			} else {
				echo respuestaError('Error al actualizar contenido: ' . mysqli_error($connection));
			}
		break;

		case 'eliminar_contenido':
			$id_contenido = escape($_POST['id_contenido'], $connection);
			
			if(empty($id_contenido)) {
				echo respuestaError('ID del contenido es obligatorio');
				break;
			}
			
			// Obtener archivo para eliminarlo del servidor
			$query_archivo = "SELECT arc_contenido FROM contenido WHERE id_contenido = $id_contenido";
			$resultado_archivo = ejecutarConsulta($query_archivo, $connection);
			
			if($resultado_archivo && count($resultado_archivo) > 0) {
				$archivo = $resultado_archivo[0]['arc_contenido'];
				if($archivo && file_exists('../uploads/' . $archivo)) {
					unlink('../uploads/' . $archivo);
				}
			}
			
			// Eliminar contenido
			$query = "UPDATE contenido SET eli_contenido = 0 WHERE id_contenido = $id_contenido";
			
			if(mysqli_query($connection, $query)) {
				echo respuestaExito([], 'Contenido eliminado correctamente');
			} else {
				echo respuestaError('Error al eliminar contenido: ' . mysqli_error($connection));
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

function organizarComentariosJerarquicos($comentarios) {
	// Crear un array indexado por id para acceso rápido
	$comentarios_indexados = [];
	foreach ($comentarios as $comentario) {
		$comentario['respuestas'] = [];
		$comentarios_indexados[$comentario['id_comentario']] = $comentario;
	}
	
	// Organizar jerárquicamente
	$comentarios_raiz = [];
	
	foreach ($comentarios_indexados as $id => $comentario) {
		if ($comentario['id_comentario_padre'] === null) {
			// Es un comentario raíz
			$comentarios_raiz[] = &$comentarios_indexados[$id];
		} else {
			// Es una respuesta, agregarla al comentario padre
			$id_padre = $comentario['id_comentario_padre'];
			if (isset($comentarios_indexados[$id_padre])) {
				$comentarios_indexados[$id_padre]['respuestas'][] = &$comentarios_indexados[$id];
			}
		}
	}
	
	return $comentarios_raiz;
}
?>
