<?php
include 'authentication.php';
class Products
{
    private $connection;
    private $tablaProductos = "productos";
    private $tablaCategories = "categorias";
    private $result;
    public $pid;
    public $nombre;
    public $descripcion;
    public $precio;
    public $categoria;
    public $imagen_producto;
    public $cantidad;
    private $auth;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->auth = new Authentication($connection);
    }

    public function getProducts()
    {
        $this->result = mysqli_query($this->connection, "SELECT p.pid, p.nombre, p.descripcion, p.id_categoria, c.categoria, p.precio, p.imagen_producto, p.cantidad FROM " . $this->tablaProductos . " p INNER JOIN " . $this->tablaCategories . " c ON p.id_categoria = c.id WHERE p.status = 1 ORDER BY p.id ASC");
        return $this->result;
    }

    public function getSingleProduct()
    {
        $this->result = mysqli_query($this->connection, "SELECT p.pid, p.nombre, p.descripcion, p.id_categoria, c.categoria, p.precio, p.imagen_producto, p.cantidad FROM " . $this->tablaProductos . " p INNER JOIN " . $this->tablaCategories . " c ON p.id_categoria = c.id WHERE p.pid = " . $this->pid);
        return $this->result;
    }

    public function create()
    {
        $this->pid = $this->auth->createNumericToken();
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        if ($this->imagen_producto != null) $this->imagen_producto = base64_encode($this->imagen_producto);
        $sql  = "INSERT INTO " . $this->tablaProductos . " (pid, nombre, descripcion, precio, id_categoria, imagen_producto, cantidad)";
        $sql .= "VALUES(" . $this->pid . ", '" . $this->nombre . "', '" . $this->descripcion . "', " . $this->precio . ", " . $this->categoria . ", '" . $this->imagen_producto . "', " . $this->cantidad . ");";
        return mysqli_query($this->connection, $sql);
    }

    public function update()
    {
        $this->pid = htmlspecialchars(strip_tags($this->pid));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $sql = "";
        if ($this->imagen_producto != null) {
            $this->imagen_producto = base64_encode($this->imagen_producto);
            $sql = "UPDATE " . $this->tablaProductos . " SET nombre = '" . $this->nombre . "', descripcion = '" . $this->descripcion;
            $sql .= "', precio = " . $this->precio . ", cantidad = " . $this->cantidad . ", id_categoria = " . $this->categoria . ", imagen_producto = '" . $this->imagen_producto . "' ";
            $sql .= "WHERE pid = " . $this->pid;
        } else {
            $sql = "UPDATE " . $this->tablaProductos . " SET nombre = '" . $this->nombre . "', descripcion = '" . $this->descripcion;
            $sql .= "', precio = " . $this->precio . ", cantidad = " . $this->cantidad . ", id_categoria = " . $this->categoria . " WHERE pid = " . $this->pid;
        }
        return mysqli_query($this->connection, $sql);
    }

    public function delete()
    {
        $this->pid = htmlspecialchars(strip_tags($this->pid));
        $sql = "UPDATE " . $this->tablaProductos . " SET status = 0 WHERE pid = " . $this->pid;
        return mysqli_query($this->connection, $sql);
    }
}
