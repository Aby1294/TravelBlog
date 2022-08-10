<?php include("includes/header_front.php") ?>

<?php 

//instanciamos db y conexcion
$baseDatos = new Basemysql();
$db = $baseDatos->connect();

//Verificamos si se envio por GET el id del index.php cuando le doy click a 'Ver mas'
if (isset($_GET['id'])) {
    $idArticulo = $_GET['id'];
}

//Instanciar articulo
$articulo = new Articulo($db);
$resultado = $articulo->leer_individual($idArticulo);
//Mapeamos el HTML

//Instaciar los comentarios para este articulo
$comentarios = new Comentario($db);
$resultado2 = $comentarios->leerPorId_enDetalle($idArticulo);

//Crear comentario
if (isset($_POST['enviarComentario'])) {
    //obtener los valores
    $idArticulo = $_POST['articulo'];
    $email = $_POST['usuario'];
    $comentario = $_POST['comentario'];

    //Validamos q los camps no esten vacios
    if (empty('email') || $email == '' || empty('comentario') || $comentario == '') {
        $error = "Error, algunos campso estan vacios";
    }else {
        //instacio el comentario
        $comentarioOBJ = new Comentario($db);

        if ($comentarioOBJ->crear_comentario($email, $comentario, $idArticulo)) {
            $mensaje = "Comentario creado correctamente";
            //Redirecciono al index.php
            echo("<script>location.href = '".RUTA_FRONT."' </script>");
        }else {
            $error = "Error, no se pudo borrar";
        }
    }
}
?>

    <div class="row">
       
    </div>

    <div class="container-fluid"> 
      
        <div class="row">
                
        <div class="row">
        <div class="col-sm-12">
            
        </div>  
    </div>

            <div class="col-sm-12">
                <div class="card">
                   <div class="card-header">
                        <h1><?php echo $resultado->titulo;  ?></h1>
                   </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid img-thumbnail" src="<?php echo RUTA_FRONT; ?>img/articulos/<?php echo $resultado->imagen; ?>">
                        </div>

                        <p><?php echo $resultado->texto;  ?></p>

                    </div>
                </div>
            </div>
        </div>  
  
    <?php if (isset($_SESSION['autenticado'])) : //$_SESSION['autenticado'] lo sacamos de acceder.php, ya que corrobora si accediste?>    
        <div class="row">        
        <div class="col-sm-6 offset-3">
        <form method="POST" action="">
            <input type="hidden" name="articulo" value="<?php echo $idArticulo; ?>">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" name="usuario" id="usuario" value="<?php echo $_SESSION['email']; ?>" readonly>               
            </div>
           
            <div class="mb-3">
                <label for="comentario">Comentario</label>   
                <textarea class="form-control" name="comentario" style="height: 200px"></textarea>              
            </div>          
        
            <br />
            <button type="submit" name="enviarComentario" class="btn btn-primary w-100"><i class="bi bi-person-bounding-box"></i> Crear Nuevo Comentario</button>
            </form>
        </div>
        </div>
   <?php endif; ?>
    </div>

    <div class="row">
    <h3 class="text-center mt-5">Comentarios</h3>
      <?php foreach($resultado2 as $comentario) : ?>
            <h4><i class="bi bi-person-circle"></i> <?php echo $comentario->nombre_usuario; ?></h4><!-- usuario q comenta -->
            <p><?php echo $comentario->comentario; ?></p><!-- comentario del usuario -->
        <?php endforeach; ?>
    </div>
         
    </div>
<?php include("includes/footer.php") ?>
       