<?php

//Creamos una clase para la conexión
//Es una clase que hereda de PDO

class Conexion extends PDO {

    //guardamos la información necesaria para la conexión 
    //en las propiedades de la clase
    private $tipoDB = 'mysql';
    private $host = 'localhost';
    private $bbdd = 'meteogalicia';
    private $usuario = 'dwes';
    private $password = 'abc123.';

    //Sobreescribimos el método constructor de la clase PDO
    public function __construct() {
        //Usamos un try catch para controlar posibles errores de conexión a la BBDD
        try {
            parent::__construct($this->tipoDB . ':host=' . $this->host . ';dbname=' . $this->bbdd, $this->usuario, $this->password);
        } catch (PDOException $e) {
            echo "Error.No se puede conectar a la base de datos.\n" . $e->getMessage();
            exit;
        }
    }

}
