<?php

class Connection {

	protected static $conn;

	private function __construct() {
		self::connect();
	}

	private static function connect() {
		self::$conn = new mysqli("localhost", "root", "", "icwsm-2016");

	    /* check connection */
	    if (self::$conn->connect_errno) {
	        printf("Connect failed: %s\n", self::$conn->connect_error);
	        exit();
	    }

	    if (!self::$conn->set_charset("utf8")) {
	        printf("Error loading character set utf8: %s\n", $mysqli->error);
	        exit();
	    }
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