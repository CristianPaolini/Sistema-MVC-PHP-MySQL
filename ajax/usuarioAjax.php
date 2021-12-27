<?php
    $peticionAjax = true;
    require_once "../config/APP.php";

    if () {
        
        /*---------- Instancia al controlador ----------*/
        require_once "../controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();
    } else {
        session_start(['name'=>'SPM']); //SPM = Sistema de Préstamos de Mobiliario
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    