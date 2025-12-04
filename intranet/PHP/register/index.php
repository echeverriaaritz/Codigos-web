<?php
// Datos de conexión a la base de datos
$host = 'localhost';      // Servidor de base de datos
$db   = 'intranet';       // Nombre de la base de datos
$user = 'asier';           // Usuario de la base de datos
$pass = 'Admin123';        // Contraseña del usuario
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4"; // DSN para PDO con UTF-8

try {
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $user, $pass);

    // Comprobar si se recibió el parámetro 'param' por POST
    if (!isset($_POST['param'])) {
        echo json_encode(['msg' => 'No se recibió ningún parámetro']); // Mensaje de error si no llega dato
        exit; // Terminar ejecución
    }

    // Limpiar y decodificar los datos JSON recibidos
    $raw = stripslashes($_POST['param']); // Elimina posibles barras invertidas
    $param = json_decode($raw); // Convierte JSON a objeto PHP

    // Validar que los campos username y password no estén vacíos
    if (empty($param->username) || empty($param->password)) {
        echo json_encode(['msg' => 'Faltan campos: username o password']); // Mensaje de error si falta info
        exit;
    }

    // Hashear (encriptar) la contraseña antes de guardarla
    $hashedPassword = password_hash($param->password, PASSWORD_DEFAULT); // Genera hash seguro

    // Preparar la consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO auth_users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql); // Preparar la sentencia
    $stmt->bindParam(':username', $param->username); // Vincular el parámetro username
    $stmt->bindParam(':password', $hashedPassword); // Vincular el parámetro password hasheado
    $stmt->execute(); // Ejecutar la consulta

    // Mensaje de éxito
    echo json_encode(['msg' => 'Usuario registrado correctamente']);
} catch (PDOException $e) {
    // Captura errores de conexión o de base de datos y los devuelve
    echo json_encode(['msg' => 'Error: ' . $e->getMessage()]);
}
?>




