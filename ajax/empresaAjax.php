<?php
    $peticionAjax = true;
    require_once "../config/APP.php";

    if () { 

        /*---------- Instancia al controlador ----------*/
        require_once "../controladores/empresaControlador.php";
        $ins_empresa = new empresaControlador();

    } else {
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    