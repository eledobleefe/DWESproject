<?php
//Necesitamos el código de las siguientes páginas
require_once 'recursos/funciones.php';
require_once 'recursos/Temperatura.php';
require_once 'recursos/Usuario.php';

//Recuperamos la sesión
session_start();

//Si en la sesión no hay guardado un nick, nos indica que no se ha comenzado en la página index.php
//Es una forma de evitar que alguien acceda a esta página pegando la url directamente en el navegador
if (!isset($_SESSION['nick'])) {
    //Redireccionamos a la página de inicio
    header("Location: index.php");
}

//Guardamos en estas variables los los campos del usuario (id, nick, pass, rol e idEstacion)
$id = $_SESSION['id'];
$nick = $_SESSION['nick'];
$pass = $_SESSION['pass'];
$rol = $_SESSION['rol'];
$idEstacion = $_SESSION['idEstacion'];


try {
    //A continuación conseguimos la temperatura de la estación seleccionada por el usuario
    $temperatura = conseguirTemperatura($idEstacion);
    //Lo mismo con los iconos de cielo, tendencia en temperatura y viento
    $iconoCeo = conseguirCeo($idEstacion);
    $iconoTemperatura = conseguirTemp($idEstacion);
    $iconoViento = conseguirVento($idEstacion);
    //Y con la información de la sensación térmica, el nombre de la estación y su concello
    $sensacionTermica = conseguirSensacion($idEstacion);
    $estacion = conseguirNombre($idEstacion);
    $concello = conseguirConcello($idEstacion);
} catch (Exception $e) {
    //Si surge algún error lo capturamos y mostramos el mensaje correspondiente
    echo $e->getMessage();
}

//Guardamos los valores de la tabla usuarios para el usuario con dicho nick y pass
$buscarUsuario = Usuario::buscar($nick, $pass);

if (isset($_POST['guardar']) && isset($buscarUsuario)) {
    try {
        //Cuando el usuario pulse el botón de guardar
        //Y no haya pulsado antes el de borrar
        //Estableceremos la conexión con la BBDD
        $conexion = new Conexion();
        //Insertaremos los datos en la tabla temperaturas
        $tempEstacion = new Temperatura(null, $id, $idEstacion, $temperatura);
        $tempEstacion->guardarTemp();
        //Y guardamos la confirmación en la variable mensaje
        $mensaje = "¡Información guardada con éxito!";
    } catch (Exception $e) {
        //Si surge algún error lo capturamos y mostramos el mensaje correspondiente
        echo $e->getMessage();
    }
}

if (isset($_POST['borrar'])) {
    //Cuando el usuario pulse el botón borrar
    //Se elimina el usuario y las temperaturas de dicho usuario
    if (isset($buscarUsuario) && $buscarUsuario) {
        try {
            //Se elimina el usuario si es que este existe y su valor no es false
            $buscarUsuario->eliminar();
            //Guardamos la información guardada del usuario en la tabla temperaturas
            $borrarTemp = Temperatura::buscarTemp($id, $idEstacion);
            //Y si existe información también se elimina
            if ($borrarTemp) {
                $borrarTemp->eliminarTemp();
            }
            //Guardamos el mensaje de éxito en la eliminación de datos
            $mensaje = "Tus datos han sido eliminados con éxito";
            //Y deshabilitamos los botones de guardar y de borrar
            //Además de crear una variable que nos avise de si se han borrado los datos para
            //aunque sea administrador, no darle ya la opción de ver los datos de otros usuarios
            //pues se ha querido ir de la BBDD
            $disabled = "disabled";
            $borrado = true;
        } catch (Exception $e) {
            //Si surge algún error lo capturamos y mostramos el mensaje correspondiente
            echo $e->getMessage();
        }
    }
}


if (isset($_POST['desconectar'])) {
    try {
        //Si el usuario pulsa el botón de desconectar
        //Destruimos los datos de la sesión
        session_destroy();
        //Y lo redireccionamos a la página de inicio
        header("Location:index.php");
    } catch (Exception $ex) {
        //Si surge algún error lo capturamos y mostramos el mensaje correspondiente
        echo $e->getMessage();
    }
}


//En el caso de que el rol sea admin tendrá acceso al listado de usuarios
if ($rol == 'admin') {
    try {
        //Lanzamos un mensaje para informar al usuario/administrador que tiene esta opción
        $mensajeAdmin = "Al ser administrador, también tienes la opción de ver el listado de usuarios";
        //Y guardamos la lista de los usuarios guardados en la tabla
        $listaUsuarios = Usuario::listar();
    } catch (Exception $ex) {
        //Si surge algún error lo capturamos y mostramos el mensaje correspondiente
        echo $e->getMessage();
    }
}

if (isset($_POST['mostrar']) && isset($rol)) {
    try {
        //Si el usuario con rol de administrador pulsa el botón de mostrar (sin haber borrado sus datos)
        //Mostramos la siguiente tabla con los datos
        $tablaUsuarios = "<table><tr class='head'><td>ID</td><td>Nick</td><td>Pass</td><td>Rol</td><td>ID Estacion</td></tr>";
        foreach ($listaUsuarios as $arrayUsuarios) {
            $tablaUsuarios .= "<tr><td>" . $arrayUsuarios['id'] . "</td>"
                    . "<td>" . $arrayUsuarios['nick'] . "</td>"
                    . "<td>" . $arrayUsuarios['pass'] . "</td>"
                    . "<td>" . $arrayUsuarios['rol'] . "</td>"
                    . "<td>" . $arrayUsuarios['idEstacion'] . "</td>";
            $tablaUsuarios .= "</tr>";
        }
    } catch (Exception $ex) {
        //Si surge algún error lo capturamos y mostramos el mensaje correspondiente
        echo $e->getMessage();
    }
} elseif (isset($_POST['ocultar']) && isset($rol)) {
    //Cuando el usuario pulse el botón ocultar, dejaremos de mostrar la tabla
    $tablaUsuarios = "";
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
            <h1><?php print_r($nick) ?></h1>
            <p>Este es el tiempo actual en "<?php echo $estacion . " - " . $concello ?>" </p>
            <hr>
            <table>
                <tr class='head'>
                    <td>Id estación </td>
                    <td>Cielos</td>
                    <td>Vientos</td>
                </tr>
                <tr>
                    <!-- Mostramos el id de la estación -->
                    <td><?php echo $idEstacion ?></td>
                    <td>
                        <!-- Mostramos el icono del estado del cielo -->
                        <?php
//Si el valor es -9999 quiere decir que la información no está disponible
                        if ($iconoCeo == -9999) {
                            echo "<p class='noDisponible'>No disponible</p>";
                        } else {
                            //Si no, empleamos la función correspondiente para obtener la imagen del icono
                            echo (mostrarIcoCeo($iconoCeo));
                        }
                        ?>
                    </td>
                    <td>
                        <!-- Mostramos el icono del estado del viento -->
                        <?php
                        //Si el valor es -9999 quiere decir que la información no está disponible
                        if ($iconoViento == -9999) {
                            echo "<p class='noDisponible'>No disponible</p>";
                        } else {
                            //Si no, empleamos la función correspondiente para obtener la imagen del icono
                            echo (mostrarIcoVento($iconoViento));
                        }
                        ?>
                    </td>                
                </tr>
                <tr class='head'>
                    <td>Temperatura </td>
                    <td>Tendencia</td>
                    <td>Sensación térmica</td>
                </tr>
                <tr>
                    <!-- Mostramos el valor de la temperatura -->
                    <td><?php echo $temperatura ?></td>
                    <td>
                        <!-- Mostramos el icono de la tendencia en temperatura -->
                        <?php
//Si el valor es -9999 quiere decir que la información no está disponible
                        if ($iconoTemperatura == -9999) {
                            echo "<p class='noDisponible'>La información no está disponible</p>";
                        } else {
                            //Si no, empleamos la función correspondiente para obtener la imagen del icono
                            echo (mostrarIcoTemp($iconoTemperatura));
                        }
                        ?>
                    </td>
                    <!-- Mostramos el valor de la sensación térmica -->
                    <td><?php echo $sensacionTermica ?></td>
                </tr>
            </table>
            <br/>
            <p class="centrado">Ahora puedes guardar los datos de la temperatura actual en tu estación 
                haciendo 'click' en el botón 'Guardar', borrar tus datos de la base de datos en 'Borrar' y/o cerrar sesión
                en 'Desconectar'.</p>
            <br/>
            <form class='ultimo' action='datosMeteo.php' method='post'>
                <!-- Creamos los botones guardar y borrar que se deshabilitarán cuando la variable $disabled exista-->
                <input type='submit' name='guardar' value='Guardar' <?php if (isset($disabled)) echo $disabled; ?>/>
                <input type='submit' name='borrar' value ='Borrar'<?php if (isset($disabled)) echo $disabled; ?>/>
                <!-- El botón de desconectar siempre estará presente-->
                <input type='submit' name='desconectar' value ='Desconectar'/>

                <!-- Si el mensaje de guardado con éxito existe lo mostramos -->
                <br/>
                <p class='exito'><?php if (isset($mensaje)) echo $mensaje; ?></p><br/>
                <!-- Y si el usuario no ha borrado sus datos y las variables $mensajeAdmin y $tablaUsuarios,
                que sólo se crean cuando dicho usuario tiene rol de admin, existen, se informará de la opción
                de ver la lista de usuarios registrados con sus respectivos botones de ver y de ocultar información-->
                <?php
                if (!isset($borrado)) {
                    if (isset($mensajeAdmin)) {
                        echo "<p class='centrado'>" . $mensajeAdmin . "</p><br/>";
                        echo"<input type='submit' name='mostrar' value ='Ver lista'/>";
                        echo"<input type='submit' name='ocultar' value ='Ocultar lista'/><br/><br/>";
                    }
                    if (isset($tablaUsuarios)) {
                        print_r($tablaUsuarios);
                    }
                }
                ?>
            </form>
        </div>
    </body>
</html>