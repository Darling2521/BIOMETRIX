<?php
require_once 'conexion.php';
session_start();

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['nombre_gestor'] = $user['nombre'];
                $_SESSION['tienda_id'] = $user['tienda_id'];

                $response['success'] = true;
                $response['redirect'] = ($user['rol'] == 'administrador') ? 'admin_dashboard.php' : 'formulario_registro.php';
            } else {
                $response['message'] = 'Contraseña incorrecta.';
            }
        } else {
            $response['message'] = 'No se encontró el usuario.';
        }
    } else {
        $response['message'] = 'Por favor, complete todos los campos.';
    }
}

echo json_encode($response);
?>
