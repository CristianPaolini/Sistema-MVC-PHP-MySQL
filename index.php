<?php

    require_once "./config/APP.php";
    require_once "./controladores/VistasControlador.php";

    $plantilla = new vistasControlador();
    $plantilla->obtener_plantilla_controlador();