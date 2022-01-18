<?php

    require_once "mainModel.php";

    class clienteModelo extends mainModel {

        /*---------- Modelo agregar cliente ----------*/
        protected static function agregar_cliente_modelo($datos) {
            $sql = mainModel::conectar()->prepare("INSERT INTO cliente(cliente_dni, cliente_nombre,
                cliente_apellido, cliente_telefono, cliente_direccion)
                    VALUES(:DNI, :Nombre, :Apellido, :Telefono, :Direccion)");

            $sql->bindParam(":DNI", $datos['DNI']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Apellido", $datos['Apellido']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            $sql->execute();

            return $sql;
        }
    }