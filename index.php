<?php

if(!empty($_FILES)) {
	$target_dir = "";
	$target_file = $target_dir . "upload.json";
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
        header("Location: dynamicCirclePacking.php");
		
    } else {
        print_r("Sorry, your file was not uploaded.");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
<div class="container">
	<div class="row" style="margin-top: 100px;">
		<div class="col-lg-12"><h2>Circle Packing</h2></div>
	</div>
	<div class="row" style="margin-top: 50px;">
		<div class="col-lg-5">
			<form action="" method="POST" enctype="multipart/form-data">
				<div>
					<label><strong>Upload JSON:</strong></label>
					<input type="file" name="upload" class="form-control">
				</div>
				<div style="margin-top:20px;">
					<input type="submit" value="Upload" class="btn btn-success">
				</div>
			</form>
		</div>
		<div class="col-lg-2"><h1>OR</h1></div>
		<div class="col-lg-5">
			<h3>View with Default Circle Packing</h3>
			<a href="circlepacking.php" class="btn btn-primary" style="margin-top:20px;">Default Circle</a>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>