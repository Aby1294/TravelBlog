<?php include("includes/header_front.php") ?>

<?php
    //Instaciamos DB y coneccion
    $baseDatos = new Basemysql();
    $db = $baseDatos->connect();

    //Instanciamos Articulo
    $articulo = new Articulo($db);
    $resultado = $articulo->leer();
    //Mapeamos HTML
?>

    <div class="container-fluid">
        <h1 class="text-center">Artículos</h1>
        
        <div class="row">
        <?php foreach($resultado as $articulo) : ?>
            <div class="col-sm-4">
                <div class="card">
                    <img src="<?php echo RUTA_FRONT; ?>img/articulos/<?php echo $articulo->imagen; ?>" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $articulo->titulo; ?></h5>
                        <p><strong><?php echo formatearFecha($articulo->fecha_creacion); ?></strong></p>
                        <p class="card-text"><?php echo textoCorto($articulo->texto); ?></p>
                        <a href="detalle.php?id=<?php echo $articulo->id; ?>" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>  
            <?php endforeach; ?>       
        </div>  
               
    </div>
<?php include("includes/footer.php") ?>
       