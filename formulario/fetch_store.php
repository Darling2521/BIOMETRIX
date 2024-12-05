<?php
require_once 'conexion.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $query = "SELECT u.rol, t.nombre as store_name FROM usuarios u LEFT JOIN tiendas t ON u.tienda_id = t.id WHERE u.email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['rol'] == 'administrador' && empty($user['store_name'])) {
            echo json_encode(['success' => true, 'store_name' => null]);
        } else {
            echo json_encode(['success' => true, 'store_name' => $user['store_name']]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
