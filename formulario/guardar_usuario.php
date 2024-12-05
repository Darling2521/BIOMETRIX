<?php
require_once 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $tienda_id = isset($_POST['tienda']) ? $_POST['tienda'] : null;
    $rol = $_POST['rol'];

    try {
        $query = "INSERT INTO usuarios (nombre, email, password, tienda_id, rol) VALUES (:nombre, :email, :password, :tienda_id, :rol)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':tienda_id', $tienda_id);
        $stmt->bindValue(':rol', $rol);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Usuario registrado con éxito.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al registrar el usuario.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Solicitud no válida.']);
}
?>
