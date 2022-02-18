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

        } /* Fin controlador */
    }