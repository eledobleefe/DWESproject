<?php
//Para trabajar necesitamos el código de las siguientes páginas
require_once 'recursos/Conexion.php';
require_once 'recursos/Usuario.php';
require_once 'recursos/restCurl.php';
require_once 'recursos/funciones.php';


//Recuperamos la sesión
session_start();

//Si en la sesión no hay guardado un nick, nos indica que no se ha comenzado en la página index.php
//Es una forma de evitar que alguien acceda a esta página pegando la url directamente en el navegador
if (!isset($_SESSION['nick'])) {
    //Le mandamos de nuevo a la página de inicio
    header("Location: index.php");
}

//Guardamos la lista de estaciones en esta variable 
$listaEstaciones = listarEstaciones();

// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {
    //En caso afirmativo, guardamos en las variables correspondientes los valores guardados en la sesión
    $nick = $_SESSION['nick'];
    $pass = $_SESSION['pass'];
    //Y obtenemos el valor de rol y de idEstación del formulario
    //Que también guardaremos en la sesión
    $rol = $_POST['rol'];
    $_SESSION['rol'] = $rol;
    $idEstacion = $_POST['idEstacion'];
    $_SESSION['idEstacion'] = $idEstacion;


    try {
        // Nos conectamos a la base de datos
        $conexion = new Conexion();
        //Insertamos el nuevo usuario en la tabla usuarios
        $usuario = new Usuario(null, $nick, $pass, $rol, $idEstacion);
        $usuario->guardarDatos();
        //Obtenemos su id de la tabla
        $id = $usuario->get_id();
        //Lo guardamos en la sesión
        $_SESSION['id'] = $id;
        //Y redireccionamos a la página donde mostramos los datos meteorológicos
        header("Location:datosMeteo.php");
    } catch (Exception $e) {
        //Si hay algún error lo capturamos y mostramos el mensaje correspondiente
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>DWES - Trabajo final</title>
        <link href="recursos/estilos.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lobster|Montserrat" rel="stylesheet">
    </head>

    <body>
        <div class='contenedorBase'>
            <h1>Bienvenid@ <?php echo $_SESSION['nick'] ?> </h1>
            <p class='textoParrafo'>Además de su usuario y contraseña, necesitamos su rol
                y su estación meteorológica para poder registrarlo.</p>
            <hr>
            <form action='registrados.php' method='post'>
                <fieldset>
                    <div class='contenedor'>
                        <label for='rol' >Selecciona tu rol</label><br/>
                        <select name='rol' size='1'>
                            <option value='user'>Usuario</option>
                            <option value='admin'>Administrador</option>
                            <option value='guest'>Invitado</option>
                        </select>
                        <br/>            
                        <label for='idEstacion' >Elige tu estación</label><br/>
                        <select name='idEstacion' size='1'>
                            <!-- Recorremos el array $listaEstaciones para mostrar sus valores -->
<?php
//Iniciamos el contador a cero
$contador = 0;
foreach ($listaEstaciones as $array) {
    //Si la posición del array es
    switch ($contador) {
        // Cero, corresponde a la provincia de A Coruña,
        // con lo que añadimos una option deshabilitada
        // para usar como separador cuyo valor será sólo A Coruña
        case 0:
            echo"<option value='-1' disabled>A CORUÑA</option><br/>";
            break;
        // Uno, corresponde con Lugo e insertamos el separador correspondiente
        case 1:
            echo"<option value='-1' disabled>LUGO</option><br/>";
            break;
        // Dos, corresponde con Ourense e insertamos el separador correspondiente
        case 2:
            echo"<option value='-1' disabled>OURENSE</option><br/>";
            break;
        // Tres, corresponde con Pontevedra e insertamos el separador correspondiente
        case 3:
            echo"<option value='-1' disabled>PONTEVEDRA</option><br/>";
            break;
    }
    //Una vez puesto el separador de provincia
    //Recorremos el array de la provincia y por cada estación
    foreach ($array as $value) {
        //Creamos su opción correspondiente
        echo "<option value='" . $value[2] . "'>" . $value[0] . " - \tEstación de " . $value[1] . "</option><br/>";
    }
    //Incrementamos el valor de $contador
    $contador++;
}
?>
                            <input type='submit' name='enviar' value='Enviar' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
