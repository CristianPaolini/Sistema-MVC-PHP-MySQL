<?php
if ($peticionAjax) {
        require_once "../modelos/loginModelo.php";
    } else {
        require_once "./modelos/loginModelo.php";
    }

    class loginControlador extends loginModelo {

        /*---------- Controlador iniciar sesión ----------*/
        public function iniciar_sesion_controlador() {
            $usuario = mainModel::limpiar_cadena($_POST['usuario_log']);
            $clave = mainModel::limpiar_cadena($_POST['clave_log']);

            /*== Comprobar campos vacíos ==*/
            if ($usuario == "" || $clave == "") {
                echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "No ha llenado todos los campos requeridos.",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
            }

            /*== Verificar integridad de los datos ==*/
            if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $usuario)) {
                echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "El formato de NOMBRE DE USUARIO no es válido.",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
            }

            if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
                echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "El formato de CLAVE no es válido.",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
            }

            $clave = mainModel::encryption($clave);

            $datos_login = [
                "Usuario"=>$usuario,
                "Clave"=>$clave
            ];

            $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);

            if ($datos_cuenta->rowCount() == 1) {
                $row = $datos_cuenta->fetch();

                session_start(['name'=>'SPM']); //Inicio sesión

                $_SESSION['id_spm'] = $row['usuario_id']; //Se guarda el id del usuario logueado en la sesión
                $_SESSION['nombre_spm'] = $row['usuario_nombre'];
                $_SESSION['apellido_spm'] = $row['usuario_apellido'];
                $_SESSION['usuario_spm'] = $row['usuario_usuario'];
                $_SESSION['privilegio_spm'] = $row['usuario_privilegio'];
                $_SESSION['token_spm'] = md5(uniqid(mt_rand(), true));

                return header("Location: ".SERVERURL."home/");

            } else {
                echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "El NOMBRE DE USUARIO ingresado no existe o la CLAVE es inválida.",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
            }
            

        }
    }