<?php

    if ($peticionAjax) {
        require_once "../modelos/empresaModelo.php";
    } else {
        require_once "./modelos/empresaModelo.php";
    }

    class empresaControlador extends empresaModelo {

        /*---------- Controlador datos empresa ----------*/
        public function datos_empresa_controlador() {
            return empresaModelo::datos_empresa_modelo();
        } /* Fin controlador */
    }