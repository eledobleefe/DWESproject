<?php

//Creamos una clase Estacion 
class Estacion {

    //para guardar los datos de las estaciones
    protected $concello;
    protected $estacion;
    protected $idEstacion;
    protected $iconoCeo;
    protected $iconoTemperatura;
    protected $iconoVento;
    protected $provincia;
    protected $sensacionTermica = 0.0;
    protected $temperatura = 0.0;

    //Y los métodos get y set correspondientes
    //para poder establecer o acceder a los datos

    public function nuevo_concello($concello) {
        $this->concello = $concello;
    }

    public function get_concello() {
        return $this->concello;
    }

    public function nueva_estacion($estacion) {
        $this->estacion = $estacion;
    }

    public function get_estacion() {
        return $this->estacion;
    }

    public function nuevo_idEstacion($idEstacion) {
        $this->idEstacion = $idEstacion;
    }

    public function get_idEstacion() {
        return $this->idEstacion;
    }

    public function nuevo_iconoCeo($iconoCeo) {
        $this->iconoCeo = $iconoCeo;
    }

    public function get_iconoCeo() {
        return $this->iconoCeo;
    }

    public function nuevo_iconoTemperatura($iconoTemperatura) {
        $this->iconoTemperatura = $iconoTemperatura;
    }

    public function get_iconoTemperatura() {
        return $this->iconoTemperatura;
    }

    public function nuevo_iconoVento($iconoVento) {
        $this->iconoVento = $iconoVento;
    }

    public function get_iconoVento() {
        return $this->iconoVento;
    }

    public function nueva_provincia($provincia) {
        $this->provincia = $provincia;
    }

    public function get_provincia() {
        return $this->provincia;
    }

    public function nueva_sensacionTermica($sensacionTermica) {
        $this->sensacionTermica = $sensacionTermica;
    }

    public function get_sensacionTermica() {
        return $this->sensacionTermica;
    }

    public function nueva_temperatura($temperatura) {
        $this->temperatura = $temperatura;
    }

    public function get_temperatura() {
        return $this->temperatura;
    }

}
