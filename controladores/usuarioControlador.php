<?php

    if ($peticionAjax) {
        require_once "../modelos/usuarioModelo.php";
    } else {
        require_once "./modelos/usuarioModelo.php";
    }

    class usuarioControlador extends usuarioModelo {

        /*---------- Controlador agregar usuario ----------*/
        public function agregar_usuario_controlador() {
            $dni = mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
            $nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
            $apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
            $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
            $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

            $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
            $email = mainModel::limpiar_cadena($_POST['usuario_email_reg']);
            $clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
            $clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);
            
            $privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);

            /*== Comprobar campos vacíos ==*/
            if ($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" ||
             $clave1 == "" || $clave2 == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No has completado todos los campos obligatorios.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /*== Verificar integridad de los datos ==*/
            if (mainModel::verificar_datos("[0-9-]{10,20}", $dni)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de DNI no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}", $nombre)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de NOMBRE no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}", $apellido)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de APELLIDO no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ($telefono != "") {
                if (mainModel::verificar_datos("[0-9()+]{8,20}", $telefono)) {
                    $alerta = [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El formato de TELÉFONO no es válido.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if ($direccion != "") {
                if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
                    $alerta = [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El formato de DIRECCIÓN no es válido.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de NOMBRE DE USUARIO no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave1) ||
                mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2)){
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El formato de CLAVES no es válido.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

            /*== Comprobar que el DNI no esté reg. en BD ==*/
            $check_dni = mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario
                WHERE usuario_dni = '$dni'");
            if ($check_dni->rowCount() > 0) {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobar usuario ==*/
            $check_user = mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario
                WHERE usuario_usuario = '$usuario'");
            if ($check_user->rowCount() > 0) {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE DE USUARIO ingresado ya se encuentra registrado en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobar email ==*/
            if ($email != "") {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $check_email = mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario
                        WHERE usuario_email = '$email'");
                    if ($check_email->rowCount() > 0) {
                        $alerta = [
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"El EMAIL ingresado ya se encuentra registrado en el sistema.",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El formato de EMAIL ingresado no es válido.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                
            }

            /*== Comprobar claves ==*/
            if ($clave1 != $clave2) {
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Las claves que acaba de ingresar no coinciden.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			} else {
				$clave = mainModel::encryption($clave1);
			}
            
            /*== Comprobar privilegio ==*/
            if ($privilegio < 1 || $privilegio > 3) {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No ha seleccionado un privilegio.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            $datos_usuario_reg = [
                "DNI"=>$dni,
                "Nombre"=>$nombre,
                "Apellido"=>$apellido,
                "Telefono"=>$telefono,
                "Direccion"=>$direccion,
                "Email"=>$email,
                "Usuario"=>$usuario,
                "Clave"=>$clave,
                "Estado"=>"Activa",
                "Privilegio"=>$privilegio
            ];

            $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

            if ($agregar_usuario->rowCount() == 1) {
                $alerta = [
					"Alerta"=>"limpiar",
					"Titulo"=>"Usuario registrado",
					"Texto"=>"Los datos del usuario han sido registrados exitosamente.",
					"Tipo"=>"success"
				];
            } else {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se pudo registrar el usuario.",
					"Tipo"=>"error"
				];
            }
            echo json_encode($alerta);
            /* Fin del controlador */

        }

        /*---------- Controlador paginar usuario ----------*/
        public function paginador_usuario_controlador($pagina, $registros, $privilegio,
         $id, $url, $busqueda) {

            $pagina = mainModel::limpiar_cadena($pagina);
            $registros = mainModel::limpiar_cadena($registros);
            $privilegio = mainModel::limpiar_cadena($privilegio);
            $id = mainModel::limpiar_cadena($id);

            $url = mainModel::limpiar_cadena($url);
            $url = SERVERURL.$url."/";

            $busqueda = mainModel::limpiar_cadena($busqueda);
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

            if (isset($busqueda) && $busqueda != "") {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id != '$id'
                    AND usuario_id != '1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre
                    LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_telefono
                    LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR usuario_usuario LIKE
                    '%$busqueda%')) ORDER BY usuario_apellido ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id !='$id'
                    AND usuario_id != '1' ORDER BY usuario_apellido ASC LIMIT $inicio, $registros";
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
                        <th>DNI</th>
                        <th>NOMBRE</th>
                        <th>TELÉFONO</th>
                        <th>USUARIO</th>
                        <th>EMAIL</th>
                        <th>ACTUALIZAR</th>
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>';

                if ($total >= 1 && $pagina <= $Npaginas) {
                    $contador = $inicio + 1;
                    $reg_inicio = $inicio + 1;
                    foreach ($datos as $rows) {
                        $tabla.='
                        <tr class="text-center" >
                            <td>'.$contador.'</td>
                            <td>'.$rows['usuario_dni'].'</td>
                            <td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'</td>
                            <td>'.$rows['usuario_telefono'].'</td>
                            <td>'.$rows['usuario_usuario'].'</td>
                            <td>'.$rows['usuario_email'].'</td>
                            <td>
                                <a href="'.SERVERURL.'user-update/'.mainModel::encryption($rows['usuario_id']).'/" 
                                class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>	
                                </a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php"
                                    method="POST" data-form="delete"
                                    autocomplete="off">
                                    <input type="hidden" name="usuario_id_del" value="'.mainModel::encryption($rows['usuario_id']).'">
                                    <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>';
                        $contador++;
                    }
                    $reg_final = $contador - 1;
                } else {
                    if ($total >= 1) {
                        $tabla.='<tr class="text-center" ><td colspan="9">
                        <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Click aquí para recargar el listado</a>
                        </td></tr>';
                    } else {
                        $tabla.='<tr class="text-center" ><td colspan="9">No hay registros en el
                    sistema.</td></tr>';
                    }
                    
                }
                $tabla.='</tbody></table></div>';

                if ($total >= 1 && $pagina <= $Npaginas) {
                    $tabla.='<p class="text-right">Mostrando usuario(s) '.$reg_inicio.'
                        al '.$reg_final.' de un total de '.$total.'</p>';

                    $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
                }

                return $tabla;

        } /* Fin del controlador */

        /*---------- Controlador eliminar usuario ----------*/
        public function eliminar_usuario_controlador() {

            /* recibiendo id del usuario */
            $id = mainModel::decryption($_POST['usuario_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* comprobando el usuario principal */
            if ($id == 1) { 
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El usuario principal del sistema no puede ser eliminado.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /* comprobando el usuario en BD */
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM
                usuario WHERE usuario_id = '$id'");

            if ($check_usuario->rowCount() <= 0) {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El usuario que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /* comprobando los préstamos */
            $check_prestamos = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM
                prestamo WHERE usuario_id = '$id' LIMIT 1"); // Con que tenga uno, ya es suficiente. No es necesario traer todos los préstamos de ese usuario con la query

            if ($check_prestamos->rowCount() > 0) {
                $alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se puede eliminar el usuario, ya que tiene préstamos
                        asociados, se recomienda deshabilitar el usuario si ya no será
                        utilizado.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /* comprobando privilegios */
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

        } /* Fin del controlador */
    }
    