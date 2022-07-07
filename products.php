<?php
class Products
{
    private $connection;
    private $tableProduct = "t_producto";
    private $viewProducts = "vw_productos";
    private $procedureProducts = "pa_obtener_producto";
    private $procedureMedia = "pa_guardar_imagen";
    private $procedureCreateProducts = "pa_crear_producto";
    private $procedureDeleteProducts = "pa_eliminar_producto";
    private $procedureUpdateProducts = "pa_actualizar_producto";
    private $procedureUpdateMedia = "pa_actualizar_media";
    private $result;
    public $id;
    public $categoria;
    public $imagen;
    public $nombre;
    public $descripcion;
    public $precio_unitario;
    public $cantidad;
    public $idMedia;

    //Constructor de la clase
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    //Obtener todos los productos
    public function getProducts()
    {
        return $this->connection->query("SELECT * FROM " . $this->viewProducts . "");
    }

    //Crear producto
    public function create()
    {
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->precio_unitario = htmlspecialchars(strip_tags($this->precio_unitario));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->connection->query("CALL " . $this->procedureCreateProducts . "('" . $this->categoria . "',
        '" . $this->idMedia . "','" . $this->nombre . "','" . $this->descripcion . "','" . $this->precio_unitario . "',
        '" . $this->cantidad . "')");
        return $this->connection->affected_rows > 0 ? true : false;
    }

    //Actualizar producto
    public function update()
    {
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->precio_unitario = htmlspecialchars(strip_tags($this->precio_unitario));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->connection->query("CALL " . $this->procedureUpdateProducts . "('" . $this->id . "',
        '" . $this->categoria . "','" . $this->nombre . "','" . $this->descripcion . "','" . $this->precio_unitario . "',
        '" . $this->cantidad . "')");
        if ($this->connection->affected_rows > 0) {
            if ($this->imagen != null) {
                $nameImage = "imagen.jpg";
                $serverPath = "http://" . $_SERVER['HTTP_HOST'] . "/www/API-PROYECTO/";
                $directory = "uploads/" . $this->generateRandom();
                $this->result = $this->connection->query("SELECT * FROM ".$this->tableProduct." WHERE id_producto = ".$this->id);
                $row = $this->result->fetch_array();
                $this->idMedia = $row['id_media'];
                if (!file_exists($directory)) {
                    mkdir("../../" . $directory, 0777, true);
                    if (file_put_contents("../../" . $directory . $nameImage, base64_decode($this->imagen))) {
                        $route = $serverPath . $directory . $nameImage;
                        return $this->connection->query("CALL ".$this->procedureUpdateMedia."('".$this->idMedia."','$route')") ? true : false;
                    }
                }
            }
        }
    }

    //Obtener un solo producto
    public function getSingleProduct()
    {
        return $this->connection->query("CALL " . $this->procedureProducts . "('" . $this->id . "')");
    }

    //Eliminar un producto
    public function delete()
    {
        return $this->connection->query("CALL " . $this->procedureDeleteProducts . "('" . $this->id . "')");
    }

    //Funcion para subir la imagen al servidor, agregarle nombre a la imagen y obtener la URL de la imagen para almacenarla en la base de datos
    public function uploadImage()
    {
        $this->result = $this->connection->query("SELECT id_media FROM t_media ORDER BY id_media ASC");
        $id = 0;
        $nameImage = "imagen.jpg";
        $serverPath = "http://" . $_SERVER['HTTP_HOST'] . "/www/API-PROYECTO/";
        while ($row = $this->result->fetch_array()) {
            $id = intval($row['id_media'] + 1);
        }
        if ($this->imagen != null) {
            $directory = "uploads/" . $this->generateRandom();
            if (!file_exists($directory)) {
                mkdir("../../" . $directory, 0777, true);
                if (file_put_contents("../../" . $directory . $nameImage, base64_decode($this->imagen))) {
                    $route = $serverPath . $directory . $nameImage;
                    if ($this->connection->query("CALL " . $this->procedureMedia . "('$route')")) return $id;
                    else return null;
                }
            }
        } else {
            if ($this->connection->query("CALL " . $this->procedureMedia . "('".$serverPath."uploads/nophoto.jpg')")) return $id;
            else return null;
        }
    }

    //Funcion para generar un nombre random a partir del nombre del producto y 10 caracteres con numeros random
    public function generateRandom()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $cadena = str_replace(' ', '-', $this->nombre);
        return $cadena . "-" . $randomString . "/";
    }
}
