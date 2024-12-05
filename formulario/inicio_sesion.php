<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function fetchStore() {
            const email = document.getElementById('email').value;
            if (email) {
                const response = await fetch('fetch_store.php?email=' + email);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('tienda').value = data.store_name;
                } else {
                    Swal.fire('Usuario no encontrado', '', 'error');
                }
            }
        }

        function showModal() {
            const modal = document.getElementById('passwordModal');
            modal.style.display = 'block';
            const overlay = document.getElementById('modalOverlay');
            overlay.style.display = 'block';
        }

        function hideModal() {
            const modal = document.getElementById('passwordModal');
            modal.style.display = 'none';
            const overlay = document.getElementById('modalOverlay');
            overlay.style.display = 'none';
        }

        function handleSubmit(event) {
            event.preventDefault();
            showModal();
        }

        async function submitLogin() {
            const password = document.getElementById('modalPassword').value;
            if (password) {
                const form = document.getElementById('loginForm');
                const formData = new FormData(form);
                formData.append('password', password);

                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        title: 'Inicio de sesión exitoso',
                        text: 'Redirigiendo...',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } else {
                Swal.fire('Por favor, introduzca la contraseña.', '', 'error');
            }
        }
    </script>
    <style>
        .bg-soft-blue {
            background-color: #4a90e2;
        }
        .bg-semi-transparent {
            background-color: rgba(255, 255, 255, 0.5);
        }
        .bg-gradient {
            background: linear-gradient(to right, #AEE96C, #75CCED);
        }
        .form-bg {
            background-color: rgba(255, 255, 255, 0.8);
        }
        #passwordModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border-radius: 8px;
        }
        #modalOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 500;
        }
    </style>
</head>
<body class="bg-gradient flex items-center justify-center h-screen">
    <div class="max-w-md w-full bg-semi-transparent shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        <div class="text-center mb-4">
            <img src="image.png" alt="Imagen de inicio de sesión" class="mx-auto w-24 h-24">
        </div>
        <form id="loginForm" onsubmit="handleSubmit(event)">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Correo</label>
                <input name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" required onblur="fetchStore()">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tienda">Tienda</label>
                <input name="tienda" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tienda" type="text" readonly>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Iniciar Sesión</button>
            </div>
        </form>
    </div>

    <div id="modalOverlay" onclick="hideModal()"></div>
    <div id="passwordModal" class="bg-semi-transparent">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="modalPassword">Contraseña</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="modalPassword" type="password" required>
        <div class="flex items-center justify-between mt-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="submitLogin()">Enviar</button>
            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="hideModal()">Cancelar</button>
        </div>
    </div>
</body>
</html>
