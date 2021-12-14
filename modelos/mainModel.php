<?php

    if ($peticionAjax) {
        require_once "../config/SERVER.php";
    } else {
        require_once "./config/SERVER.php";
    }
    
    class mainModel {

        /*---------- Función conectar a BD ----------*/
        protected static function conectar() {
            $conexion = new PDO(SGBD, USER, PASS);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        }

        /*---------- Función ejecutar consultas simples ----------*/
        protected static function ejecutar_consulta_simple($consulta) {
            $sql = self::conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        /*---------- Encriptar cadenas ----------*/
        public function encryption($string) { //Public porque va a ser empleada en múltiples Views.
			$output = FALSE;
			$key = hash('sha256', SECRET_KEY);
			$iv = substr(hash('sha256', SECRET_IV), 0, 16);
			$output = openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output = base64_encode($output);
			return $output;
		}

        /*---------- Desencriptar cadenas ----------*/
		protected static function decryption($string) {
			$key = hash('sha256', SECRET_KEY);
			$iv = substr(hash('sha256', SECRET_IV), 0, 16);
			$output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		}

        /*---------- Función generar códigos aleatorios ----------*/
        protected static function generar_codigo_aleatorio($letra, $longitud, $numero) {
            for($i = 1; $i <= $longitud; $i++) {
                $aleatorio = rand(0, 9);
                $letra.=$aleatorio; //Concatena
            }
            return $letra."-".$numero;
        }

        /*---------- Función limpiar cadenas ----------*/
        protected static function limpiar_cadena($cadena) { //Método que previene inyecciones SQL y otros tipos de ataques
            $cadena = trim($cadena);
            $cadena = stripslashes($cadena);
            $cadena = str_ireplace("<script>", "", $cadena);
            $cadena = str_ireplace("</script>", "", $cadena);
            $cadena = str_ireplace("<script src", "", $cadena);
            $cadena = str_ireplace("<script type=", "", $cadena);
            $cadena = str_ireplace("SELECT * FROM", "", $cadena);
            $cadena = str_ireplace("DELETE FROM", "", $cadena);
            $cadena = str_ireplace("INSERT INTO", "", $cadena);
            $cadena = str_ireplace("DROP TABLE", "", $cadena);
            $cadena = str_ireplace("DROP DATABASE", "", $cadena);
            $cadena = str_ireplace("TRUNCATE TABLE", "", $cadena);
            $cadena = str_ireplace("SHOW TABLES", "", $cadena);
            $cadena = str_ireplace("SHOW DATABASES", "", $cadena);
            $cadena = str_ireplace("<?php", "", $cadena);
            $cadena = str_ireplace("--", "", $cadena);
            $cadena = str_ireplace(">", "", $cadena);
            $cadena = str_ireplace("<", "", $cadena);
            $cadena = str_ireplace("[", "", $cadena);
            $cadena = str_ireplace("]", "", $cadena);
            $cadena = str_ireplace("^", "", $cadena);
            $cadena = str_ireplace("==", "", $cadena);
            $cadena = str_ireplace(";", "", $cadena);
            $cadena = str_ireplace("::", "", $cadena);
            $cadena = stripslashes($cadena);
            $cadena = trim($cadena);
            return $cadena;

        }

    }