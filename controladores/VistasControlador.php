<?php

    require_once "./modelos/vistasModelo.php";

    class vistasControlador extends vistasModelo {

        /*---------- Controlador obtener plantilla ----------*/
        public function obtener_plantilla_controlador() {
            return require_once "./vistas/plantilla.php";
        } /* Fin del controlador */

        /*---------- Controlador obtener vistas ----------*/
        public function obtener_vistas_controlador() {
            if (isset($_GET['views'])) { // Ver .htaccess, viene por método GET esta variable
                $ruta = explode("/", $_GET['views']);
                $respuesta = vistasModelo::obtener_vistas_modelo($ruta[0]);

            } else {
                $respuesta = "login";
            }
            return $respuesta;
        } /* Fin del controlador */
    }