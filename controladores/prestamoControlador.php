<?php

    if ($peticionAjax) {
        require_once "../modelos/prestamoModelo.php";
    } else {
        require_once "./modelos/prestamoModelo.php";
    }
    
    class prestamoControlador extends prestamoModelo {
        
    }