<?php

class DB
{
    private $conn;
    public function connect(){
        if (!$this->conn){
            $this->conn = mysqli_connect('localhost', 'root', '123456', 'demo') or die ('Lỗi kết nối');
     
            mysqli_query($this->conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        }
    }
    public function disconnect(){
        if ($this->conn){
            mysqli_close($this->conn);
        }
    }
    public function insert($table, $data)
    {
        $this->connect();
    
        $field_list = '';
       
        $value_list = '';
        foreach ($data as $key => $value){
            $field_list .= ",$key";
            $value_list .= ",'".mysql_escape_string($value)."'";
        }
    
        $sql = 'INSERT INTO '.$table. '('.trim($field_list, ',').') VALUES ('.trim($value_list, ',').')';
    
        return mysqli_query($this->conn, $sql);
    }
}

?>