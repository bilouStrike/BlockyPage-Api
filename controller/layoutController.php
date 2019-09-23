<?php

/**
 * Layout controller
 */
require_once './classes/layout.php';
require_once './classes/category.php';
class layoutController
{
	
	private $db;
    private $requestMethod;
    private $request_data;
    private $layout;
    private $category;

    public function __construct($db, $requestMethod, $request_data)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->request_data = $request_data;
        $this->layout = new Layout($db);
        $this->category = new Category($db);
    }

    public function dispatchRequest() {

        	switch ($this->request_data['request_type']) {
            case 'category':
                    $response = $this->getAlLayoutsBycategory($this->request_data['category']);
            break;
            case 'sub_category':
                    $response = $this->getAlLayoutsBycategory($this->request_data['sub_category']);
            break;
            case 'item':
                    $response = $this->getLayoutByid();
            break;
            case 'getSubCategories':
                    $response = $this->getSubCategories();
            break;
            default:
                $response = $this->notFoundResponse();
            break;
        }
        if ($response['body']) {
            echo $response['body'];
        }
    }

    function getAlLayoutsBycategory($category) {

        $data = $this->layout->getByCategory($category);
        $final = array();
        while ($row = $data->fetch_assoc()) {
            $final[] = $row;
        }
        http_response_code(200);
        echo json_encode($final);
    }


    public function getAlLayouts() {

    	$data = $this->layout->getAlLayout();
 		$final = array();
	    while ($row = $data->fetch_assoc()){
	        $final[] = $row;
	    }
	    http_response_code(200);
	    echo json_encode($final);
	}

	public function getLayoutByid() {
		$final = '';
		$data = $this->layout->getById($this->request_data['item_id']);
	    while ($row = $data->fetch_assoc()){
	        $final = $row;
	    }
	    http_response_code(200);
	    echo json_encode($final);
	}

    public function getSubCategories() {

        $data = $this->category->getByCategory($this->request_data['category_id']);
        $final = array();
        while ($row = $data->fetch_assoc()){
            $final[] = $row;
        }
        http_response_code(200);
        echo json_encode($final);
    }

    public function notFoundResponse() {
        http_response_code(405);
    }
}