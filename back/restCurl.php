<?php

//Necesitamos el código de 'Estacion.php'
require_once 'Estacion.php';


//Según la información que hemos encontrado, los servicios web de meteogalicia son REST
//Info en:
// https://www.programmableweb.com/api/meteogalica-meteosix
//http://www.meteogalicia.gal/datosred/infoweb/meteo/proxectos/meteosix/API_MeteoSIX_en.pdf

//Guardamos en la variable la dirección de meteogalicia de donde obtendremos los datos meteorológicos
$url = 'http://servizos.meteogalicia.gal/rss/observacion/estadoEstacionsMeteo.action?';

// Iniciamos cURL 
$ch = curl_init();

//Ahora, con curl_setopt configuramos las ociónes para la transferencia cURL
// Deshabilitamos la verificación SSL
// Le decimos que no verifique certificados porque no siempre son correctos y si dan error no funcionaría el programa
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//Establecemos la forma de retorno
//Al ponerla en true lo que hacemos es que después la podamos guardar en una variable al ejecutar curl
//Si ponemos false se imprimiría en pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//Aplicamos la url de la que sacaremos la información
curl_setopt($ch, CURLOPT_URL, $url);

// Ejecutamos la conexión con los parámetros establecidos y guardamos el resultado en la variable
$result = curl_exec($ch);
// Una vez conseguida y guardada la información cerramos la conexión
curl_close($ch);


//Guardamos toda la información del JSON en esta variable
$listEstadoActual = json_decode($result, true);

//Inicializamos la variable
$todasEstacionesActual = array();

//Comprobamos que $listEstadoActual esté ok, si no, según experiencia propia, quiere decir que no hay acceso a internet
if ($listEstadoActual) {
    try {
        //Recorremos el array $listEstadoActual
        foreach ($listEstadoActual as $array1) {
            //Entramos en el array que contiene $listEstadoActual
            foreach ($array1 as $array2) {
                //Recorremos este segundo array que contiene la información de todas las estaciones
                //Creamos una nueva estación
                $nueva_estacion = new Estacion();
                //Entramos en el array que contiene los datos de la estación actual
                foreach ($array2 as $key => $value) {
                    //Y dependiendo de si la clave coincide con concello, estacion, idEstacion, etc.
                    //utilizaremos su método set correspondiente para establecer su valor
                    switch ($key) {
                        case 'concello':
                            $nueva_estacion->nuevo_concello($value);
                            break;
                        case 'estacion':
                            $nueva_estacion->nueva_estacion($value);
                            break;
                        case 'idEstacion':
                            $nueva_estacion->nuevo_idEstacion($value);
                            break;
                        case 'lnIconoCeo':
                            $nueva_estacion->nuevo_iconoCeo($value);
                            break;
                        case 'lnIconoTemperatura':
                            $nueva_estacion->nuevo_iconoTemperatura($value);
                            break;
                        case 'lnIconoVento':
                            $nueva_estacion->nuevo_iconoVento($value);
                            break;
                        case 'provincia':
                            $nueva_estacion->nueva_provincia($value);
                            break;
                        case 'valorSensTermica':
                            $nueva_estacion->nueva_sensacionTermica($value);
                            break;
                        case 'valorTemperatura':
                            $nueva_estacion->nueva_temperatura($value);
                            break;
                    }
                }
                //Antes de pasar a la siguiente estación guardamos la actual en la variable $todasEstacionesActual
                array_push($todasEstacionesActual, $nueva_estacion);
            }
        }
    } catch (Exception $e) {
        //Si hay algún error lo capturamos y lanzamos el mensaje correspondiente
        echo $e->getMessage();
    }
} else {
    //Lanzamos el mensaje formateado para avisar de la posible falta de conexión
    echo "<p class='centrado noDisponible'>Ha habido algún error en la obtención de la información base. Revisa tu conexión.</p>";
}

