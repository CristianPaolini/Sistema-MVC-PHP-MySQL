<?php
    session_start(['name'=>'SPM']);
    require_once "../config/APP.php";

    if (isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || 
        isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) {
        # code...
    } else {
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    