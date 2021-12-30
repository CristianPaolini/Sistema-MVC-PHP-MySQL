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
        }
    }