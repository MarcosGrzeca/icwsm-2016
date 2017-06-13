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
		self::$conn = new mysqli("localhost", "root", "", self::$bd);

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