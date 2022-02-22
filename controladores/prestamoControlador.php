<?php

    if ($peticionAjax) {
        require_once "../modelos/prestamoModelo.php";
    } else {
        require_once "./modelos/prestamoModelo.php";
    }
    
    class prestamoControlador extends prestamoModelo {
        
        /*---------- Controlador buscar cliente préstamo ----------*/
        public function buscar_cliente_prestamo_controlador() {
            /* Recuperar el texto */
            $cliente = mainModel::limpiar_cadena($_POST['buscar_cliente']);

            /* Comprobar texto */
            if ($cliente == "") {
                return '<div class="alert alert-warning" role="alert">
                            <p class="text-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                Debe introducir al menos uno de los siguientes valores: DNI, NOMBRE, APELLIDO, TELÉFONO.
                            </p>
                        </div>';
                        exit();
            }

            /* Seleccionar clientes en BD */
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
            /* Recuperar el id */
            $id = mainModel::limpiar_cadena($_POST['id_agregar_cliente']);

            /* Comprobando el cliente en la BD */
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
            
            /* Iniciando la sesión */
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
    }