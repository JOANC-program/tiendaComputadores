<?php

class Conexion
{
    private $mySQLI;
    private $filasAfectadas;
    private $sql;
    private $result;

    public function abrir()
    {
        // Solo abre si no está ya abierta o si la conexión existente no está activa
        if ($this->mySQLI === null || $this->mySQLI->ping() === false) {
            $this->mySQLI = new mysqli("localhost", "root", "", "computadores"); // <-- ¡VERIFICA ESTE NOMBRE DE DB!
            if ($this->mySQLI->connect_error) {
                throw new Exception("Error al conectar con la DB: " . $this->mySQLI->connect_error);
            }
            $this->mySQLI->set_charset("utf8mb4"); // Recomendado: Establecer el conjunto de caracteres
        }
        return true;
    }

    public function cerrar()
    {
        if ($this->mySQLI && $this->mySQLI->ping()) {
            $this->mySQLI->close();
            $this->mySQLI = null; // Para indicar que no hay conexión activa
        }
    }

    public function beginTransaction()
    {
        // Asegura que la conexión esté abierta y activa antes de iniciar la transacción
        if (!$this->mySQLI || !$this->mySQLI->ping()) { 
            $this->abrir(); 
        }
        $this->mySQLI->autocommit(FALSE);
        return true;
    }

    public function commit()
    {
        if (!$this->mySQLI || !$this->mySQLI->ping()) {
            throw new Exception("La conexión a la base de datos no está abierta o se perdió.");
        }
        $result = $this->mySQLI->commit();
        $this->mySQLI->autocommit(TRUE); 
        return $result;
    }

    public function rollBack()
    {
        if (!$this->mySQLI || !$this->mySQLI->ping()) {
            throw new Exception("La conexión a la base de datos no está abierta o se perdió.");
        }
        $result = $this->mySQLI->rollback();
        $this->mySQLI->autocommit(TRUE);
        return $result;
    }

    public function consulta($sql)
    {
        // Aquí no se usa prepare, se sigue usando query() directamente
        if (!$this->mySQLI || !$this->mySQLI->ping()) {
            $this->abrir(); // Asegura que la conexión esté abierta
        }
        $this->sql = $sql;
        $this->result = $this->mySQLI->query($this->sql);
        $this->filasAfectadas = $this->mySQLI->affected_rows;
        // Si hay un error en la consulta, podrías querer manejarlo aquí
        if ($this->result === false && $this->mySQLI->error) {
            error_log("Error en consulta: " . $this->mySQLI->error . " SQL: " . $sql);
        }
        return $this->result; // Retorna el resultado (para SELECTs, será un mysqli_result object)
    }

    public function obtenerResult()
    {
        return $this->result;
    }

    public function obtenerFilasAfectadas()
    {
        return $this->filasAfectadas;
    }

    public function getMysqli()
    {
        // Este método es para cuando los Gestores necesitan el objeto mysqli directo
        // para usar prepare(), begin_transaction(), commit(), rollback()
        if (!$this->mySQLI || !$this->mySQLI->ping()) {
            $this->abrir(); // Asegura que la conexión esté activa
        }
        return $this->mySQLI;
    }

    public function obtenerInsertId() {
        if (!$this->mySQLI || !$this->mySQLI->ping()) {
            throw new Exception("La conexión a la base de datos no está abierta.");
        }
        return $this->mySQLI->insert_id;
    }
}
?>