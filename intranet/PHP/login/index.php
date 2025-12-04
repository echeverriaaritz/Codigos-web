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
        echo json_encode(['msg' => 'fail']); // Si no hay datos, devolver fallo
        exit; // Terminar ejecución
    }

    // Limpiar y decodificar los datos JSON recibidos
    $raw = stripslashes($_POST['param']); // Quita barras invertidas
    $param = json_decode($raw); // Convierte JSON a objeto PHP

    // Validar que el usuario y contraseña no estén vacíos
    if (empty($param->username) || empty($param->password)) {
        echo json_encode(['msg' => 'fail']); // Si faltan datos, devolver fallo
        exit;
    }

    // Preparar la consulta para buscar el usuario en la tabla auth_users
    $sql = "SELECT * FROM auth_users WHERE username = :username";
    $stmt = $pdo->prepare($sql); // Preparar la consulta
    $stmt->bindParam(':username', $param->username); // Vincular el parámetro username
    $stmt->execute(); // Ejecutar la consulta
    $userData = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener los datos del usuario como array asociativo

    // Verificar si el usuario existe y la contraseña es correcta
    if ($userData && password_verify($param->password, $userData['password'])) {
        echo json_encode(['msg' => 'success']); // Login correcto
    } else {
        echo json_encode(['msg' => 'fail']); // Login incorrecto
    }

} catch (PDOException $e) {
    // Captura errores de conexión o de base de datos
    echo json_encode(['msg' => 'fail']); // Devolver fallo
}
?>


