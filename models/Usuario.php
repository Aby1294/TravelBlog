<?php

class Usuario{
    private $conn;
    private $table = 'usuarios';//nombre de la tabla con la que vamos a conectar

    //Propiedades de la tabla articulos
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $fecha_creacion;

    //Constructor de nuestra clase
    public function __construct($db)
    {
        $this->conn = $db;
    }
//-----------------------------------------------------------//
    //Obtener los articulos
    public function leer_usuarios(){

        //crear el query
        $query = 'SELECT u.id AS usuario_id, u.nombre AS usuario_nombre, u.email AS usuario_email, u.fecha_creacion AS usuario_fecha_creacion,
         r.nombre AS rol FROM ' . $this->table . ' u INNER JOIN roles r ON r.id = u.rol_id';

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Ejecutar queery  
        $stmt->execute();
        //retorno los articulos
        $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);//PDO::FETCH_OBJ es el tipo de retornom, en este caso un objeto
        return $usuarios;

    }
//-----------------------------------------------------------//
    //Obtener los artifulos indivuduales de cada fila
    public function leer_individual_usuario($id){//debe recibur el $id xq trae una sola fila

        //crear el query
        $query = 'SELECT u.id AS usuario_id, u.nombre AS usuario_nombre, u.email AS usuario_email, u.fecha_creacion 
        AS usuario_fecha_creacion, r.nombre AS rol  FROM ' . $this->table . 
        ' u INNER JOIN roles r ON r.id = u.rol_id WHERE u.id = ? LIMIT 0,1';

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(1, $id);

        //Ejecutar queery
        $stmt->execute();
        //retorno los articulos
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);//PDO::FETCH_OBJ es el tipo de retornom, en este caso un objeto
        return $usuario;

    }
//-----------------------------------------------------------//
    //actualizr los articulos
    public function actualizar_usuario($idUsuario, $rol){
         
        //Crear query
         $query = 'UPDATE ' . $this->table . ' SET rol_id = :rol_id WHERE id = :id';

         //Preparar sentencia
         $stmt = $this->conn->prepare($query);

         //Vincular parámetro
         $stmt->bindParam(":rol_id", $rol, PDO::PARAM_INT);              
         $stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);

         //Ejecutar query
         if ($stmt->execute()) {
             return true;

         }
 
         //Si hay error 
         printf("error $s\n", $stmt->error);

    }
//-----------------------------------------------------------//
    //borrar los articulos
    public function borrar_usuario($idUsuario){//debe recibur el $id xq trae una sola fila

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id ';
    
        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);
    
        //Vincular parametro
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
    
        //Ejecutar queery
        if ($stmt->execute()==true) {
            return true;
        }

        //SI hay error
        printf("error $s\n", $stmt->error);
    }  
//-----------------------------------------------------------//
    //borrar los articulos
    public function registrar($nombre, $email, $password){//debe recibur el $id xq trae una sola fila

        $query = 'INSERT INTO ' . $this->table . ' (nombre, email, password, rol_id) 
        VALUES (:nombre, :email, :password, 2)';//queda el el ro_id 2 xq es la opciuon de registrado en la DB

        //Encriptar el password(en este caso lo hacemos por MD5, pero existen otras)
        $passwordEncriptado = md5($password);
    
        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);
    
        //Vincular parametro
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password',$passwordEncriptado, PDO::PARAM_STR);
        //$stmt->bindParam('rol_id',$rol_id, PDO::PARAM_STR); Esto no va
    
        //Ejecutar queery
        if ($stmt->execute()) {
            return true;
        }

        //SI hay error
        printf("error $s\n", $stmt->error);
    }
//-----------------------------------------------------------//
    //Validar si el mail ya esta registrado
    public function validar_mail_registrar($email){

        $query = 'SELECT * FROM usuarios WHERE email = :email';

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        //Ejecutar queery
        $resultado = $stmt->execute();

        //retorno los articulos
        $registroEmail = $stmt->fetch(PDO::FETCH_ASSOC);

        //Si el mail == true entonces retorno false, no permitiendo registrar
        if ($registroEmail) {//RECORDA!! no es necesario poner == true
            return false;
        }else {
            return true;
        }

    }
//-----------------------------------------------------------//
    //Validar si el mail ya esta registrado
    public function acceder($email, $password){

        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email AND password = :password';

        //Encriptar el password MD5 (en este caso se debe encriptar xq no puedo comparar una password encriptada con una que no lo esta)
        $passwordEncriptado = md5($password);

        //Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $passwordEncriptado, PDO::PARAM_STR);

        //Ejecutar queery
        $resultado = $stmt->execute();

        //retorno los articulos
        $existeUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        //Si el usuario existe retorna == true, entonces se esta autenticando de manera correcta
        if ($existeUsuario) {
            return true;
        }else {
            return false;
        }


    }

    
    
    }








?>