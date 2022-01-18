<?php

    if ($peticionAjax) {
        require_once "../modelos/clienteModelo.php";
    } else {
        require_once "./modelos/clienteModelo.php";
    }

    class clienteControlador extends clienteModelo {
        
        /*---------- Controlador agregar cliente ----------*/
        public function agregar_cliente_controlador() {
    
        } /* Fin controlador */
    }