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

        }
    }