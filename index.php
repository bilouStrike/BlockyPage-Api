<?php

// API library implementation




// return a specific item
//GET /item/{id}

//get all categories : GET /category

// Get by category by category : GET /category/sub-category


// create a new record
//POST /section

// update an existing record
//PUT /section/{id}

// delete an existing record
//DELETE /section/{id}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'controller/layoutController.php';
require_once 'config/db.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
 //
$requestMethod = $_SERVER["REQUEST_METHOD"];

// eveecrything else results in a 404 Not Found
if ( !$uri[2] ) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

/** 
  RequestType : browse category or get single data
  if : 
  category --> get all layout by given category
  data     --> get single data(section,layout, block-style) by id
*/
$category = ( !empty($uri[2]) && $uri[2] != 'item') ? $uri[2] : false;
$sub_category = (!empty($uri[3]) &&  $uri[2] != 'item') ? $uri[3] : false;
$item = ( !empty($uri[2]) && $uri[2] == 'item' && !empty($uri[3]) ) ? true : false;

$requestType = $sub_category !== false ? 'sub_category' : (  $category !== false ? 'category' : (  $item !== false ? 'item' : null ) ) ;

if ( $uri[2] == 'getCategories' ) {
	$requestType = 'getSubCategories';
	$category_id = $uri[3];
}


$request_data = array('request_type' =>  $requestType ,
					 'category' => $category,
					 'sub_category' => $sub_category,
					 'item_id'	=> $uri[3] ?? null,
					 'category_id' => $category_id  ?? null
					 );  


$dbConnection = new db();
$connection = $dbConnection->getConnection();
$controller = new LayoutController($connection, $requestMethod, $request_data);
$controller->dispatchRequest();