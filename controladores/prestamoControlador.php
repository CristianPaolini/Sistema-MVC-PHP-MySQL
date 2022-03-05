<?php

    if ($peticionAjax) {
        require_once "../modelos/prestamoModelo.php";
    } else {
        require_once "./modelos/prestamoModelo.php";
    }
    
    class prestamoControlador extends prestamoModelo {
        
        /*---------- Controlador buscar cliente préstamo ----------*/
        public function buscar_cliente_prestamo_controlador() {
            /*== Recuperando el texto ==*/
            $cliente = mainModel::limpiar_cadena($_POST['buscar_cliente']);

            /*== Comprobando texto ==*/
            if ($cliente == "") {
                return '<div class="alert alert-warning" role="alert">
                            <p class="text-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                Debe introducir al menos uno de los siguientes valores: DNI, NOMBRE, APELLIDO, TELÉFONO.
                            </p>
                        </div>';
                        exit();
            }

            /*== Seleccionando clientes en BD ==*/
            $datos_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%'
                OR cliente_nombre LIKE '%$cliente%' OR cliente_apellido LIKE '%$cliente%' OR cliente_telefono LIKE
                '%$cliente%' ORDER BY cliente_apellido ASC");

            if ($datos_cliente->rowCount() >= 1) {
                $datos_cliente = $datos_cliente->fetchAll();

                $tabla = '<div class="table-responsive"><table class="table
                    table-hover table-bordered table-sm"><tbody>';
                foreach ($datos_cliente as $rows) {
                    $tabla.= '<tr class="text-center">
                                <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' - '.$rows['cliente_dni'].'</td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="agregar_cliente('.$rows['cliente_id'].')">
                                    <i class="fas fa-user-plus"></i>
                                    </button>
                                </td>
                            </tr>';
                }
                $tabla.= '</tbody></table>
                    </div>';
                return $tabla;
                    
            } else {
                return '<div class="alert alert-warning" role="alert">
                            <p class="text-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                No hemos encontrado ningún cliente en el sistema que coincida
                                con <strong>“'.$cliente.'”</strong>
                            </p>
                        </div>';
                        exit();
            }
            
        } /* Fin controlador */

        /*---------- Controlador agregar cliente préstamo ----------*/
        public function agregar_cliente_prestamo_controlador() {
            /*== Recuperando el id ==*/
            $id = mainModel::limpiar_cadena($_POST['id_agregar_cliente']);

            /*== Comprobando el cliente en la BD ==*/
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE
                cliente_id = '$id'");

            if ($check_cliente->rowCount() <= 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se encontró el cliente en la base de datos.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_cliente->fetch();
            }
            
            /*== Iniciando la sesión ==*/
            session_start(['name'=>'SPM']);

            if (empty($_SESSION['datos_cliente'])) {
                $_SESSION['datos_cliente'] = [
                    "ID"=>$campos['cliente_id'],
                    "DNI"=>$campos['cliente_dni'],
                    "Nombre"=>$campos['cliente_nombre'],
                    "Apellido"=>$campos['cliente_apellido'],

                ];

                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cliente agregado",
                    "Texto"=>"El cliente se agregó para realizar un préstamo.",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            } else { // Si ya hay un cliente guardado en sesión, viene por el else
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo agregar el cliente al préstamo.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            }
            
        } /* Fin controlador */

        /*---------- Controlador eliminar cliente préstamo ----------*/
        public function eliminar_cliente_prestamo_controlador() {

            /*== Iniciando la sesión ==*/
            session_start(['name'=>'SPM']);

            unset($_SESSION['datos_cliente']);

            if (empty($_SESSION['datos_cliente'])) {
                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cliente removido",
                    "Texto"=>"Los datos del cliente han sido removidos con éxito.",
                    "Tipo"=>"success"
                ];
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo remover los datos del cliente.",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);

        } /* Fin controlador */

        /*---------- Controlador buscar item préstamo ----------*/
        public function buscar_item_prestamo_controlador() {
            /*== Recuperando el texto ==*/
            $item = mainModel::limpiar_cadena($_POST['buscar_item']);

            /*== Comprobando texto ==*/
            if ($item == "") {
                return '<div class="alert alert-warning" role="alert">
                            <p class="text-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                Debe introducir al menos uno de los siguientes valores: CÓDIGO, NOMBRE DEL ITEM.
                            </p>
                        </div>';
                        exit();
            }

            /*== Seleccionando items en BD ==*/
            $datos_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE (item_codigo LIKE '%$item%'
                OR item_nombre LIKE '%$item%') AND (item_estado = 'Habilitado') ORDER BY item_nombre ASC");

            if ($datos_item->rowCount() >= 1) {
                $datos_item = $datos_item->fetchAll();

                $tabla = '<div class="table-responsive"><table class="table
                    table-hover table-bordered table-sm"><tbody>';
                foreach ($datos_item as $rows) {
                    $tabla.= '<tr class="text-center">
                                <td>'.$rows['item_codigo'].'-'.$rows['item_nombre'].'</td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="modal_agregar_item('.$rows['item_id'].')">
                                    <i class="fas fa-box-open"></i>
                                    </button>
                                </td>
                            </tr>';
                }
                $tabla.= '</tbody></table>
                    </div>';
                return $tabla;
                    
            } else {
                return '<div class="alert alert-warning" role="alert">
                            <p class="text-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                No hemos encontrado ningún item en el sistema que coincida
                                con <strong>“'.$item.'”</strong>
                            </p>
                        </div>';
                        exit();
            }
        } /* Fin controlador */

        /*---------- Controlador agregar item préstamo ----------*/
        public function agregar_item_prestamo_controlador() {

            /*== Recuperando id del item ==*/
            $id = mainModel::limpiar_cadena($_POST['id_agregar_item']);

            /*== Comprobando item en BD ==*/
            $check_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id = '$id' AND
                item_estado = 'Habilitado'");
            if ($check_item->rowCount() <= 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se encontró el item en la base de datos.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_item->fetch();
            }

            /*== Recuperando detalles del préstamo ==*/
            $formato = mainModel::limpiar_cadena($_POST['detalle_formato']);
            $cantidad = mainModel::limpiar_cadena($_POST['detalle_cantidad']);
            $tiempo = mainModel::limpiar_cadena($_POST['detalle_tiempo']);
            $costo = mainModel::limpiar_cadena($_POST['detalle_costo_tiempo']);

            /*== Comprobando campos vacíos ==*/
            if ($cantidad == "" || $tiempo == "" || $costo == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No ha completado todos los campos obligatorios.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /*== Verificando integridad de los datos ==*/
            if (mainModel::verificar_datos("[0-9]{1,7}", $cantidad)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de CANTIDAD no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[0-9]{1,7}", $tiempo)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de TIEMPO no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[0-9.]{1,15}", $costo)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de COSTO no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ($formato != "Horas" && $formato != "Dias" && $formato != "Evento" && $formato != "Mes") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de FORMATO no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            session_start(['name'=>'SPM']);

            if (empty($_SESSION['datos_item'][$id])) {
                $costo = number_format($costo, 2, '.', '');

                $_SESSION['datos_item'][$id] = [
                    "ID"=>$campos['item_id'],
                    "Codigo"=>$campos['item_codigo'],
                    "Nombre"=>$campos['item_nombre'],
                    "Detalle"=>$campos['item_detalle'],
                    "Formato"=>$formato,
                    "Cantidad"=>$cantidad,
                    "Tiempo"=>$tiempo,
                    "Costo"=>$costo
                ];

                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Item agregado",
                    "Texto"=>"El item ha sido agregado para realizar un préstamo.",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El item que intenta agregar ya se encuentra agregado.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
        } /* Fin controlador */

        /*---------- Controlador eliminar item préstamo ----------*/
        public function eliminar_item_prestamo_controlador() {
            /*== Recuperando el id del item ==*/
            $id = mainModel::limpiar_cadena($_POST['id_eliminar_item']);

            /*== Iniciando la sesión ==*/
            session_start(['name'=>'SPM']);

            unset($_SESSION['datos_item'][$id]); // remuevo item mediante el id

            if (empty($_SESSION['datos_item'][$id])) { // si está vacío, el item fue removido en la línea anterior
                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Item removido",
                    "Texto"=>"Los datos del item han sido removidos con éxito.",
                    "Tipo"=>"success"
                ];
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo remover los datos del item.",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);

        } /* Fin controlador */

        /*---------- Controlador datos préstamo ----------*/
        public function datos_prestamo_controlador($tipo, $id) {
            $tipo = mainModel::limpiar_cadena($tipo);

            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return prestamoModelo::datos_prestamo_modelo($tipo, $id);
        } /* Fin controlador */

        /*---------- Controlador agregar préstamo ----------*/
        public function agregar_prestamo_controlador() {

        } /* Fin controlador */
    }