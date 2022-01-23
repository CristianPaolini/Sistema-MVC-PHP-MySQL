<?php

    require_once "mainModel.php";

    class empresaModelo extends mainModel {

        /*---------- Modelo datos empresa ----------*/
        protected static function datos_empresa_modelo() {
            $sql = mainModel::conectar()->prepare("SELECT * FROM empresa");

            $sql->execute();
            return $sql;
        }
    }