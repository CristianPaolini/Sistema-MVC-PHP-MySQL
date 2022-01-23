<?php

    if ($peticionAjax) {
        require_once "../modelos/empresaModelo.php";
    } else {
        require_once "./modelos/empresaModelo.php";
    }

    class empresaControlador extends empresaModelo {

        /*---------- Controlador datos empresa ----------*/
        public function datos_empresa_controlador() {
            return empresaModelo::datos_empresa_modelo();
        } /* Fin controlador */

        /*---------- Controlador agregar empresa ----------*/
        public function agregar_empresa_controlador() {
            $nombre = mainModel::limpiar_cadena($_POST['empresa_nombre_reg']);
            $email = mainModel::limpiar_cadena($_POST['empresa_email_reg']);
            $telefono = mainModel::limpiar_cadena($_POST['empresa_telefono_reg']);
            $direccion = mainModel::limpiar_cadena($_POST['empresa_direccion_reg']);

            /*== Comprobar campos vacíos ==*/
            if ($nombre == "" || $email == "" || $telefono == "" ||
             $direccion == "") {
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
            if (mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ. ]{1,70}", $nombre)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de NOMBRE DE EMPRESA no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

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

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El formato de EMAIL no es válido.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /*== Comprobar empresas registradas ==*/
            $check_empresas = mainModel::ejecutar_consulta_simple("SELECT empresa_id FROM empresa");
            if ($check_empresas->rowCount() >= 1) {
                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Ya existe una empresa registrada, no es posible registrar más.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_empresa_reg = [
                "Nombre"=>$nombre,
                "Email"=>$email,
                "Telefono"=>$telefono,
                "Direccion"=>$direccion,
            ];

            $agregar_empresa = empresaModelo::agregar_empresa_modelo($datos_empresa_reg);
            if ($agregar_empresa->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"recargar",
                    "Titulo"=>"Empresa registrada",
                    "Texto"=>"Los datos de la empresa han sido registrados exitosamente.",
                    "Tipo"=>"success"
                ];
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo registrar la empresa. Por favor, intente nuevamente.",
                    "Tipo"=>"error"
                ];
            }
            echo json_encode($alerta);
        } /* Fin controlador */
    }