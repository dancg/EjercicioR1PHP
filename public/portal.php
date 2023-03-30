<?php
session_start();
require_once __DIR__ . "./../vendor/autoload.php";
if (!isset($_SESSION['usuario'])) {
    header('Location:index.php');
    die();
}

use Src\Usuario;

$usuarios = Usuario::read();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if (Usuario::devolverEmail($id) == $_SESSION['usuario']) {
        $_SESSION['error'] = "No puedes editarte a ti mismo!!!";
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }
    foreach ($usuarios as $item) {
        if ($item->id == $id) {
            if ($item->perfil == "Usuario") {
                (new Usuario)->setPerfil("Administrador")
                    ->update($id);
            } else {
                (new Usuario)->setPerfil("Usuario")
                    ->update($id);
            }
        }
    }
    header("Location:{$_SERVER['PHP_SELF']}");
    die();
}
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
    <!-- CDN SeetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Portal</title>
</head>

<body style="background-color:bisque">
    <div class="d-flex flex-row-reverse my-2 mx-4">
        <div>
            <a href="cerrar.php" class="btn btn-danger ">
                <i class="fa-solid fa-right-from-bracket"></i> SALIR
            </a>
        </div>
        <div>
            <input type="text" readonly value="<?php echo $_SESSION['usuario'] ?>" class="bg-info form-control" />
        </div>
    </div>
    <div class="container">
        <h5 class=" text-center my-2">LISTADO DE USUARIOS REGISTRADOS</h5>
        <table class="table table-dark">
            <thead>
                <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">PERFIL</th>
                    <th scope="col">CIUDAD</th>
                    <?php
                    if (Usuario::permisoUsuarios($_SESSION['usuario']))
                        echo "<th scope='col'>ACCIONES</th>";
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $item) {
                    $cadena = ($item->email == $_SESSION['usuario']) ? "*" : "";
                    $cadena1 = ($item->perfil == "Administrador") ? "text-danger" : "text-success";
                    $cadena2 = ($item->email == $_SESSION['usuario']) ? "disabled" : "";
                    echo <<<TXT
                            <tr class="text-center">
                            <td>{$item->id}</td>
                            <td>{$item->email}$cadena</td>
                            <td class='$cadena1'>{$item->perfil}</td>
                            <td>{$item->ciudad}</td>
                            TXT;
                    if (Usuario::permisoUsuarios($_SESSION['usuario'])) {
                        echo <<<TXT
                            <td>
                                <form action='portal.php' method='POST'>
                                <input type='hidden' value='{$item->id}' name='id'>
                                <button class='btn btn-warning' type='submit' name='editar'$cadena2>
                                    <i class='fas fa-edit'></i>
                                </button>
                                </form>
                            </td>
                            </tr>
                        TXT;
                    }
                }
                ?>
            </tbody>
        </table>
        <?php if (isset($_SESSION['error'])) {
            echo "<p class='text-danger text-center italics bg-info'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        } ?>
    </div>

</body>

</html>