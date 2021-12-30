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

    }
    