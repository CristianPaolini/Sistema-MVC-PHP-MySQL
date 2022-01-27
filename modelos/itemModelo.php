<?php

    require_once "mainModel.php";

    class itemModelo extends mainModel {

        /*---------- Modelo agregar item ----------*/
        protected static function agregar_item_modelo($datos) {
            $sql = mainModel::conectar()->prepare("INSERT INTO item(item_codigo, item_nombre, item_stock,
                item_estado, item_detalle) VALUES(:Codigo, :Nombre, :Stock, :Estado, :Detalle)");

            $sql->bindParam(':Codigo', $datos['Codigo']);
            $sql->bindParam(':Nombre', $datos['Nombre']);
            $sql->bindParam(':Stock', $datos['Stock']);
            $sql->bindParam(':Estado', $datos['Estado']);
            $sql->bindParam(':Detalle', $datos['Detalle']);
            $sql->execute();

            return $sql;
        }
    }