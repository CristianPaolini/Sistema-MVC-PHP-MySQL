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

            /*== Comprobar código ==*/
            $check_codigo = mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE
                item_codigo = '$codigo'");
            if ($check_codigo->rowCount() >= 1) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El CÓDIGO de item ingresado ya se encuentra registrado en el sistema.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $check_nombre = mainModel::ejecutar_consulta_simple("SELECT item_nombre FROM item WHERE
                item_nombre = '$nombre'");
            if ($check_nombre->rowCount() >= 1) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El NOMBRE de item ingresado ya se encuentra registrado en el sistema.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_item_reg = [
                "Codigo"=>$codigo,
                "Nombre"=>$nombre,
                "Stock"=>$stock,
                "Estado"=>$estado,
                "Detalle"=>$detalle
            ];

            $agregar_item = itemModelo::agregar_item_modelo($datos_item_reg);

            if ($agregar_item->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"limpiar",
                    "Titulo"=>"Item registrado",
                    "Texto"=>"Los datos del item han sido registrados exitosamente.",
                    "Tipo"=>"success"
                ];
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo registrar el item. Por favor, intente nuevamente.",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);
        } /* Fin controlador */
    }