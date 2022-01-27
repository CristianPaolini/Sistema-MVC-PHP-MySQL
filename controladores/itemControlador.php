<?php

    if ($peticionAjax) {
        require_once "../modelos/itemModelo.php";
    } else {
        require_once "./modelos/itemModelo.php";
    }
    
    class itemControlador extends itemModelo {
        
        /*---------- Controlador agregar item ----------*/
        public function agregar_item_controlador() {

        } /* Fin controlador */
    }