<?php

//Necesitaremos acceder a código que está dentro de estos archivos
//por eso los 'requerimos'
require_once 'Conexion.php';

//Una vez hecho, creamos la clase Temperatura
class Temperatura {

    //las propiedades equivalen a las columnas de la tabla `temperaturas`
    private $id;
    private $id_usuario;
    private $id_estacion;
    private $valTemperatura;

    //y siempre vamos a trabajar con la tabla `temperaturas`
    //por lo que utilizamos una constante
    const TEMP = 'temperaturas';

    //Métodos get y set correspondientes    
    public function nuevo_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function nuevo_id_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function get_id_usuario() {
        return $this->id_usuario;
    }

    public function nuevo_id_estacion($id_estacion) {
        $this->id_estacion = $id_estacion;
    }

    public function get_id_estacion() {
        return $this->id_estacion;
    }

    public function nuevo_valTemperatura($valTemperatura) {
        $this->valTemperatura = $valTemperatura;
    }

    public function get_valTemperatura() {
        return $this->valTemperatura;
    }

    //Método constructor
    //El id consta como null porque en la tabla es un valor que se auto incrementa
    public function __construct($id = null, $id_usuario, $id_estacion, $valTemperatura) {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->id_estacion = $id_estacion;
        $this->valTemperatura = $valTemperatura;
    }

    //Con este método nos conectamos a la BBDD 
    //(por eso escribimos require_once 'Conexion.php' al primcipio)
    //Para guardar en la tabla 'temperaturas' los datos
    public function guardarTemp() {
        //Nos conectamos a la BBDD
        $conexion = new Conexion();
        //Preparamos la inserción en la tabla los datos correspondientes
        $consulta = $conexion->prepare('INSERT INTO ' . self::TEMP . ' (id_usuario, id_estacion, valTemperatura) VALUES(:id_usuario, :id_estacion, :valTemperatura)');
        //Identificamos los marcadores
        $consulta->bindParam(':id_usuario', $this->id_usuario);
        $consulta->bindParam(':id_estacion', $this->id_estacion);
        $consulta->bindParam(':valTemperatura', $this->valTemperatura);
        //Y ejecutamos la inserción
        $consulta->execute();
        //Al finalizar la inserción recuperamos el id que se acaba de insertar
        $this->id = $conexion->lastInsertId();
        //Por último cerramos la conexión
        $conexion = null;
    }

    //Método para buscar coincidencias con el mismo id_usuario e id_estacion
    public static function buscarTemp($id_usuario, $id_estacion) {
        //Conectamos con la BBDD
        $conexion = new Conexion();
        //Preparamos la consulta, seleccionando id, rol y id de la estación de las filas que coincidan con nick y contraseña
        $consulta = $conexion->prepare('SELECT id, valTemperatura FROM ' . self::TEMP . ' WHERE id_usuario = :id_usuario AND id_estacion = :id_estacion');
        //Identificamos los marcadores
        $consulta->bindParam(':id_usuario', $id_usuario);
        $consulta->bindParam(':id_estacion', $id_estacion);
        //Ejecutamos la consulta
        $consulta->execute();
        //Guardamos el resultado en la variable
        $registro = $consulta->fetch();
        //Si el resultado está bien devolvemos su contenido
        if ($registro) {
            return new self($registro['id'], $id_usuario, $id_estacion, $registro['valTemperatura']);
        } else {
            //Si no decimos que no ha habido coincidencias
            return false;
        }
        //Cerramos la conexion
        $conexion = null;
    }

    //Para eliminar los datos de las temperaturas de un usuario concreto en la tabla de temperaturas
    public function eliminarTemp() {
        //Conectamos con la BBDD
        $conexion = new Conexion();
        //Preparamos la eliminación de los datos
        $consulta = $conexion->prepare('DELETE FROM ' . self::TEMP . ' WHERE id_usuario = :id_usuario');
        //Identificamos el id_usuario con el id del usuario actual
        $consulta->bindParam(':id_usuario', $this->id_usuario);
        //Ejecutamos la consulta
        $consulta->execute();
        //Cerramos la conexión
        $conexion = null;
    }

}
