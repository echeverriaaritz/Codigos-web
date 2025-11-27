<?php
// Datos de conexión (igual que antes)
$host = 'localhost';
$db   = 'intranet';
$user = 'asier';
$pass = 'Admin123';

// Cadena de conexión
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    // Crear conexión PDO
    $pdo = new PDO($dsn, $user, $pass);

    // ----- LEER DATOS QUE VIENEN DESDE JAVASCRIPT -----
    // JS envía un POST con un parámetro llamado 'param'
    // que contiene un JSON en forma de cadena

    $raw = stripslashes($_POST['param']);
    // $_POST['param']    → lee el valor del campo 'param' enviado por AJAX
    // stripslashes(...)  → elimina caracteres de escape extra (por seguridad/compatibilidad)
    // $raw               → cadena JSON tal cual, por ejemplo: {"username":"pepe","password":"1234","company":"X"}

    $param = json_decode($raw);
    // json_decode(...)   → convierte la cadena JSON en un objeto PHP
    // $param->username   → valor de la propiedad username
    // $param->password   → valor de la propiedad password
    // $param->company    → valor de la propiedad company

    // ----- PREPARAR SENTENCIA SQL DE INSERT -----
    $sql = "INSERT INTO users (username, password, company)
            VALUES (:username, :password, :company)";
    // :username, :password, :company son “marcadores” (placeholders)
    // para usar sentencias preparadas y evitar SQL injection

    $stmt = $pdo->prepare($sql);
    // Prepara la sentencia para que PDO la pueda ejecutar luego

    // Asignamos (bindeamos) los valores que vienen del JSON a los marcadores
    $stmt->bindParam(':username', $param->username);  // :username ← $param->username
    $stmt->bindParam(':password', $param->password);  // :password ← $param->password
    $stmt->bindParam(':company',  $param->company);   // :company  ← $param->company

    // Ejecutamos el INSERT en la base de datos
    $stmt->execute();

    // rowCount devuelve cuántas filas se han afectado (insertadas en este caso)
    $rows = $stmt->rowCount();

    // Indicamos que la respuesta será JSON
    header('Content-Type: application/json; charset=utf-8');

    // Enviamos mensaje de éxito en formato JSON
    echo json_encode([
        'msg' => "Success: inserted $rows user(s)." // Ej: “Success: inserted 1 user(s).”
    ]);

} catch (PDOException $e) {
    // Si ocurre un error en cualquier punto del try, se captura aquí
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'msg' => "fail" // Mensaje genérico de fallo
    ]);
}