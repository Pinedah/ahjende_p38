<?php
	// Función para cargar variables de entorno desde .env
	function loadEnv($path) {
		if (!file_exists($path)) {
			die('Error: Archivo .env no encontrado en ' . $path);
		}
		
		$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) {
				continue; // Ignorar comentarios
			}
			
			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = trim($value);
			
			if (!array_key_exists($name, $_ENV)) {
				putenv(sprintf('%s=%s', $name, $value));
				$_ENV[$name] = $value;
				$_SERVER[$name] = $value;
			}
		}
	}

	// Cargar variables de entorno
	$envPath = __DIR__ . '/../.env';
	if (file_exists($envPath)) {
		loadEnv($envPath);
		$host = getenv('DB_HOST');
		$user = getenv('DB_USER');
		$pass = getenv('DB_PASS');
		$database = getenv('DB_NAME');
		$charset = getenv('DB_CHARSET');
		$port = getenv('DB_PORT') ? intval(getenv('DB_PORT')) : 3307; // Puerto por defecto 3307
	} else {
		// Configuración por defecto si no existe .env
		$host = 'localhost';
		$user = 'root';
		$pass = '';
		$database = 'ahj_ende_pinedah';
		$charset = 'utf8mb4';
		$port = 3307; // Puerto por defecto 3307
		
		// Crear archivo .env con configuración por defecto
		$envContent = "DB_HOST=localhost\nDB_USER=root\nDB_PASS=\nDB_NAME=ahj_ende_pinedah\nDB_CHARSET=utf8mb4\nDB_PORT=3307";
		file_put_contents($envPath, $envContent);
		echo "<div class='alert alert-warning'>Archivo .env creado automáticamente con configuración por defecto</div>";
	}
	
	$connection = mysqli_connect($host, $user, $pass, $database, $port);
	if (!$connection) {
		die('Error de conexión: ' . mysqli_connect_error());
	}
	mysqli_set_charset($connection, $charset);

	// Función para ejecutar consultas y obtener datos
	function ejecutarConsulta($query, $connection) {
		$result = mysqli_query($connection, $query);
		if (!$result) return false;
		$datos = [];
		while($row = mysqli_fetch_assoc($result)) {
			$datos[] = $row;
		}
		return $datos;
	}

	// Función para escape de datos (prevención SQL Injection)
	function escape($valor, $connection) {
		return mysqli_real_escape_string($connection, $valor);
	}

	// Respuesta exitosa estándar
	function respuestaExito($data = null, $message = 'OK') {
		return json_encode([
			'success' => true,
			'data' => $data,
			'message' => $message
		], JSON_UNESCAPED_UNICODE);
	}

	// Respuesta de error estándar
	function respuestaError($message = 'Error', $code = 400) {
		return json_encode([
			'success' => false,
			'message' => $message,
			'code' => $code
		], JSON_UNESCAPED_UNICODE);
	}
?>
