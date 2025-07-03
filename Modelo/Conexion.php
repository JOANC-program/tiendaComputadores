<?php
class Conexion{
    private $mySQLI;
    private $filasAfectadas;
    private $sql;
    private $result;
    public function abrir(){
        $this->mySQLI = new mysqli("localhost","root","","computadores");
        if($this->mySQLI->connect_error){
            throw new Exception("Error al conectar con la DB".$this->mySQLI->connect_error);
        };
        return true;
    }

    public function cerrar(){
        if ($this->mySQLI){
            $this->mySQLI->close();
        }
    }

    public function consulta($sql){
        $this->sql=$sql;
        $this->result= $this->mySQLI->query($this->sql);
        $this->filasAfectadas= $this->mySQLI->affected_rows;
    }
     public function obtenerResult(){
        return $this->result;
    }
    
    public function obtenerFilasAfectadas(){
        return $this->filasAfectadas;
    }

    public function obtenerInsertId() {
        return $this->mySQLI->insert_id;
    }
}