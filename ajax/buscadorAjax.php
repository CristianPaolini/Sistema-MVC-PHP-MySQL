<?php
    session_start(['name'=>'SPM']);
    require_once "../config/APP.php";

    if (isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || 
        isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) {

            $data_url = [
                "usuario"=>"user-search",
                
            ];

            if (isset($_POST['modulo'])) {
                $modulo = $_POST['modulo'];
                if (!isset($data_url[$modulo])) {
                    $alerta = [
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No se pudo realizar la búsqueda debido a un error.",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo realizar la búsqueda debido a un error de configuración.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
    } else {
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    