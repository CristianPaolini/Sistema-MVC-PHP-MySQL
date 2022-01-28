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

            /*== Verificar integridad de los datos ==*/
            if (mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}", $codigo)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de CÓDIGO no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $nombre)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de NOMBRE no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[0-9]{1,9}", $stock)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de STOCK no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ($detalle != "") {
                if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $detalle)) {
                    $alerta = [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El formato de DETALLE de item no es válido.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if ($estado != "Habilitado" && $estado != "Deshabilitado") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de ESTADO de item no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
        } /* Fin controlador */
    }