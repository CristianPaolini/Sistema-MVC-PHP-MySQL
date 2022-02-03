<?php

    if ($peticionAjax) {
        require_once "../modelos/prestamoModelo.php";
    } else {
        require_once "./modelos/prestamoModelo.php";
    }
    
    class prestamoControlador extends prestamoModelo {
        
        /*---------- Controlador buscar cliente préstamo ----------*/
        public function buscar_cliente_prestamo_controlador() {
            
        } /* Fin controlador */
    }