<?php

class Connection {

	protected static $conn;
	protected static $bd = "icwsm-2016";

	private function __construct() {
		self::connect();
	}

	public static function setBD($bdAlt) {
		self::$bd = $bdAlt;
	}

	private static function connect() {
		if (self::$bd == "rds") {
			self::$conn = new mysqli("alemao.ckfgeb2fkvqp.sa-east-1.rds.amazonaws.com:3306", "root", "alemao10", "alemao");
		} else {
			self::$conn = new mysqli("localhost", "root", "", self::$bd);
		}

	    /* check connection */
	    if (self::$conn->connect_errno) {
	        printf("Connect failed: %s\n", self::$conn->connect_error);
	        exit();
	    }

	    if (!self::$conn->set_charset("utf8")) {
	        printf("Error loading character set utf8: %s\n", $mysqli->error);
	        exit();
	    }

		self::$conn->set_charset("utf8mb4");
	    
	    //self::$conn->query("SET character_set_client='utf8'");
		//self::$conn->query("SET character_set_results='utf8'");
		//self::$conn->query("set collation_connection='utf8_general_ci'");
	}

	public static function get() {
        # Garante uma única instância. Se não existe uma conexão, criamos uma nova.
        if (!self::$conn)
        {
            new Connection();
        }
        # Retorna a conexão.
        return self::$conn;
    }
}

?>