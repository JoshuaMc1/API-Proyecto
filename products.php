<?php
    class Products
    {
        private $connection;
        private $tableProducts = "t_producto";
        private $viewProducts = "vw_productos";
        private $procedureProducts = "pa_obtener_producto";
        private $result;
        public $id;
        public $categoria;
        public $imagen;
        public $nombre;
        public $descripcion;
        public $precio_unitario;
        public $cantidad;

        //Constructor de la clase
        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        //Obtener todos los productos
        public function getProducts()
        {
            $sql = "SELECT * FROM ".$this->viewProducts."";
            $this->result = $this->connection->query($sql);
            return $this->result;
        }

        //Crear producto
        public function create()
        {

        }

        //Actualizar producto
        public function update()
        {

        }

        //Obtener un solo producto
        public function getSingleProduct()
        {
            return $this->connection->query("CALL ".$this->procedureProducts."('".$this->id."')");
        }

        //Eliminar un producto
        public function delete()
        {

        }
    }
?>