<?php include("../includes/header.php") ?>
<?php
//Instanciamos la base de datos y conexcion
$baseDatos = new Basemysql();
//llamo al metodo connect
$db = $baseDatos->connect();

//Validar si se envio el id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Instanciamos el objeto
$articulos = new Articulo($db);
//llamamos al metodo leer_indivudual que trae los registros de la tabla
$resultado = $articulos->leer_individual($id);
//luego mapeamos la tabla abajo en el codigo HTML

//-----------------------------------------------------------//

//Actualizamos articulo
if (isset($_POST['editarArticulo'])) {
    //Obtener los valores
    $idArticulo = $_POST['id'];
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    //$newImageName = $_POST['imagen']; ESTO NO VA!

    if ($_FILES['imagen']['error']>0) {//esto significa si la imagen tiene un error >0 entonces tuvo un fallo al intentar subor
        //No se sube imagen pero deja actualizar los demas campos
        if (empty($titulo) || $titulo == '' || empty($texto) || $texto=='') {
            $error = "Error, algunos campos estan vacios";
        }else {
            
            //Instanciamos el articulo
            $articulo = new Articulo($db);

            $newImageName = "";//esta vacia xq en este caso no se esta enviando la imagen

            if ($articulo->actualizar_articulo($idArticulo, $titulo, $texto, $newImageName)) {
                $mensaje = "Articulo actualizado correctamente";
                header("Location:articulos.php?mensaje=" . urldecode($mensaje));
                exit();
                
            }else {
                $error = "Error, No se pudo actualizar";
            }
        }
    }else {
        //Si entra es porque si se subio la imagen
        //Validar los demas campos no esten vacios
        if (empty($titulo) || $titulo == '' || empty($texto) || $texto=='') {
            $error = "Error, algunos campos estan vacios";
        }else {
            //SI entra es porque se enviaron los datos
            //SUbida de archivos, es este caso la imagen no esta vacia, si se actualizo
            $image = $_FILES['imagen']['name'];
            $imageArr = explode('.', $image);
            $rand = rand(1000, 99999);//para q se cree un nombre de imagen diferente para cada imagen q se suba
            $newImageName = $imageArr[0] . $rand . '.' . $imageArr[1];
            $rutaFinal = "../img/articulos/" . $newImageName;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal);


            //Instanciamos el articulo
            $articulo = new Articulo($db);

            if ($articulo->actualizar_articulo($idArticulo, $titulo, $texto, $newImageName)) {
                $mensaje = "Articulo actualizado correctamente";
                header("Location:articulos.php?mensaje=" . urldecode($mensaje));
            }else {
                $error = "Error, No se pudo actualizar";
            }
        }
    }
}

//-----------------------------------------------------------//

//Borrar articulo
if (isset($_POST['borrarArticulo'])) {
    //Obtener el id del articulo
    $idArticulo = $_POST['id'];
          
            //Instanciamos el articulo
            $articulo = new Articulo($db);

            if ($articulo->borrar_articulo($idArticulo)) {
                $mensaje = "Articulo borrado correctamente";
                header("Location:articulos.php?mensaje=" . urldecode($mensaje));
                exit();
                
            }else {
                $error = "Error, No se pudo borrar";
            }
}
?>

<div class="row">
    <div class="col-sm-12">
        <?php if(isset($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $error; ?></strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        <?php endif; ?>
    </div>  
</div>

<div class="row">
        <div class="col-sm-6">
            <h3>Editar Artículo</h3>
        </div>            
    </div>
    <div class="row">
        <div class="col-sm-6 offset-3">
        <form method="POST" action="" enctype="multipart/form-data"><!-- enctype="multipart/form-data" esto me permitira subir archivos-->

            <input type="hidden" name="id" value="<?php echo $resultado->id;  ?>"><!--Campo de tipo oculto (type="hidden") que me permite acceder al id para editar el articulo  -->

            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $resultado->titulo  ?>">               
            </div>

            <div class="mb-3">
                <img class="img-fluid img-thumbnail" src="<?php echo RUTA_FRONT . "img/articulos/" . $resultado->imagen; ?>">
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="imagen" placeholder="Selecciona una imagen">               
            </div>
            <div class="mb-3">
                <label for="texto">Texto</label>   
                <textarea class="form-control" placeholder="Escriba el texto de su artículo" name="texto" style="height: 200px">
                <?php echo $resultado->texto  ?>
                </textarea>              
            </div>          
        
            <br />
            <button type="submit" name="editarArticulo" class="btn btn-success float-left"><i class="bi bi-person-bounding-box"></i> Editar Artículo</button>

            <button type="submit" name="borrarArticulo" class="btn btn-danger float-right"><i class="bi bi-person-bounding-box"></i> Borrar Artículo</button>
            </form>
        </div>
    </div>
<?php include("../includes/footer.php") ?>