<?php

//Necesitamos el código de los siguientes archivos
require_once 'Estacion.php';
require_once 'restCurl.php';

//Función para listar las provincias
function listarEstaciones() {
    //Recuperamos la variable del archivo restCurl.php
    global $todasEstacionesActual;
    //Creamos el array que guarde todas las provincias
    $listaProvincias = array();
    //Creamos los array que guarden los datos correspondientes a cada provincia
    $listaC = array();
    $listaL = array();
    $listaO = array();
    $listaP = array();
    //Guardamos el número de estaciones
    $largo = sizeof($todasEstacionesActual, 0);

    //Recorremos $todasEstacionesActual
    for ($indice = 0; $indice < $largo; $indice++) {
        //Guardamos en la variable correspondiente el valor de provincia, concello, estación y su id de la estación actual
        $provincia = $todasEstacionesActual[$indice]->get_provincia();
        $concello = $todasEstacionesActual[$indice]->get_concello();
        $estacion = $todasEstacionesActual[$indice]->get_estacion();
        $idEstacion = $todasEstacionesActual[$indice]->get_idEstacion();

        //Si la provincia
        switch ($provincia) {
            //Coincide con Coruña
            case 'A Coruña':
                //Guardamos la estación en el array de Coruña
                array_push($listaC, array($concello, $estacion, $idEstacion));
                break;
            //Si coincide con Lugo
            case 'Lugo':
                //Guardamos la estación en el array de Lugo
                array_push($listaL, array($concello, $estacion, $idEstacion));
                break;
            //En el caso de Ourense
            case 'Ourense':
                //En el de Ourense
                array_push($listaO, array($concello, $estacion, $idEstacion));
                break;
            //Y por último si coincide con Pontevedra
            case 'Pontevedra':
                //En el de Pontevedra
                array_push($listaP, array($concello, $estacion, $idEstacion));
                break;
        }
    }

    //Guardamos los cuatro arrays dentro del array $listaProvincias
    array_push($listaProvincias, $listaC);
    array_push($listaProvincias, $listaL);
    array_push($listaProvincias, $listaO);
    array_push($listaProvincias, $listaP);

    //Y devolvemos dicho array 
    return $listaProvincias;
}

//Función para conseguir la temperatura según el id de la estación
function conseguirTemperatura($id_est) {
    //Recuperamos la variable $todasEstacionesActual
    global $todasEstacionesActual;
    //Guardamos su largo en la variable
    $largo = sizeof($todasEstacionesActual, 0);
    //Recorremos el array
    for ($i = 0; $i < $largo; $i++) {
        //Guardamos la estación actual
        $estacion = $todasEstacionesActual[$i];
        //Su id y su temperatura
        $idEstacion = $estacion->get_idEstacion();
        $temperatura = $estacion->get_temperatura();
        //En el caso de que el id de dicha estación coincida con el parámetro
        if ($idEstacion == $id_est) {
            //devolvemos la temperatura de dicha estación
            return $temperatura;
        }
    }
}

//Funciona igual que la función anterior pero
//obtenemos su nombre en vez de su temperatura
function conseguirNombre($id_est) {
    global $todasEstacionesActual;
    $largo = sizeof($todasEstacionesActual, 0);
    for ($i = 0; $i < $largo; $i++) {
        $estacion = $todasEstacionesActual[$i];
        $idEstacion = $estacion->get_idEstacion();
        $nombre = $estacion->get_estacion();
        if ($idEstacion == $id_est) {
            return $nombre;
        }
    }
}

//Funciona igual que la función anterior pero
//obtenemos el valor de concello en vez de su nombre
function conseguirConcello($id_est) {
    global $todasEstacionesActual;
    $largo = sizeof($todasEstacionesActual, 0);
    for ($i = 0; $i < $largo; $i++) {
        $estacion = $todasEstacionesActual[$i];
        $idEstacion = $estacion->get_idEstacion();
        $concello = $estacion->get_concello();
        if ($idEstacion == $id_est) {
            return $concello;
        }
    }
}

//Funciona igual que la función anterior pero
//obtenemos el icono correspondiente al estado del cielo
function conseguirCeo($id_est) {
    global $todasEstacionesActual;
    $largo = sizeof($todasEstacionesActual, 0);
    for ($i = 0; $i < $largo; $i++) {
        $estacion = $todasEstacionesActual[$i];
        $idEstacion = $estacion->get_idEstacion();
        $iconoCeo = $estacion->get_iconoCeo();
        if ($idEstacion == $id_est) {
            return $iconoCeo;
        }
    }
}

//Funciona igual que la función anterior pero
//obtenemos el icono correspondiente a la tendencia en temperatura
function conseguirTemp($id_est) {
    global $todasEstacionesActual;
    $largo = sizeof($todasEstacionesActual, 0);
    for ($i = 0; $i < $largo; $i++) {
        $estacion = $todasEstacionesActual[$i];
        $idEstacion = $estacion->get_idEstacion();
        $iconoTemperatura = $estacion->get_iconoTemperatura();
        if ($idEstacion == $id_est) {
            return $iconoTemperatura;
        }
    }
}

//Funciona igual que la función anterior pero
//obtenemos el icono correspondiente al estado del viento
function conseguirVento($id_est) {
    global $todasEstacionesActual;
    $largo = sizeof($todasEstacionesActual, 0);
    for ($i = 0; $i < $largo; $i++) {
        $estacion = $todasEstacionesActual[$i];
        $idEstacion = $estacion->get_idEstacion();
        $iconoVento = $estacion->get_iconoVento();
        if ($idEstacion == $id_est) {
            return $iconoVento;
        }
    }
}

//Funciona igual que la función anterior pero
//obtenemos el valor de la sensación térmica
function conseguirSensacion($id_est) {
    global $todasEstacionesActual;
    $largo = sizeof($todasEstacionesActual, 0);
    for ($i = 0; $i < $largo; $i++) {
        $estacion = $todasEstacionesActual[$i];
        $idEstacion = $estacion->get_idEstacion();
        $sensacionTermica = $estacion->get_sensacionTermica();
        if ($idEstacion == $id_est) {
            return $sensacionTermica;
        }
    }
}

//Las siguientes tres funciones obtienen la imagen del icono correspondiente
//teniendo una url donde se guarda el icono en la que simplemente tenemos
//que variar el valor del icono correspondiente (ej. 202, 103...)
function mostrarIcoCeo($iconoCeo) {
    $direccion = "<img src='http://www.meteogalicia.es/datosred/infoweb/meteo/imagenes/meteoros/ceo/" . $iconoCeo . ".png'/>";
    return $direccion;
}

function mostrarIcoTemp($iconoTemp) {
    $direccion = "<img src='http://www.meteogalicia.es/datosred/infoweb/meteo/imagenes/termometros/" . $iconoTemp . ".png'/>";
    return $direccion;
}

function mostrarIcoVento($iconoVento) {
    $direccion = "<img src='http://www.meteogalicia.es/datosred/infoweb/meteo/imagenes/meteoros/vento/combo/" . $iconoVento . ".png'/>";
    return $direccion;
}

?>