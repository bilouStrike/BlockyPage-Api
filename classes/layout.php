<?php

require_once './config/db.php';

/*
 * Layout class 
 */

class Layout {

	private $connection;

	public $id;
    public $name;
    public $content;
    public $type;
    public $thumbnail;
    public $category_id;
    public $category_name;
    
	
	function __construct($db) {
		$this->connection = $db;
	}

	/**
	* @return array
	*/
	public function getAlLayout() {
		$query = 'SELECT layout.id, layout.name, content, thumbnail, category.name
            FROM  layout 
            LEFT JOIN category ON layout.category = category.id 
            ORDER BY layout.id DESC ';
		    $stmt = $this->connection->query($query);
		    return $stmt;
    }

	/**
	* 
	* @param category
	* @return array
	*/
	public function getByCategory($category) {
		$query = 'SELECT layout.id, layout.name, content, thumbnail, category.name
            FROM  layout 
            LEFT JOIN category ON layout.category = category.id 
            WHERE category.name = "'.$category.'"
            ORDER BY layout.id DESC ';
		    $stmt = $this->connection->query($query);
		    return $stmt;
	}

	/**
	* 
	* @param layout_id
	* @return string
	*/
	public function getById($id) {
		$query = 'SELECT layout.id, layout.name, content, thumbnail, category.name
            FROM  layout 
            RIGHT JOIN category ON layout.category = category.id 
            WHERE layout.id = '.intval($id).'
            ORDER BY layout.id DESC ';
		    $stmt = $this->connection->query($query);
		    return $stmt;
	}

	/**
	* @param array
	* @return void
	*/
	public function insertLayout($layoutData) {
		 var_dump($layoutData);
		$insert_pro = $this->connection->prepare("insert into layout (name, content, thumbnail, category) values (?,?,?,?)");
		$insert_pro->bind_param("ssss",$layoutData['name'],$layoutData['content'],$layoutData['thumbnail'],$layoutData['category']);
		$run_pro = $insert_pro->execute();
	}


}