<?php

session_start();
require_once __DIR__ . "./../vendor/autoload.php";

use Src\Usuario;

$ciudades = Usuario::devolverCiudades();
if (isset($_POST['registro'])) {
    //Procesamos el formulario
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    $ciudad = $_POST['ciudad'];
    //Validamos el email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores = true;
        $_SESSION['erroremail'] = '***El email introducido no es válido***';
    }
    //Hay que comprobar si el email ya existe
    else {
        if (Usuario::existeEmail($email)) {
            $errores = true;
            $_SESSION['erroremail'] = '***El email introducido ya existe***';
        }
    }
    //Validamos la contraseña
    if (strlen($pass) < 5) {
        $errores = true;
        $_SESSION['errorpass'] = '***La contraseña debe tener como mínimo 5 caracteres***';
    }
    //Validamos la ciudad
    if (!in_array($ciudad, $ciudades)) {
        $errores = true;
        $_SESSION['errorciudad'] = '***No has seleccionado una ciudad***';
    }
    //Comprobamos si hay errores
    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }
    //Si llegamos aqui no hay errores, entonces guardamos el usuario, pero hay que asignarle
    //por defecto el perfil de usuario
    (new Usuario)->setEmail($email)
    ->setPerfil("Usuario")
    ->setCiudad($ciudad)
    ->setPass($pass)
    ->create();

    //Guardamos el usuario en una variable de sesión
    $_SESSION['usuario'] = $email;
    header("Location:portal.php");
} else {
    //Pintamos el formulario

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CDN Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- CDN FONTAWESOME -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Registro</title>
    </head>

    <body style="background-color:bisque">
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <form name="registro" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

                                        <h2 class="fw-bold mb-2 text-uppercase">Registro</h2>
                                        <p class="text-white-50 mb-5">Please enter your data!</p>

                                        <div class="form-outline form-white mb-4">
                                            <?php
                                            if (isset($_SESSION['erroremail'])) {
                                                echo "<p class='text-danger italic'>{$_SESSION['erroremail']}</p>";
                                                unset($_SESSION['erroremail']);
                                            }
                                            ?>
                                            <input type="text" id="typeEmailX" class="form-control form-control-lg" name="email" />
                                            <label class="form-label" for="typeEmailX">Email</label>
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <?php
                                            if (isset($_SESSION['errorpass'])) {
                                                echo "<p class='text-danger italic'>{$_SESSION['errorpass']}</p>";
                                                unset($_SESSION['errorpass']);
                                            }
                                            ?>
                                            <input type="password" id="typePasswordX" class="form-control form-control-lg" name="pass" />
                                            <label class="form-label" for="typePasswordX">Password</label>
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <?php
                                            if (isset($_SESSION['errorciudad'])) {
                                                echo "<p class='text-danger italic'>{$_SESSION['errorciudad']}</p>";
                                                unset($_SESSION['errorciudad']);
                                            }
                                            ?>
                                            <select name="ciudad" id="ciudad" class="form-control form-control-lg">
                                                <option value="-1">________Seleciona una ciudad_________</option>
                                                <?php
                                                foreach ($ciudades as $item) {
                                                    echo "<option>{$item}</option>";
                                                }
                                                ?>
                                            </select>
                                            <label class="form-label" for="ciudad">Ciudad</label>
                                        </div>

                                        <button class="btn btn-outline-light btn-lg px-5" type="submit" name="registro">Registrar</button>

                                    </form>
                                </div>

                                <div>
                                    <p class="mb-0">¿Ya tienes cuenta? <a href="index.php" class="text-white-50 fw-bold">Loguéate</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>

    </html>
<?php } ?>