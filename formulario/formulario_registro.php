<?php
 session_start();
 if ($_SESSION['rol'] != 'gestor') {
     header("Location: inicio_sesion.php");
     exit();
 }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .bg-soft-blue {
            background-color: #4a90e2; 
        }
        .bg-semi-transparent {
            background-color: rgba(255, 255, 255, 0.8); 
        }
        .bg-gradient {
            background: linear-gradient(to right, #AEE96C, #75CCED);       
        }
        .form-bg {
            background-color: rgba(255, 255, 255, 0.8); 
        }
        .hidden {
            display: none;
        }
        .navbar-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }
    </style>
</head>
<body class="bg-gradient">
    <!-- Navbar -->
    <nav class="bg-white navbar-shadow p-4">
        <div class="container mx-auto flex justify-between items-center text-gray-800">
            <div class="flex items-center">
                <i class="fas fa-user-circle text-2xl mr-2"></i>
                <span class="font-light text-xl"><?php echo $_SESSION['nombre_gestor']; ?></span>
            </div>
            <a href="logout.php" class="text-gray-800 hover:underline">Cerrar sesión</a>
        </div>
    </nav>

    <div class="container mx-auto mt-4">
        <div class="max-w-4xl mx-auto form-bg shadow-md rounded px-8 pt-6 pb-8 mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center justify-center p-4">
                <img src="image.png" alt="Animación o Imagen" class="rounded shadow-md">
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-4">Formulario de Registro</h2>
                <form id="formularioRegistro" method="POST">
                    <div id="pagina1">
                        <!-- Cedula -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nro_doc">
                                Cédula
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nro_doc" name="nro_doc" type="text" placeholder="Cédula" required maxlength="10" class="w-full border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300 transition duration-300">
                            <small id="doc-error" class="text-red-600"></small>
                        </div>
                        <!-- Nombres -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombres">
                                Nombres
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase" id="nombres" name="nombres" type="text" placeholder="Nombres" required>
                        </div>
                        <!-- Apellidos -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="apellidos">
                                Apellidos
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase" id="apellidos" name="apellidos" type="text" placeholder="Apellidos" required>
                        </div>
                        <!-- Sexo -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="genero">
                                Sexo
                            </label>
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="genero" name="genero" required>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>                            
                            </select>
                        </div>
                        <!-- Fecha de Nacimiento -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="fec_nacimiento">
                                Fecha de Nacimiento
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase" id="fec_nacimiento" name="fec_nacimiento" type="text" placeholder="dd/mm/yyyy" required>
                        </div>
                    </div>
                    
                    <div id="pagina2" class="hidden">
                        <!-- Nacionalidad -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nacionalidad">
                                Nacionalidad
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase" id="nacionalidad" name="nacionalidad" type="text" placeholder="Nacionalidad" required>
                        </div>
                        <!-- Estado Civil -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="est_civil">
                                Estado Civil
                            </label>
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="est_civil" name="est_civil" required>
                                <option value="" disabled selected>Seleccione un estado civil</option>
                                <option value="SOLTERO">Soltero</option>
                                <option value="CASADO">Casado</option>
                                <option value="DIVORCIADO">Divorciado</option>
                                <option value="VIUDO">Viudo</option>
                                <option value="UNION">En unión de hecho</option>
                                <option value="NO_REGISTRA">No registra</option>
                            </select>
                        </div>
                        <!-- Código Dactilar -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="cod_dactilar">
                                Código Dactilar 
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase" id="cod_dactilar" name="cod_dactilar" type="text" placeholder="CódigoDactilar" required>
                        </div>
                        <!-- tienda -->
                        <div class="mb-4">                            
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase" id="tienda" name="tienda" type="hidden" value="<?php echo $_SESSION['tienda_id']; ?>">
                        </div>                       
                    </div>
                    <div class="flex items-center justify-between">
                        <button id="anterior" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hidden" type="button">
                            Anterior
                        </button>
                        <button id="siguiente" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Siguiente
                        </button>
                        <button id="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hidden" type="submit">
                            Insertar
                        </button>
                    </div>

                    <!-- Campos ocultos -->
                    <input type="hidden" id="user_registro" name="user_registro" value="<?php echo $_SESSION['nombre_gestor']; ?>">
                    <input type="hidden" id="ip_host" name="ip_host" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
                </form>
                <div id="message"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
       

        const siguienteBtn = document.getElementById('siguiente');
        const anteriorBtn = document.getElementById('anterior');
        const submitBtn = document.getElementById('submit');
        const pagina1 = document.getElementById('pagina1');
        const pagina2 = document.getElementById('pagina2');
        const form = document.getElementById('formularioRegistro');
        const messageDiv = document.getElementById('message');

        siguienteBtn.addEventListener('click', () => {
            pagina1.classList.add('hidden');
            pagina2.classList.remove('hidden');
            siguienteBtn.classList.add('hidden');
            anteriorBtn.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
        });

        anteriorBtn.addEventListener('click', () => {
            pagina2.classList.add('hidden');
            pagina1.classList.remove('hidden');
            siguienteBtn.classList.remove('hidden');
            anteriorBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('guardar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    swal('Registro exitoso', data.message, 'success').then(() => {
                        form.reset();
                        pagina2.classList.add('hidden');
                        pagina1.classList.remove('hidden');
                        siguienteBtn.classList.remove('hidden');
                        anteriorBtn.classList.add('hidden');
                        submitBtn.classList.add('hidden');
                    });
                } else {
                    swal('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
            });
        });
    </script>
</body>
</html>
