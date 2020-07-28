<?php

//Necesitamos acceso al código contenido en 'Conexion.php'
require_once 'Conexion.php';

//Creamos la clase Usuario
class Usuario {

    //Cada propiedad corresponde a una columna de la tabla `usuarios`
    private $id;
    private $nick;
    private $pass;
    private $rol;
    private $idEstacion;

    //Excepto la constante en la que guardamos el nombre de la tabla
    //pues siempre será la misma
    const USUARIOS = 'usuarios';

    //Métodos get y set correspondientes
    public function nuevo_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function nuevo_nick($nick) {
        $this->nick = nick;
    }

    public function get_nick() {
        return $this->nick;
    }

    public function nuevo_pass($pass) {
        $this->pass = $pass;
    }

    public function get_pass() {
        return $this->pass;
    }

    public function nuevo_rol($rol) {
        $this->rol = $rol;
    }

    public function get_rol() {
        return $this->rol;
    }

    public function nuevo_idEstacion($idEstacion) {
        $this->idEstacion = $idEstacion;
    }

    public function get_idEstacion() {
        return $this->idEstacion;
    }

    //Método constructor
    //El id consta como null porque en la tabla es un valor que se auto incrementa
    public function __construct($id = null, $nick, $pass, $rol, $idEstacion) {
        $this->id = $id;
        $this->nick = $nick;
        $this->pass = $pass;
        $this->rol = $rol;
        $this->idEstacion = $idEstacion;
    }

    //Para guardar los datos del usuario en la tabla de usuarios
    public function guardarDatos() {
        //Conectamos con la BBDD
        $conexion = new Conexion();
        //Preparamos la inserción de los datos
        $consulta = $conexion->prepare('INSERT INTO ' . self::USUARIOS . ' (nick, pass, rol, idEstacion) VALUES(:nick, :pass, :rol, :idEstacion)');
        //Identificamos los marcadores
        $consulta->bindParam(':nick', $this->nick);
        $consulta->bindParam(':pass', $this->pass);
        $consulta->bindParam(':rol', $this->rol);
        $consulta->bindParam(':idEstacion', $this->idEstacion);
        //Ejecutamos la consulta
        $consulta->execute();
        //Al finalizar la inserción recuperamos el id que se acaba de insertar
        $this->id = $conexion->lastInsertId();
        //Cerramos la conexión
        $conexion = null;
    }

    //Para eliminar los datos del usuario en la tabla de usuarios
    public function eliminar() {
        //Conectamos con la BBDD
        $conexion = new Conexion();
        //Preparamos le eliminación de los datos
        $consulta = $conexion->prepare('DELETE FROM ' . self::USUARIOS . ' WHERE nick = :nick AND pass = :pass');
        //Identificamos los datos
        $consulta->bindParam(':nick', $this->nick);
        $consulta->bindParam(':pass', $this->pass);
        //Ejecutamos el borrado
        $consulta->execute();
        //Cerramos la conexión
        $conexion = null;
    }

    //Método para listar los usuarios
    public static function listar() {
        //Conectamos con la BBDD
        $conexion = new Conexion();
        //Preparamos la selección de los datos de la tabla
        $consulta = $conexion->prepare('SELECT id, nick, pass, rol, idEstacion FROM ' . self::USUARIOS . ' ORDER BY id');
        //La ejecutamos
        $consulta->execute();
        //Y guardamos todas las filas en esta variable
        $registros = $consulta->fetchAll();
        //Devolvemos los datos
        return $registros;
        //Cerramos la conexion
        $conexion = null;
    }

    //Método para buscar coincidencias con el mismo nick y contraseña
    public static function buscar($nick, $pass) {
        //Conectamos con la BBDD
        $conexion = new Conexion();
        //Preparamos la consulta, seleccionando id, rol y id de la estación de las filas que coincidan con nick y contraseña
        $consulta = $conexion->prepare('SELECT id, rol, idEstacion FROM ' . self::USUARIOS . ' WHERE nick = :nick AND pass = :pass');
        //Identificamos los marcadores
        $consulta->bindParam(':nick', $nick);
        $consulta->bindParam(':pass', $pass);
        //Ejecutamos la consulta
        $consulta->execute();
        //Guardamos el resultado en la variable
        $registro = $consulta->fetch();
        //Devolvemos los datos si ha habido coincidencia
        if ($registro) {
            return new self($registro['id'], $nick, $pass, $registro['rol'], $registro['idEstacion']);
        } else {
            //Si no retornamos un false
            return false;
        }
        //Cerramos la conexion
        $conexion = null;
    }

    //Método para buscar coincidencias por nick
    //Funciona igual que el método anterior salvo que sólo se busca la coincidencia con el nick
    public static function buscarNick($nick) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT id, pass, rol, idEstacion FROM ' . self::USUARIOS . ' WHERE nick = :nick');
        $consulta->bindParam(':nick', $nick);
        $consulta->execute();
        $registro = $consulta->fetch();
        if ($registro) {
            return new self($registro['id'], $nick, $registro['pass'], $registro['rol'], $registro['idEstacion']);
        } else {
            return false;
        }
        //Cerramos la conexion
        $conexion = null;
    }

}

?>
 