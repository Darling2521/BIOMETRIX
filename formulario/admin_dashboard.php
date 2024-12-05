<?php
require_once 'conexion.php';
session_start();

if ($_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Filtro por tienda
$tiendaFiltro = isset($_GET['tienda']) ? $_GET['tienda'] : '';

// Paginación
$registrosPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

$consultaTienda = $tiendaFiltro ? "AND t.nombre = :tienda" : "";

$consulta = "
SELECT 
    r.id, 
    r.fec_registro, 
    r.nro_doc, 
    r.nombres, 
    r.apellidos, 
    r.genero, 
    r.nacionalidad, 
    r.est_civil, 
    r.cod_dactilar, 
    r.fec_nacimiento, 
    t.nombre AS tienda, 
    u.nombre AS gestor
FROM 
    registro r
INNER JOIN 
    tiendas t ON r.tienda_id = t.id
INNER JOIN 
    usuarios u ON r.user_registro = u.nombre
WHERE 
    r.estado = 'pendiente'
    $consultaTienda
LIMIT :registrosPorPagina OFFSET :offset
";

$stmt = $conn->prepare($consulta);
if ($tiendaFiltro) {
    $stmt->bindParam(':tienda', $tiendaFiltro);
}
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$registros_pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$consultaTotal = "
SELECT COUNT(*) 
FROM 
    registro r
INNER JOIN 
    tiendas t ON r.tienda_id = t.id
WHERE 
    r.estado = 'pendiente'
    $consultaTienda
";
$stmtTotal = $conn->prepare($consultaTotal);
if ($tiendaFiltro) {
    $stmtTotal->bindParam(':tienda', $tiendaFiltro);
}
$stmtTotal->execute();
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$usuario_logueado = $_SESSION['nombre_gestor'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<nav class="bg-black bg-opacity-50 p-4 flex justify-between items-center text-white fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center space-x-4">
        <img src="image.png" alt="Logo" class="h-8 w-8 object-contain">
        <div class="text-lg">Bienvenido, <?php echo $usuario_logueado; ?></div>
    </div>
    <div>
        <a href="logout.php" class="border border-white text-white font-semibold py-2 px-4 rounded hover:bg-white hover:bg-opacity-10">Cerrar sesión</a>
    </div>
</nav>
<div class="container mx-auto mt-20 pt-4">
    <!-- Filtro por tienda -->
    <div class="flex justify-between items-center mb-4">
        <form method="GET" class="flex items-center">
            <label for="tienda" class="mr-2">Filtrar por tienda:</label>
            <select name="tienda" id="tienda" class="border rounded py-2 px-4">
                <option value="">Todas</option>
                <?php
                $consultaTiendas = $conn->query("SELECT nombre FROM tiendas");
                $tiendas = $consultaTiendas->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tiendas as $tienda) {
                    $selected = $tienda['nombre'] === $tiendaFiltro ? 'selected' : '';
                    echo "<option value=\"{$tienda['nombre']}\" $selected>{$tienda['nombre']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded ml-2">Filtrar</button>
        </form>

        <div class="text-lg">
    <span class="font-semibold text-2xl text-red-500 underline" id="pendientesText">Pendientes: <?php echo $totalRegistros; ?></span>
</div>
    </div>

    <!-- Tabla de registros -->
    <table class="min-w-full bg-white shadow-md rounded my-6">
        <thead class="bg-gradient-to-r from-green-500 to-blue-500 text-white">
            <tr>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Fecha de Registro</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Tienda</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Gestor</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros_pendientes as $registro): ?>
            <tr class="hover:bg-green-100" data-id="<?php echo $registro['id']; ?>">
                <td class="py-3 px-4 text-center"><?php echo $registro['fec_registro']; ?></td>
                <td class="py-3 px-4 text-center"><?php echo $registro['tienda']; ?></td>
                <td class="py-3 px-4 text-center"><?php echo $registro['gestor']; ?></td>
                <td class="py-3 px-4 text-center">
                    <button class="ver-detalles bg-gradient-to-r from-green-500 to-blue-500 text-white py-1 px-3 rounded hover:from-blue-500 hover:to-green-500" data-id="<?php echo $registro['id']; ?>">Ver detalles</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="flex justify-center mt-4">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>&tienda=<?php echo $tiendaFiltro; ?>" class="py-2 px-4 mx-1 <?php echo $i == $paginaActual ? 'bg-blue-500 text-white' : 'bg-white text-blue-500 border border-blue-500'; ?> rounded"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<!-- Modal para mostrar los detalles del registro -->
<div id="modal-registro" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md mx-auto">
        <div class="flex justify-between items-center bg-green-600 text-white px-6 py-4 rounded-t-lg">
            <h2 class="text-xl font-bold">Detalles</h2>
            <span class="cerrar-modal cursor-pointer hover:text-gray-300">&times;</span>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <p class="text-lg"><span class="font-semibold">Tienda:</span> <span id="modal-tienda"></span></p>
                <p class="text-lg"><span class="font-semibold">Número de Documento:</span> <span id="modal-nro-doc"></span></p>
                <p class="text-lg"><span class="font-semibold">Nombres:</span> <span id="modal-nombres"></span></p>
                <p class="text-lg"><span class="font-semibold">Apellidos:</span> <span id="modal-apellidos"></span></p>
                <p class="text-lg"><span class="font-semibold">Estado Civil:</span> <span id="modal-est-civil"></span></p>
                <p class="text-lg"><span class="font-semibold">Género:</span> <span id="modal-genero"></span></p>
                <p class="text-lg"><span class="font-semibold">Nacionalidad:</span> <span id="modal-nacionalidad"></span></p>
                <p class="text-lg"><span class="font-semibold">Código Dactilar:</span> <span id="modal-cod-dactilar"></span></p>
                <p class="text-lg"><span class="font-semibold">Fecha de Nacimiento:</span> <span id="modal-fec-nacimiento"></span></p>
            </div>
            <div class="flex justify-center mt-8">
                <button id="modal-aprobar-registro" class="bg-green-600 text-white py-2 px-6 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-opacity-50 transition-colors duration-300">Aprobar Registro</button>
            </div>
        </div>
    </div>
</div>

<script>
    var modal = document.getElementById("modal-registro");
    var span = document.getElementsByClassName("cerrar-modal")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    var botonesVerDetalles = document.getElementsByClassName("ver-detalles");
    for (var i = 0; i < botonesVerDetalles.length; i++) {
        botonesVerDetalles[i].onclick = function() {
            var idRegistro = this.getAttribute("data-id");
            fetch('get_registro.php?id=' + idRegistro)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("modal-tienda").innerText = data.tienda;
                    document.getElementById("modal-nro-doc").innerText = data.nro_doc;
                    document.getElementById("modal-nombres").innerText = data.nombres;
                    document.getElementById("modal-apellidos").innerText = data.apellidos;
                    document.getElementById("modal-est-civil").innerText = data.est_civil;
                    document.getElementById("modal-genero").innerText = data.genero;
                    document.getElementById("modal-nacionalidad").innerText = data.nacionalidad;
                    document.getElementById("modal-cod-dactilar").innerText = data.cod_dactilar;
                    document.getElementById("modal-fec-nacimiento").innerText = data.fec_nacimiento;

                    document.getElementById("modal-aprobar-registro").setAttribute("data-id", idRegistro);
                    modal.style.display = "flex";
                });
        }
    }

    document.getElementById("modal-aprobar-registro").onclick = function() {
        var idRegistro = this.getAttribute("data-id");
        aprobarRegistro(idRegistro);
    }

    function aprobarRegistro(idRegistro) {
        fetch('aprobar_registro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: idRegistro, user: '<?php echo $usuario_logueado; ?>' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registro aprobado con éxito');
                var row = document.querySelector(`tr[data-id='${idRegistro}']`);
                row.parentNode.removeChild(row);
                modal.style.display = "none";
            } else {
                alert('Error al aprobar el registro');
            }
        });
    }
</script>

</body>
</html>
