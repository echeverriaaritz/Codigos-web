<?php
// ----- DATOS DE CONEXIÓN A LA BD -----
$host = 'localhost';        // Servidor de la base de datos (en este caso la propia máquina)
$db   = 'intranet';       // Nombre de la base de datos
$user = 'asier';             // Usuario de MySQL que creamos antes
$pass = 'Admin123';          // Contraseña de ese usuario
$charset = 'utf8mb4';       // Codificación de caracteres recomendada

// Montamos el DSN (cadena de conexión de PDO)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones para PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Si hay error, lanza excepción (más fácil de depurar)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetchAll() devolverá arrays asociativos (['columna' => valor])
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa sentencias preparadas nativas de MySQL (más seguro)
];

try {
    // Crear el objeto PDO (conexión a la BD)
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Preparamos la consulta SQL
    $sql = "SELECT * FROM users";   // Selecciona todas las columnas de todos los usuarios
    $stmt = $pdo->prepare($sql);    // Prepara la sentencia para ejecutarla
    $stmt->execute();               // Ejecuta la consulta en MySQL

    // Obtenemos todos los resultados en un array
    $users = $stmt->fetchAll();     // $users será un array de arrays asociativos

    // Indicamos que la respuesta será JSON
    header('Content-Type: application/json; charset=utf-8');

    // Enviamos un JSON al navegador con los datos
    echo json_encode(
        [
            'success' => true,  // Indica que todo ha ido bien
            'data'    => $users // Aquí va el array de usuarios
        ],
        JSON_PRETTY_PRINT      // Para que el JSON quede “bonito” (formateado)
    );

} catch (PDOException $e) {
    // Si hay cualquier error en la BD, entramos aquí
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,          // marcamos que ha habido fallo
        'message' => 'Database Error'// mensaje genérico (no exponemos detalles)
    ]);
}