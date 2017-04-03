<?php
	$accepted_origins = array($_SERVER['HTTP_ORIGIN']);

	$imageFolder = "images/";
	reset ($_FILES);
	$temp = current($_FILES);
	if (is_uploaded_file($temp['tmp_name'])){

		if (isset($_SERVER['HTTP_ORIGIN'])) {

			if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
				header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
			}
			else {
				header("HTTP/1.0 403 Origin Denied");
				exit();
			}

		}

		if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
			header("HTTP/1.0 500 Invalid file name.");
			exit();
		}

		$ext = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
		if (!in_array($ext, array("gif", "jpg", "png", "bmp", "svg", "dat"))) {
			header("HTTP/1.0 500 Invalid extension.");
			exit();
		}
		$image_name = "";
		if($ext == "dat"){
			$image_name = "image_".time().".bmp";
		}
		else{
			$image_name = "image_".time().".".$ext;
		}
		$filetowrite = $imageFolder . $image_name;
		move_uploaded_file($temp['tmp_name'], $filetowrite);

		$filetowrite = getPath($image_name);
		echo json_encode(array('location' => $filetowrite));

	}
	else {
		header("HTTP/1.0 500 Server Error");
	}

	function getPath($imageName){
		$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
		$returedURI = "{$protocol}://".$_SERVER['HTTP_HOST'];
		$uri = explode("/", $_SERVER['REQUEST_URI']);

		for($i=0; $i<sizeof($uri)-1; $i++){
			$returedURI .= $uri[$i].'/';
		}

		return $returedURI . "images/" . $imageName;
	}
?>
