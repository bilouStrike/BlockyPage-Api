<?php
session_start();
if (!isset($_SESSION['authorized'])) {
	header("Location: simpleAuth.php"); 
	exit();
}
require_once 'classes/layout.php';
require_once 'classes/category.php';
require_once 'config/db.php';

function getCurrentDirectory() {
	$path = dirname($_SERVER['PHP_SELF']);
	$position = strrpos($path,'/') + 1;
	return substr($path,$position);
}

function currentApiUrl(){
	$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$myDomain = preg_replace('/^www\./', '', parse_url($actual_link, PHP_URL_HOST));
	return $protocol.'://'.$myDomain.'/'.getCurrentDirectory().'/' ;
}

$dbConnection = new db();
$connection = $dbConnection->getConnection();

$cat = new category($connection);
$categories = $cat->getAllCategoies();
$final = array();
	    while ($row = $categories->fetch_assoc()){
	        $final[] = $row;
	    }


if(isset($_POST['add'])) {
	
	$folder ="thumbnail/"; 
	$layout_img = $_FILES['thumbnail']['name']; 
	$path = $folder . $layout_img ; 
	$target_file = $folder.basename($_FILES["thumbnail"]["name"]);

	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	$allowed = array('jpeg','png' ,'jpg','JPEG','PNG' ,'JPG'); 
	$filename = $_FILES['thumbnail']['name']; 

	$thumbnail_url = currentApiUrl().'thumbnail/'.$layout_img;

	$ext = pathinfo($filename, PATHINFO_EXTENSION); 

	if (!in_array($ext, $allowed) ) 
		{ 
		 echo "Sorry, only JPG, JPEG, PNG & GIF  files are allowed.";
		 exit();
		}
		else
		{ 
			move_uploaded_file( $_FILES['thumbnail']['tmp_name'], $path); 
			$layoutData = array(
					'name' => $_POST['name'], 
					'content' => $_POST['content'], 
					'category' => $_POST['category'],
					'thumbnail' => $thumbnail_url, 
					'type' => null
				);
			$layout = new Layout($connection);
			$layout->insertLayout($layoutData);
		} 

}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Insert layout </title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="js/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div class="container" style="padding-top: 100px;">
		<form method="POST" action="Insert.php" enctype="multipart/form-data">
		  <div class="form-group">
		    <label>Layout name:</label>
		    <input type="text" class="form-control" name="name">
		  </div>
		  <div class="form-group">
		    <label>Category:</label>
		    <select class="custom-select" id="category">
		    	<option></option>
			    <?php
			    foreach ($final as $final) {
			    ?>
			    	<option value="<?php echo $final['id']; ?>"><?php echo $final['name']; ?></option>
			    <?php
				}
			    ?>
			</select>
		  </div>
		  <div class="form-group">
		    <label>sub Category :</label>
		    <select class="custom-select" name="category" id="sub_categories">
			   
			</select>
		  </div>

		  <div class="form-group">
		    <label>Content:</label>
		    <h3 id="selected_category"></h3>
		    <textarea class="form-control" name="content"></textarea>
		  </div>
		  <input type='file' name='thumbnail'/>
		  <button type="submit" name="add" class="btn btn-primary">Submit</button>
		</form>
</div>
</body>
<script type="text/javascript">
	$(document).ready( function() {
		$('#category').change(function() {
			let cat = $(this).val();
			let apiUrl = "<?php echo currentApiUrl(); ?>" ;
			$.ajax({
				url : apiUrl+"getCategories/"+cat ,
                type : 'post' ,
                dataType: 'json',
                data : {
                  
                },
                success: function ( response ) {
                	let all_categories = "<option></option>";
                	$.each(response, function( id, value ) {
			            all_categories += "<option value="+value.id+"> "+value.name+" </option>";
			           })
                	$('#sub_categories').html(all_categories);

                },
                error : function() {
                  console.log("wrong");
                }
			})
		});
		$('#sub_categories').change(function() {
			let scat = 'Category : '+ $(this).find("option:selected").text();
			$('#selected_category').html(scat);
		});
	});
</script>
</html>