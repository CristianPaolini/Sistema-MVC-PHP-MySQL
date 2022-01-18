<?php

    if ($peticionAjax) {
        require_once "../modelos/clienteModelo.php";
    } else {
        require_once "./modelos/clienteModelo.php";
    }

    class clienteControlador extends clienteModelo {
        
        /*---------- Controlador agregar cliente ----------*/
        public function agregar_cliente_controlador() {
            $dni = mainModel::limpiar_cadena($_POST['cliente_dni_reg']);
            $nombre = mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
            $apellido = mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
            $telefono = mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
            $direccion = mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);

            /*== Comprobar campos vacíos ==*/
            if ($dni == "" || $nombre == "" || $apellido == "" || $telefono == "" ||
             $direccion == "") {
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