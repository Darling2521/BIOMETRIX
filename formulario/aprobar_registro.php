<?php
require_once 'conexion.php';
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$user = $data['user'];
$consulta = $conn->prepare("UPDATE registro SET estado = 'aprobado', fec_aprob = NOW(), user_aprob = :user WHERE id = :id");
$consulta->bindParam(':id', $id);
$consulta->bindParam(':user', $user);
if ($consulta->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
