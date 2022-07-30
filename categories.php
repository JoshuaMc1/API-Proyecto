<?php
class Categories
{
    private $connection;
    private $tablaCategoria = "categorias";
    public $id;
    public $categoria;
    public $result;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getCategories()
    {
        $this->result = mysqli_query($this->connection, "SELECT id, categoria FROM " . $this->tablaCategoria . " WHERE status = 1");
        return $this->result;
    }

    public function create()
    {
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        return mysqli_query($this->connection, "INSERT INTO " . $this->tablaCategoria . " (categoria) VALUES ('" . $this->categoria . "')");
    }

    public function update()
    {
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->id = htmlspecialchars(strip_tags($this->id));
        return mysqli_query($this->connection, "UPDATE " . $this->tablaCategoria." SET categoria = '".$this->categoria."' WHERE id = ".$this->id);
    }

    public function delete()
    {
        $this->id = htmlspecialchars(strip_tags($this->id));
        return mysqli_query($this->connection, "UPDATE ".$this->tablaCategoria." SET status = 0 WHERE id = " . $this->id);
    }
}
