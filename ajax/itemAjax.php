<?php
    $peticionAjax = true;
    require_once "../config/APP.php";

    if () { 

        /*---------- Instancia al controlador ----------*/
        require_once "../controladores/itemControlador.php";
        $ins_item = new itemControlador();

    } else {
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    