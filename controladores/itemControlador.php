<?php

    if ($peticionAjax) {
        require_once "../modelos/itemModelo.php";
    } else {
        require_once "./modelos/itemModelo.php";
    }
    
    class itemControlador extends itemModelo {
        
        /*---------- Controlador agregar item ----------*/
        public function agregar_item_controlador() {
            $codigo = mainModel::limpiar_cadena($_POST['item_codigo_reg']);
            $nombre = mainModel::limpiar_cadena($_POST['item_nombre_reg']);
            $stock = mainModel::limpiar_cadena($_POST['item_stock_reg']);
            $estado = mainModel::limpiar_cadena($_POST['item_estado_reg']);
            $detalle = mainModel::limpiar_cadena($_POST['item_detalle_reg']);

            /*== Comprobar campos vacíos ==*/
            if ($codigo == "" || $nombre == "" || $stock == "" ||
             $estado == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No ha completado todos los campos obligatorios.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
        } /* Fin controlador */
    }