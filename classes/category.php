<?php

require_once './config/db.php';

/*
 * category class 
 */

class Category {

	private $connection;

	function __construct($db) {
		$this->connection = $db;
	}

	/**
	* @return array
	*/
	public function getAllCategoies() {
		$query = 'SELECT * FROM  category WHERE parent = 0 ORDER BY id ASC ';
		    $stmt = $this->connection->query($query);
		    return $stmt;
    }

    /**
    * @param integer
	* @return array
	*/
	public function getByCategory($category) {
		$query = 'SELECT * FROM  category WHERE parent = "'.$category.'" ORDER BY id ASC ';
		    $stmt = $this->connection->query($query);
		    return $stmt;
    }

}