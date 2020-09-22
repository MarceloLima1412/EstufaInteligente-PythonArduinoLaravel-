<?php
	require_once("configs.php");
	require_once("funcoes_bd.php");

	//		Debug erros
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	$headerStringValue = $_SERVER['HTTP_AUTENTICAR'];
	$headerStringValueS = $_SERVER['HTTP_DATE'];

	//print_r($_FILES);


	//============================================================================
	//		Only POST allowed
	//============================================================================
	if($_SERVER['REQUEST_METHOD'] != "POST")
	{
		http_response_code(403);
		echo('{"erro": "Método ' . $_SERVER['REQUEST_METHOD'] . ' não é permitido!"}' . PHP_EOL);
		exit();		
	}	
	//============================================================================

	//============================================================================
	//		Auth
	//============================================================================
	if($headerStringValue != "essImage")
	{
		http_response_code(402);
		echo('{"erro": "auth falhou!"}');
		exit();		
	}	
	//============================================================================
    
    $image = $_FILES['image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));

    //Create connection and select DB
        $db = new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
        
        // Check connection
        if($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }
        
        $dataTime = date("Y-m-d H:i:s");
        
        //Insert image content into database
        $insert = $db->query("INSERT into image (image, data) VALUES ('$imgContent', '$headerStringValueS')");
        if($insert){
            echo "File uploaded successfully.";
        }else{
            echo "File upload failed, please try again.";
        } 

    return $headerStringValueS;


?>