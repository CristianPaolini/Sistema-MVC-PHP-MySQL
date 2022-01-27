<?php
    $peticionAjax = true;
    require_once "../config/APP.php";

    if (isset($_POST['item_codigo_reg'])) { 

        /*---------- Instancia al controlador ----------*/
        require_once "../controladores/itemControlador.php";
        $ins_item = new itemControlador();

        /*---------- Agregar un item ----------*/
        if (isset($_POST['item_codigo_reg'])) {
            echo $ins_item->agregar_item_controlador()
        }

    } else {
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    