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
		
		if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
			header("HTTP/1.0 500 Invalid extension.");
			exit();
		}
		
		$filetowrite = $imageFolder . $temp['name'];
		move_uploaded_file($temp['tmp_name'], $filetowrite);
		
		$filetowrite = getPath($temp['name']);
		echo json_encode(array('location' => $filetowrite));
		
	} 
	else {
		header("HTTP/1.0 500 Server Error");
	}

	function getPath($imageName){
		$returedURI = "http://".$_SERVER['HTTP_HOST'];
		$uri = explode("/", $_SERVER['REQUEST_URI']);
		
		for($i=0; $i<sizeof($uri)-1; $i++){
			$returedURI .= $uri[$i].'/';
		}

		return $returedURI . "images/" . $imageName;
	}
?>