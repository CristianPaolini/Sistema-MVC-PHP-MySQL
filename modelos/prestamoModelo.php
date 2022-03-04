<?php

    require_once "mainModel.php";

    class prestamoModelo extends mainModel {

        /*---------- Modelo agregar préstamo ----------*/
        protected static function agregar_prestamo_modelo($datos) {
            $sql = mainModel::conectar()->prepare("INSERT INTO prestamo(prestamo_codigo, prestamo_fecha_inicio, prestamo_hora_inicio,
                prestamo_fecha_final, prestamo_hora_final, prestamo_cantidad, prestamo_total, prestamo_pagado, prestamo_estado,
                prestamo_observacion, usuario_id, cliente_id)
                    VALUES(:Codigo, :FechaInicio, :HoraInicio, :FechaFinal, :HoraFinal, :Cantidad, :Total, :Pagado, :Estado,
                        :Observacion, :Usuario, :Cliente)");

            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":FechaInicio", $datos['FechaInicio']);
            $sql->bindParam(":HoraInicio", $datos['HoraInicio']);
            $sql->bindParam(":FechaFinal", $datos['FechaFinal']);
            $sql->bindParam(":HoraFinal", $datos['HoraFinal']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);
            $sql->bindParam(":Total", $datos['Total']);
            $sql->bindParam(":Pagado", $datos['Pagado']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":Observacion", $datos['Observacion']);
            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Cliente", $datos['Cliente']);
            $sql->execute();

            return $sql;
        }
    }