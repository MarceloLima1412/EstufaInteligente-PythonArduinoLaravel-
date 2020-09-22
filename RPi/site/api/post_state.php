<?php
	require_once("configs.php");
	require_once("funcoes_bd.php");

	//		Debug erros
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	$headerStringValue = $_SERVER['HTTP_AUTENTICAR'];

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
	if($headerStringValue != "essState")
	{
		http_response_code(402);
		echo('{"erro": "auth falhou!"}');
		exit();		
	}	
	//============================================================================
    
    //============================================================================
	//		Post - verify data
	//============================================================================
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['lampada']) || !isset($DATA['pump']) || !isset($DATA['janela']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//============================================================================


   	//===========================================
	//		Get Value sent
	//===========================================
	$user_lampada = $DATA['lampada'];
	$user_pump = $DATA['pump'];
	$user_janela = $DATA['janela'];
	//===========================================


    //Create connection and select DB
        $db = new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
        
        // Check connection
        if($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }
        
        $dataTime = date("Y-m-d H:i:s");
        
        //Insert image content into database
        $insert = $db->query("INSERT into atuadores (lampada, pump, janela) VALUES ('$user_lampada', '$user_pump', $user_janela)");
        if($insert){
            echo "Success adding to DB";
        }else{
            echo "FAILED, please try again.";
        } 

    return $insert;


?>