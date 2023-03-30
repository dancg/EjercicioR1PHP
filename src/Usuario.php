<?php 
namespace Src;

use \PDO;
use \PDOException;

class Usuario extends Conexion {
    private string $id;
    private string $email;
    private string $perfil;
    private string $ciudad;
    private string $pass;
    public function __construct()
    {
        parent::__construct();
    }
    //---------------------------------CRUD---------------------------------------
    public function create(){
        $q = "insert into usuarios (email, perfil, ciudad, pass) values(:e, :pe, :c, :p)";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':e'=>$this->email,
                ':pe'=>$this->perfil,
                ':c'=>$this->ciudad,
                ':p'=>$this->pass,
            ]);
        }catch(PDOException $ex){
            die('Error en create'.$ex->getMessage());
        }
        parent::$conexion=null;
    }
    public static function read(){
        parent::crearConexion();
        $q = "select * from usuarios";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die('Error en read'.$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function update($id){
        $q ="update usuarios set perfil=:pe where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':pe'=>$this->perfil,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die('Error en create'.$ex->getMessage());
        }
        parent::$conexion=null;
    }
    public function delete(){
        
    }
    //---------------------------------OTROS METODOS------------------------------
    public static function comprobarUsuario(string $email, string $pass) :bool{
        parent::crearConexion();
        $q = "select pass from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([':e'=>$email]);
        }catch(PDOException $ex){
            die('Error en comprobarUsuario'.$ex->getMessage());
        }
        parent::$conexion=null;
        if($stmt->rowCount() == 0) return false;
        $dbpass = $stmt->fetch(PDO::FETCH_OBJ)->pass;
        return password_verify($pass, $dbpass);
    }
    public static function devolverCiudades(){
        return ['Almeria','Cadiz','Cordoba','Granada','Huelva','Jaen','Malaga','Sevilla',];
    }
    public static function existeEmail(string $email) :bool{
        parent::crearConexion();
        $q = "select id from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([':e'=>$email]);
        }catch(PDOException $ex){
            die('Error en existeEmail'.$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
    //Función que habilita las acciones en el portal a los que sean Admin
    public static function permisoUsuarios($email){
        parent::crearConexion();
        $q = "select perfil from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([':e'=>$email]);
        }catch(PDOException $ex){
            die('Error en permisoUsuarios'.$ex->getMessage());
        }
        parent::$conexion=null;
        if($stmt->fetch(PDO::FETCH_OBJ)->perfil == "Administrador")return true;
        else return false;
    }
    //Función que devuelve el email para que no permita editarse a sí mismo
    public static function devolverEmail($id):string{
        parent::crearConexion();
        $q = "select email from usuarios where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$id]);
        }catch(PDOException $ex){
            die('Error en devolverEmail'.$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ)->email;
    }
    //---------------------------------SETTERS------------------------------------

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */ 
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Set the value of ciudad
     *
     * @return  self
     */ 
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Set the value of pass
     *
     * @return  self
     */ 
    public function setPass($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_DEFAULT);

        return $this;
    }
}