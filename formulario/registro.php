<?php
require_once 'conexion.php';

$query = "SELECT id, nombre FROM tiendas";
$stmt = $conn->prepare($query);
$stmt->execute();
$tiendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(to right, #AEE96C, #75CCED);
    }

    .container {
        max-width: 500px;
        width: 100%;
    }

    .max-w-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 0.75rem;
    }

    label {
        color: #4a5568;
        font-weight: 600;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus {
        border-color: #a0aec0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(164, 202, 254, 0.45);
    }

    button[type="submit"] {
        background-color: #4299e1;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease-in-out;
    }

    button[type="submit"]:hover {
        background-color: #2c7ad6;
    }

    .chosen-container {
        width: 100% !important;
    }

    .chosen-container-single .chosen-single {
        height: auto;
        padding: 0.5rem 0.75rem;
        border-color: #e2e8f0;
        border-radius: 0.375rem;
        background: #fff;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: border-color 0.2s ease-in-out;
    }

    .chosen-container-single .chosen-single:focus {
        border-color: #a0aec0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(164, 202, 254, 0.45);
    }

    .chosen-container-active.chosen-with-drop .chosen-single {
        border-color: #a0aec0;
        box-shadow: 0 0 0 3px rgba(164, 202, 254, 0.45);
    }

    .chosen-container .chosen-drop {
        border-color: #e2e8f0;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    button[type="submit"] {
        background-color: #4299e1;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease-in-out;
        width: 100%;
        margin-top: 10px;
    }

    button[type="submit"]:hover {
        background-color: #2c7ad6;
    }
</style>

<body class="bg-gray-100">
    <div class="container mx-auto mt-4">
        <div class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-semibold mb-4">Registro de Usuario</h2>
            <form id="registro-form">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">Nombre</label>
                    <input name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                    <input name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Contraseña</label>
                    <input name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tienda">Tienda</label>
                    <select name="tienda" class="chosen-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tienda" required>
                        <option value="" disabled selected>Seleccione una tienda</option>
                        <?php foreach ($tiendas as $tienda): ?>
                            <option value="<?= $tienda['id'] ?>"><?= $tienda['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="rol">Rol</label>
                    <select name="rol" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rol" required>
                        <option value="gestor">Gestor</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Registrar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.chosen-select').chosen({width: "100%"});
        });

        document.getElementById('rol').addEventListener('change', function (event) {
            const tiendaInput = document.getElementById('tienda');

            if (event.target.value === 'administrador') {
                tiendaInput.value = ''; 
                tiendaInput.disabled = true; 
                tiendaInput.removeAttribute('required'); 
            } else {
                tiendaInput.disabled = false; 
                tiendaInput.setAttribute('required', true); 
            }
        });

     
        $('#registro-form').on('submit', function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: 'guardar_usuario.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                    // Limpiar el formulario
                    $('#registro-form')[0].reset();
                    $('.chosen-select').val('').trigger('chosen:updated');
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    </script>
</body>
</html>
