<?php
    $peticionAjax = true;
    require_once "../config/APP.php";

    if () {
        

    } else {
        session_start(['name'=>'SPM']); //SPM = Sistema de Préstamos de Mobiliario
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    