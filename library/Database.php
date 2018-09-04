<?php
include('../config/config.php');
class Database
{
	private $conn;

	public function __construct() {
		$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if(!$this->conn) {
			echo "Failed to connect to : " . mysql_connect_error($this->conn);
		}
	}

	public function insertDatabase($tableName, $data)
	{	
		$string = "insert into ".$tableName." (";
		$string .= implode(",", array_keys($data)) . ') values (';            
		$string .= "'" . implode("','", array_values($data)) . "')";  

		// var_dump($string);
		
		if(mysqli_query($this->conn, $string))  
		{  
			 return true;  
		}  
		else  
		{  
			 echo mysqli_error($this->conn);  
		}

	}

	public function __destruct()
	{
		$conn->close();
	}
}
?>