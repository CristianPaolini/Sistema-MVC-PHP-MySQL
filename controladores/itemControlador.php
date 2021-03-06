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

        /*---------- Controlador paginar items ----------*/
        public function paginador_item_controlador($pagina, $registros, $privilegio,
        $url, $busqueda) {

            $pagina = mainModel::limpiar_cadena($pagina);
            $registros = mainModel::limpiar_cadena($registros);
            $privilegio = mainModel::limpiar_cadena($privilegio);

            $url = mainModel::limpiar_cadena($url);
            $url = SERVERURL.$url."/";

            $busqueda = mainModel::limpiar_cadena($busqueda);
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

            if (isset($busqueda) && $busqueda != "") {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE
                '%$busqueda%' OR item_nombre LIKE '%$busqueda%' ORDER BY item_nombre
                    ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item ORDER BY item_nombre 
                    ASC LIMIT $inicio, $registros";
            }

            $conexion = mainModel::conectar();

            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int)$total->fetchColumn();

            $Npaginas = ceil($total / $registros);

            $tabla.='<div class="table-responsive">
            <table class="table table-dark table-sm">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>CÓDIGO</th>
                        <th>NOMBRE</th>
                        <th>STOCK</th>
                        <th>ESTADO</th>
                        <th>DETALLE</th>';
                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla.='<th>ACTUALIZAR</th>';
                        }
                        if ($privilegio == 1) {
                            $tabla.='<th>ELIMINAR</th>';
                        }
            $tabla.='</tr>
                </thead>
                <tbody>';

                if ($total >= 1 && $pagina <= $Npaginas) {
                    $contador = $inicio + 1;
                    $reg_inicio = $inicio + 1;
                    foreach ($datos as $rows) {
                        $tabla.='
                        <tr class="text-center" >
                            <td>'.$contador.'</td>
                            <td>'.$rows['item_codigo'].'</td>
                            <td>'.$rows['item_nombre'].'</td>
                            <td>'.$rows['item_stock'].'</td>
                            <td>'.$rows['item_estado'].'</td>
                            <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
                            title="'.$rows['item_nombre'].'"
                            data-content="'.$rows['item_detalle'].'">
                            <i class="fas fa-info-circle"></i>
                                </button></td>';
                            if ($privilegio == 1 || $privilegio == 2) {
                        $tabla.='<td>
                                    <a href="'.SERVERURL.'item-update/'.mainModel::encryption($rows['item_id']).'/" 
                                    class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>	
                                    </a>
                                </td>';
                            }
                            if ($privilegio == 1) {
                        $tabla.='<td>
                                    <form class="FormularioAjax" action="'.SERVERURL.'ajax/itemAjax.php"
                                        method="POST" data-form="delete"
                                        autocomplete="off">
                                        <input type="hidden" name="item_id_del" value="'.mainModel::encryption($rows['item_id']).'">
                                        <button type="submit" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>';
                            }
                $tabla.='</tr>';
                        $contador++;
                    }
                    $reg_final = $contador - 1;
                } else {
                    if ($total >= 1) {
                        $tabla.='<tr class="text-center" ><td colspan="8">
                        <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Click aquí para recargar el listado</a>
                        </td></tr>';
                    } else {
                        $tabla.='<tr class="text-center" ><td colspan="8">No hay registros en el
                    sistema.</td></tr>';
                    }
                    
                }
                $tabla.='</tbody></table></div>';

                if ($total >= 1 && $pagina <= $Npaginas) {
                    $tabla.='<p class="text-right">Mostrando item(s) '.$reg_inicio.'
                        al '.$reg_final.' de un total de '.$total.'</p>';

                    $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
                }

                return $tabla;

        } /* Fin del controlador */

        /*---------- Controlador eliminar item ----------*/
        public function eliminar_item_controlador() {
        
            /*== recibiendo id del item ==*/
            $id = mainModel::decryption($_POST['item_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /*== comprobar item en BD ==*/
            $check_item = mainModel::ejecutar_consulta_simple("SELECT item_id FROM item
                WHERE item_id = '$id'");
            if ($check_item->rowCount() <= 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El item que intenta eliminar no existe en el sistema.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /*== comprobando detalles de préstamo ==*/
            $check_prestamos = mainModel::ejecutar_consulta_simple("SELECT item_id FROM detalle
                WHERE item_id = '$id' LIMIT 1");
            if ($check_prestamos->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se puede eliminar el item, ya que tiene préstamos
                    asociados, se recomienda deshabilitar el item si ya no será
                    utilizado.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /*== Comprobar los privilegios ==*/
            session_start(['name'=>'SPM']);
            if ($_SESSION['privilegio_spm'] != 1) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No tiene los permisos necesarios para realizar esta operación.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_item = itemModelo::eliminar_item_modelo($id);

            if ($eliminar_item->rowCount() == 1) {
                $alerta = [
					"Alerta"=>"recargar",
					"Titulo"=>"Item eliminado",
					"Texto"=>"El item ha sido eliminado del sistema exitosamente.",
					"Tipo"=>"success"
				];
            } else {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se pudo eliminar el item. Por favor, intente nuevamente.",
					"Tipo"=>"error"
				];
            }
            echo json_encode($alerta);

        } /* Fin del controlador */

        /*---------- Controlador datos item ----------*/
        public function datos_item_controlador($tipo, $id) {
            $tipo = mainModel::limpiar_cadena($tipo);

            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return itemModelo::datos_item_modelo($tipo, $id);
        } /* Fin del controlador */

        /*---------- Controlador actualizar item ----------*/
        public function actualizar_item_controlador() {
            /*== recuperar el id ==*/
            $id = mainModel::decryption($_POST['item_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /*== comprobar el item en la BD ==*/
            $check_item = mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE
                item_id = '$id'");
            if ($check_item->rowCount() <= 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se ha encontrado un cliente que corresponda a su búsqueda.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_item->fetch();
            }

            $codigo = mainModel::limpiar_cadena($_POST['item_codigo_up']);
            $nombre = mainModel::limpiar_cadena($_POST['item_nombre_up']);
            $stock = mainModel::limpiar_cadena($_POST['item_stock_up']);
            $estado = mainModel::limpiar_cadena($_POST['item_estado_up']);
            $detalle = mainModel::limpiar_cadena($_POST['item_detalle_up']);

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
            if ($codigo != $campos['item_codigo']) {
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
            }

            /*== Comprobar nombre ==*/
            if ($nombre != $campos['item_nombre']) {
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
            }

            /*== Comprobar privilegios ==*/
            session_start(['name'=>'SPM']);
            if ($_SESSION['privilegio_spm'] < 1 || $_SESSION['privilegio_spm'] > 2) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No tiene los permisos necesarios para realizar esta operación.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_item_up = [
                "Codigo"=>$codigo,
                "Nombre"=>$nombre,
                "Stock"=>$stock,
                "Estado"=>$estado,
                "Detalle"=>$detalle,
                "ID"=>$id
            ];

            if (itemModelo::actualizar_item_modelo($datos_item_up)) {
                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Datos actualizados",
                    "Texto"=>"Los datos del item han sido actualizados con éxito.",
                    "Tipo"=>"success"
                ];
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo actualizar los datos del item. Por favor, intente nuevamente.",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);
        } /* Fin del controlador */
    
    }