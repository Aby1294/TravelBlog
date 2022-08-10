<?php

class Articulo{
    private $conn;
    private $table = 'articulos';//nombre de la tabla con la que vamos a conectar

    //Propiedades de la tabla articulos
    public $id;
    public $titulo;
    public $imagen;
    public $texto;
    public $fecha_creacion;

    //Constructor de nuestra clase
    public function __construct($db)
    {
        $this->conn = $db;
    }
//-----------------------------------------------------------//
    //Obtener los articulos
    public function leer(){

        //crear el query
        $query = 'SELECT id, titulo, imagen, texto, fecha_creacion FROM ' . $this->table;

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Ejecutar queery
        $stmt->execute();
        //retorno los articulos
        $articulos = $stmt->fetchAll(PDO::FETCH_OBJ);//PDO::FETCH_OBJ es el tipo de retornom, en este caso un objeto
        return $articulos;

    }
//-----------------------------------------------------------//
    //Obtener los artifulos indivuduales de cada fila
    public function leer_individual($id){//debe recibur el $id xq trae una sola fila

        //crear el query
        $query = 'SELECT id, titulo, imagen, texto, fecha_creacion FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(1, $id);

        //Ejecutar queery
        $stmt->execute();
        //retorno los articulos
        $articulos = $stmt->fetch(PDO::FETCH_OBJ);//PDO::FETCH_OBJ es el tipo de retornom, en este caso un objeto
        return $articulos;

    }
//-----------------------------------------------------------//
    //Obtener los artifulos indivuduales de cada fila
    public function crear_articulo($titulo, $newImageName, $texto){//debe recibur el $id xq trae una sola fila

        //crear el query
        $query = 'INSERT INTO ' . $this->table . ' (titulo, imagen, texto) VALUES (:titulo, :imagen, :texto)';

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $newImageName, PDO::PARAM_STR);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);

        //Ejecutar queery
        if ($stmt->execute()==true) {
            return true;
        }

        //SI hay error
        printf("error $s\n", $stmt->error);
    }
//-----------------------------------------------------------//
    //actualizr los articulos
    public function actualizar_articulo($id, $titulo, $texto, $newImageName){//debe recibur el $id xq trae una sola fila

        //Si la imagen esta vacia
        if ($newImageName == "") {
            //crear el query
        $query = 'UPDATE ' . $this->table . ' SET titulo = :titulo, texto = :texto WHERE id = :id ';
    
        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);
    
        //Vincular parametro
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        //Ejecutar queery
        if ($stmt->execute()==true) {
            return true;
        }
        }else {
             //crear el query
        $query = 'UPDATE ' . $this->table . ' SET titulo = :titulo, texto = :texto, imagen = :imagen WHERE id = :id ';
    
        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);
    
        //Vincular parametro
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $newImageName, PDO::PARAM_STR);//es un PARAM_STR xq guardamos la ruta de la imagen y no la imagen en si
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        //Ejecutar queery
        if ($stmt->execute()==true) {
            return true;
        }

        
    
        //SI hay error
        printf("error $s\n", $stmt->error);
            }       

    
}
//-----------------------------------------------------------//
    //borrar los articulos
    public function borrar_articulo($id){//debe recibur el $id xq trae una sola fila

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id ';
    
        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);
    
        //Vincular parametro
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        //Ejecutar queery
        if ($stmt->execute()==true) {
            return true;
        }

        //SI hay error
        printf("error $s\n", $stmt->error);
            }       

    
}








?>