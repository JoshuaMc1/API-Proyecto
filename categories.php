<?php
class Categories
{
    private $connection;
    private $viewCategories ="vw_categorias";
    private $tableCategoria = "t_categoria";
    public $id;
    public $categoria;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getCategories()
    {
        return $this->connection->query("SELECT * FROM ".$this->viewCategories);
    }

    public function create()
    {
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->connection->query("INSERT INTO ".$this->tableCategoria." (categoria) VALUES ('".$this->categoria."')");
        return $this->connection->affected_rows > 0 ? true : false;
    }

    public function update()
    {
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->id = htmlspecialchars(strip_tags($this->id));
        return $this->connection->query("UPDATE ".$this->tableCategoria." SET categoria = '".$this->categoria."' WHERE id_categoria = ".$this->id);
    }

    public function delete()
    {
        $this->id = htmlspecialchars(strip_tags($this->id));
        return $this->connection->query("UPDATE ".$this->tableCategoria." SET status = '0' WHERE id_categoria = ".$this->id);
    }
}
