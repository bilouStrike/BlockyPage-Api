<?php
session_start();
if (isset($_POST['add'])) {
	if ( $_POST['key'] == 'e47282afa8b79601e14a5826a98fcf3713106f74' ) {
		$_SESSION["authorized"] = true;
		header("Location: insert.php"); 
		exit();
	}
}	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Auth</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
	<div class="container" style="padding-top: 100px;">
		<form method="POST" action="simpleAuth.php" enctype="multipart/form-data">
		  <div class="form-group">
		    <label>Secret key :</label>
		    <input type="text" class="form-control" name="key">
		  </div>
		   <button type="submit" name="add" class="btn btn-primary">Submit</button>
		</form>
</div>
</body>
</html>