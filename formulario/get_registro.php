<?php
require_once 'conexion.php';

$id = $_GET['id'];

$consulta = "
SELECT r.nro_doc, r.nombres, r.apellidos, r.genero, r.nacionalidad, r.cod_dactilar, r.est_civil, r.fec_nacimiento, t.nombre as tienda
FROM registro r
INNER JOIN tiendas t ON r.tienda_id = t.id
WHERE r.id = :id";

$stmt = $conn->prepare($consulta);
$stmt->bindParam(':id', $id);
$stmt->execute();
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($registro);
?>
