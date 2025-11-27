<?php
// Datos de conexión
$host = 'localhost';
$db   = 'intranet';
$user = 'asier';
$pass = 'Admin123';

// Cadena de conexión
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    // Crear conexión PDO
    $pdo = new PDO($dsn, $user, $pass);

    // ----- LEER JSON QUE VIENE DE JAVASCRIPT -----
    $raw = stripslashes($_POST['param']);
    // Igual que antes, leemos el parámetro 'param' enviado por JS
    // y limpiamos posibles caracteres de escape

    $param = json_decode($raw);
    // Decodificamos el JSON a objeto PHP
    // $param->id tendrá el id que queremos borrar

    // ----- PREPARAR SENTENCIA DELETE -----
    $sql = "DELETE FROM users WHERE id = :id";
    // Borrará la fila cuyo id coincida con el proporcionado

    $stmt = $pdo->prepare($sql);
    // Preparamos la sentencia

    // Vinculamos el valor del id del JSON al marcador :id
    $stmt->bindParam(':id', $param->id);
    // Aquí podríamos especificar PDO::PARAM_INT, pero no es obligatorio

    // Ejecutamos el DELETE
    $stmt->execute();

    // Contamos cuántas filas se han borrado
    $rows = $stmt->rowCount();

    // Indicamos que vamos a devolver JSON
    header('Content-Type: application/json; charset=utf-8');

    // Devolvemos un mensaje con cuántas filas se han borrado
    echo json_encode([
        'msg' => "Success: Deleted $rows user(s)." // “Success: Deleted 1 user(s).”
    ]);

} catch (PDOException $e) {
    // Si hay algún error de BD, devolvemos un JSON con fail
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'msg' => "fail"
    ]);
}