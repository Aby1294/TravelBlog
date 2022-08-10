<?php 

    class Basemysql{

    //Parámetros base de datos
    private $host = 'localhost';//me conecto por localhost
    private $db_name = 'blog';//nombre base de datos
    private $username = 'root';//el usuario por defecto
    private $password = '';//no tiene conrtrasena
    private $conn;//variable q va a manejar la conexion a la base de datos

    //Conexión a la BD
    public function connect(){
        $this->conn = null;//metodo de la funcion q me permite conectarme

        //try = intentar
        try {                   //cadena de conexcion, lo concateno con 'host' 
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' .$this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //cantch = capturar  especifica una respuesta si se produce una excepción 
        } catch (PDOException $e) {//
            echo "Error en la conexión: " . $e->getMessage();
        }

        return $this->conn;
    }

    }

    ?>