<?php include("../includes/header.php") ?>
<?php
//Instanciar base de datos y conexion
$baseDatos = new Basemysql();
//llamo al metodo connect
$db = $baseDatos->connect();

//validar si se preciono el boton crear articulo
if (isset($_POST['crearArticulo'])) {
    //Obtener los valores
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    //$newImageName = $_POST['imagen'];

    if ($_FILES['imagen']['error'] > 0) { //esto significa si la imagen tiene un error >0 entonces tuvo un fallo al intentar subor
        $error = "Error, ningun archivo seleccionado";
    } else {
        //Si entra es porque si se subio la imagen
        //Validar los demas campos no esten vacios
        if (empty($titulo) || $titulo == '' || empty($texto) || $texto == '') {
            $error = "Error, algunos campos estan vacios";
        } else {
            //SI entra es porque se enviaron los datos
            //SUbida de archivos
            $image = $_FILES['imagen']['name'];
            $imageArr = explode('.', $image);
            $rand = rand(1000, 99999); //para q se cree un nombre de imagen diferente para cada imagen q se suba
            $newImageName = $imageArr[0] . $rand . '.' . $imageArr[1];
            $rutaFinal = "../img/articulos/" . $newImageName;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal);


            //Instanciamos el articulo
            $articulos = new Articulo($db);

            if ($articulos->crear_articulo($titulo, $newImageName, $texto)) {
                $mensaje = "Articulo creado correctamente";
                header("Location:articulos.php?mensaje=" . urldecode($mensaje));
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
    <div class="col-sm-6">
        <h3>Crear un Nuevo Art??culo</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 offset-3">
        <form method="POST" action="" enctype="multipart/form-data">
            <!-- enctype="multipart/form-data" me permite subir archivos -->
            <div class="mb-3">
                <label for="titulo" class="form-label">T??tulo:</label>
                <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingresa el t??tulo">
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="apellidos" placeholder="Selecciona una imagen">
            </div>
            <div class="mb-3">
                <label for="texto">Texto</label>
                <textarea class="form-control" placeholder="Escriba el texto de su art??culo" name="texto" style="height: 200px"></textarea>
            </div>

            <br />
            <button type="submit" name="crearArticulo" class="btn btn-primary w-100"><i class="bi bi-person-bounding-box"></i> Crear Nuevo Art??culo</button>
        </form>
    </div>
</div>
<?php include("../includes/footer.php") ?>