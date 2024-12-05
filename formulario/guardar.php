<?php
require_once 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = array("status" => "", "message" => "");

    $nro_doc = $_POST['nro_doc'];
    $nombres = strtoupper($_POST['nombres']);
    $apellidos = strtoupper($_POST['apellidos']);
    $genero = strtoupper($_POST['genero']);
    $nacionalidad = strtoupper($_POST['nacionalidad']);
    $est_civil = strtoupper($_POST['est_civil']);
    $cod_dactilar = strtoupper($_POST['cod_dactilar']);
    $fec_nacimiento = $_POST['fec_nacimiento'];
    $tienda_id = $_SESSION['tienda_id'];
    $user_registro = $_POST['user_registro'];
    $ip_host = $_POST['ip_host'];

    $date_format = '/^\d{2}\/\d{2}\/\d{4}$/';
    if (!preg_match($date_format, $fec_nacimiento)) {
        $response["status"] = "error";
        $response["message"] = "La fecha de nacimiento debe estar en el formato día/mes/año (dd/mm/yyyy).";
        echo json_encode($response);
        exit();
    }

    try {
        $conn->beginTransaction();

        // Verificar si el usuario ya existe en registrocivil
        $query_verificar = "SELECT * FROM registrocivil WHERE cedula = ?";
        $stmt_verificar = $conn->prepare($query_verificar);
        $stmt_verificar->bindParam(1, $nro_doc);
        $stmt_verificar->execute();
        $registro_civil = $stmt_verificar->fetch(PDO::FETCH_ASSOC);

        if ($registro_civil === false) {
            // Insertar en la tabla registro
            $query_registro = "INSERT INTO registro(nro_doc, nombres, apellidos, genero, nacionalidad, est_civil, cod_dactilar, fec_nacimiento, tienda_id, user_registro, ip_host) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt_registro = $conn->prepare($query_registro);
            $stmt_registro->bindParam(1, $nro_doc);
            $stmt_registro->bindParam(2, $nombres);
            $stmt_registro->bindParam(3, $apellidos);
            $stmt_registro->bindParam(4, $genero);
            $stmt_registro->bindParam(5, $nacionalidad);
            $stmt_registro->bindParam(6, $est_civil);
            $stmt_registro->bindParam(7, $cod_dactilar);
            $stmt_registro->bindParam(8, $fec_nacimiento);
            $stmt_registro->bindParam(9, $tienda_id);
            $stmt_registro->bindParam(10, $user_registro);
            $stmt_registro->bindParam(11, $ip_host);
            $stmt_registro->execute();

            // Separar los nombres y apellidos
            $nombres_array = explode(" ", $nombres);
            $apellidos_array = explode(" ", $apellidos);

            // Preparar los campos para la tabla registrocivil
            $nombreprimero = $nombres_array[0] ?? '';
            $nombresegundo = $nombres_array[1] ?? '';
            $nombretercero = $nombres_array[2] ?? '';
            $nombrecuarto = $nombres_array[3] ?? '';
            $nombrequinto = $nombres_array[4] ?? '';
            $apellidopaterno = $apellidos_array[0] ?? '';
            $apellidomaterno = $apellidos_array[1] ?? '';

            $nombre_completo = $nombres . ' ' . $apellidos;

            // Insertar en la tabla registrocivil
            $query_civil = "INSERT INTO registrocivil(cedula, nombres, apellidopaterno, apellidomaterno, nombreprimero, nombresegundo, nombretercero, nombrecuarto, nombrequinto, cod_sexo, fecha_nacimiento, cod_estado_civil, nacionalidad) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt_civil = $conn->prepare($query_civil);
            $stmt_civil->bindParam(1, $nro_doc);
            $stmt_civil->bindParam(2, $nombre_completo); 
            $stmt_civil->bindParam(3, $apellidopaterno);
            $stmt_civil->bindParam(4, $apellidomaterno);
            $stmt_civil->bindParam(5, $nombreprimero);
            $stmt_civil->bindParam(6, $nombresegundo);
            $stmt_civil->bindParam(7, $nombretercero);
            $stmt_civil->bindParam(8, $nombrecuarto);
            $stmt_civil->bindParam(9, $nombrequinto);
            $stmt_civil->bindParam(10, $genero);
            $stmt_civil->bindParam(11, $fec_nacimiento);
            $stmt_civil->bindParam(12, $est_civil);
            $stmt_civil->bindParam(13, $nacionalidad);
            $stmt_civil->execute();

            //Valores que voy a agregar en la tabla registro_civil del 214
            $cod_condic = 58;
            $lugar_nacim = ($nacionalidad =='VENEZOLANO') ? 456 : 170;
            $genero_sql = ($genero == 'FEMENINO') ? 'MUJER' : 'HOMBRE';
            $fecha_actualizado = date('M') . date('Y'); 
            $cedula_magna = 1;
            $provincia ='PICHINCHA';
            $canton = 'QUITO';
            $parroquia = 'ITCHIMBIA';
            $profesion = 'SIN PROFESION';
            $localidad = 1;

            $query_sql = "INSERT INTO [dbo].[Registro_civil]
            (cedula, nombres, cod_sexo, COD_CONDIC, fecha_nacimiento, LUG_NACIM, DES_NACIONALID, cod_estado_civil, cedula_magna, INDIVIDUAL_DACTILAR, provincia, canton, parroquia, actualizado, COD_GENERO_LEY, DES_PROFESION, COD_LOCALIDAD, FECHA_FALLECIMIENTO)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt_sql = $conn_sql->prepare($query_sql);
            $stmt_sql->bindParam(1, $nro_doc);
            $stmt_sql->bindParam(2, $nombre_completo);
            $stmt_sql->bindParam(3, $genero_sql);
            $stmt_sql->bindParam(4, $cod_condic);
            $stmt_sql->bindParam(5, $fec_nacimiento);
            $stmt_sql->bindParam(6, $lugar_nacim);
            $stmt_sql->bindParam(7, $nacionalidad);
            $stmt_sql->bindParam(8, $est_civil);
            $stmt_sql->bindParam(9, $cedula_magna);
            $stmt_sql->bindParam(10, $cod_dactilar);
            $stmt_sql->bindParam(11, $provincia);
            $stmt_sql->bindParam(12, $canton);
            $stmt_sql->bindParam(13, $parroquia);
            $stmt_sql->bindParam(14, $fecha_actualizado);
            $stmt_sql->bindParam(15, $cedula_magna); 
            $stmt_sql->bindParam(16, $profesion);
            $stmt_sql->bindParam(17, $localidad);
            $stmt_sql->bindParam(18, $null);
            $stmt_sql->execute();




            $conn->commit();

            $response["status"] = "success";
            $response["message"] = "Registro insertado exitosamente a espera de aprobación.";
        } else {
            $response["status"] = "error";
            $response["message"] = "La cédula ya está registrada.";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        $response["status"] = "error";
        $response["message"] = "Ocurrió un error al insertar el registro: " . $e->getMessage();
    }

    echo json_encode($response);
}
?>