<?php

class Comentario{
    private $conn;
    private $table = 'comentarios';//nombre de la tabla con la que vamos a conectar

    //Propiedades de la tabla articulos/Campos
    public $id;
    public $comentario;
    public $estado;
    public $fecha_creacion;

    //Constructor de nuestra clase con acceso a la DB
    public function __construct($db)
    {
        $this->conn = $db;
    }
//-----------------------------------------------------------//
    //Obtener los articulos
    public function leer_comentario(){

        //Crear query
        $query = 'SELECT c.id AS id_comentario, c.comentario AS comentario, c.estado AS estado, c.fecha_creacion AS fecha,
        c.usuario_id, u.email AS nombre_usuario, a.titulo AS titulo_articulo  FROM ' . $this->table . 
        ' c LEFT JOIN usuarios u ON u.id = c.usuario_id LEFT JOIN articulos a ON a.id = c.articulo_id';

       //Preparar sentencia
       $stmt = $this->conn->prepare($query);

       //Ejecutar query
       $stmt->execute();
       $comentarios = $stmt->fetchAll(PDO::FETCH_OBJ);
       return $comentarios;

    }
//-----------------------------------------------------------//
    //Obtener los artifulos indivuduales de cada fila
    public function leer_individual_comentario($id){//debe recibur el $id xq trae una sola fila

        //crear el query
        $query = 'SELECT c.id AS id_comentario, c.comentario AS comentario, c.estado AS estado, c.fecha_creacion AS fecha, 
            c.usuario_id, u.email AS nombre_usuario, a.titulo AS titulo_articulo  FROM ' . $this->table . 
            ' c LEFT JOIN usuarios u ON u.id = c.usuario_id LEFT JOIN articulos a ON a.id = c.articulo_id WHERE c.id = ? LIMIT 0,1';

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(1, $id);

        //Ejecutar queery
        $stmt->execute();
        //retorno los articulos
        $comentario = $stmt->fetch(PDO::FETCH_OBJ);//PDO::FETCH_OBJ es el tipo de retornom, en este caso un objeto
        return $comentario;

    }
//-----------------------------------------------------------//
    //actualizr los articulos
    public function actualizar_comentario($idComentario, $estado){
         
        //Crear query
         $query = 'UPDATE ' . $this->table . ' SET estado = :estado WHERE id = :id';

         //Preparar sentencia
         $stmt = $this->conn->prepare($query);

         //Vincular parámetro
         $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);              
         $stmt->bindParam(":id", $idComentario, PDO::PARAM_INT);

         //Ejecutar query
         if ($stmt->execute()) {
             return true;

         }
 
         //Si hay error 
         printf("error $s\n", $stmt->error);

    }
//-----------------------------------------------------------//
    //borrar los articulos
    public function borrar_comentario($idComentario){//debe recibur el $id xq trae una sola fila

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id ';
    
        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);
    
        //Vincular parametro
        $stmt->bindParam(':id', $idComentario, PDO::PARAM_INT);
    
        //Ejecutar queery
        if ($stmt->execute()==true) {
            return true;
        }

        //SI hay error
        printf("error $s\n", $stmt->error);
    }  
//-----------------------------------------------------------//
    //Obtener comentarios por id de articulo   
    public function leerPorId_enDetalle($idArticulo){
        $query = 'SELECT c.id AS id_comentario, c.comentario AS comentario, c.estado AS estado, 
        c.fecha_creacion AS fecha, c.usuario_id, u.email AS nombre_usuario FROM ' .$this->table . 
        ' c INNER JOIN usuarios u ON u.id = c.usuario_id WHERE articulo_id = :articulo_id AND estado = 1';

        //Preparar sentencia
        $stmt = $this->conn->prepare($query);

        //VIncular parametro
        $stmt->bindParam(':articulo_id', $idArticulo, PDO::PARAM_INT );

        //Ejecutar query
        $stmt->execute();
        $comentarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $comentarios;
    }

//-----------------------------------------------------------//
    //Obtener comentarios por id de articulo 
    public function crear_comentario($email, $comentario, $idArticulo){
        //Obtener el id del usuario usando el email
        $query = 'SELECT * FROM usuarios WHERE email = :email';

        //Preparar sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(':email', $email);

        $stmt->execute();
        $usuario= $stmt->fetch(PDO::FETCH_OBJ);
        $idUsuario = $usuario->id;

        //Crear el query para la insercion del comentario
        $query2 = 'INSERT INTO ' . $this->table . ' (comentario, usuario_id, articulo_id, estado) 
        VALUES (:comentario, :usuario_id, :articulo_id, 0)';

        //Preparar sentencia
        $stmt = $this->conn->prepare($query2);

        //Vincular parametro
        $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':articulo_id', $idArticulo, PDO::PARAM_INT);  

        //Ejecturar query
        if ($stmt->execute()) {
            return true;
        }

        //SI ahay error
        printf("error $s\n", $stmt->error);
        return false;
    }  

    

    
    }








?>