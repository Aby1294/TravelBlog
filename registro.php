<?php include("includes/header_front.php") ?>

<?php
//instanciar db y conexcion
$baseDatos = new Basemysql();
$db = $baseDatos->connect();

//Instaciamos el registro
$registro = new Usuario($db);

if (isset($_POST['registrarse'])) {
    //Obtengo los valores
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmarPassword = $_POST['confirmar_password'];

    //validar q los campos no esten vacios
    if (empty($nombre) || $nombre=='' || empty($email) || $email=='' || empty($password) || $password=='' 
    || empty($confirmarPassword) || $confirmarPassword=='') {
        $error = "Error, algunos campos obligatorios estan vacios";
    }else {
        //Validamos q los passwords coincidan
        if ($password != $confirmarPassword) {
            $error = "Error, las password no coinciden";
        }else {
        //Instaciamos el registro
        //$registro = new Usuario($db);

        if ($registro->validar_mail_registrar($email)) {
            //Crear el usuario en el rol registrado
            if ($registro->registrar($nombre, $email, $password)) {
                $mensaje = "Usuario registrado exitosamente, click en el botón acceder para ingresar";
             }else{
                $error = "Error, no se pudo registrar el usuario";
            }
        }else {
            $error = "Error, este email ya se encuentra registrado";
        }

        }
        
        
    }

}


?>

<div class="row">
    <div class="col-sm-12">
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $error; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
        <div class="col-sm-12">
            <?php if(isset($mensaje)) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo $mensaje; ?></strong> 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            <?php endif; ?>
        </div>  
    </div>

    <div class="container-fluid">
        <h1 class="text-center">Registro de Usuarios</h1>
        <div class="row">
            <div class="col-sm-6 offset-3">
                <div class="card">
                   <div class="card-header">
                        Regístrate para poder comentar
                   </div>
                    <div class="card-body">
                    <form method="POST" action="">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre">               
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Ingresa el email">               
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" placeholder="Ingresa el password">               
                    </div>

                    <div class="mb-3">
                        <label for="confirmarPassword" class="form-label">Confirmar password:</label>
                        <input type="password" class="form-control" name="confirmar_password" placeholder="Ingresa la confirmación del password">            
                    </div>                    

                    <br />
                    <button type="submit" name="registrarse" class="btn btn-primary w-100"><i class="bi bi-person-bounding-box"></i> Registrarse</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>  
         
    </div>
<?php include("includes/footer.php") ?>
       