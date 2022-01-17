<?php
    $peticionAjax = true;
    require_once "../config/APP.php";

    if (isset($_POST['cliente_dni_reg'])) { 

        /*---------- Instancia al controlador ----------*/
        require_once "../controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();
        
    } else {
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    