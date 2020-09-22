<?php
	require_once("configs.php");
	require_once("funcoes_bd.php");

	//		Debug erros
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	

	header('Content-Type: text/html; charset=utf-8');


	//============================================================================
	//		Only Post allowed
	//============================================================================
	if($_SERVER['REQUEST_METHOD'] != "POST")
	{
		http_response_code(403);
		echo('{"erro": "Método ' . $_SERVER['REQUEST_METHOD'] . ' não é permitido!"}' . PHP_EOL);
		exit();		
	}	
	//============================================================================


 

	//============================================================================
	//		Post - verify data
	//============================================================================
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['auth']) || !isset($DATA['moisture']) || !isset($DATA['date']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//============================================================================

	



	//===========================================
	//		Get Value sent
	//===========================================
	$user_pwd = $DATA['auth'];
	$user_key = $DATA['key'];
	$user_moisture = $DATA['moisture'];
	$user_date = $DATA['date'];
	//===========================================


	//=======================================================
	//		Credenciais
	//=======================================================
	if ($user_pwd != $config_auth_password)
	{	
		http_response_code(401);		
		echo('{"erro": "Erro de autenticação!"}' . PHP_EOL);		
		exit();
	}
	//=======================================================



	//============================================================================
	//		Update Data Base
	//============================================================================
	$status_insert = bd_InsertMoisture($user_moisture, $user_date);

	
	if (!$status_insert)
	{
		http_response_code(404);					
		echo('{"erro": "Não foi possível atualizar a base de dados."}' . PHP_EOL);				
		exit();					
	}	
	//============================================================================
	else{
		$json = array("status" => "OK RPi", "key" => $user_key, "moisture" => $user_moisture, "date" => $user_date);
		echo(json_encode($json) . PHP_EOL);
	}

/*
//============================================================================
	//		Enviar para a web
	//============================================================================
	$json = array("status" => "OK", "auth" => $user_pwd,"key" => $user_key, "moisture" => $user_moisture, "date" => $user_date);
	$data_string = json_encode($json);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://heftiest-mailbox.000webhostapp.com/api/post_moisture.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec ($ch);
	curl_close ($ch);
	//============================================================================
	
	
		
		
	
	//============================================================================
	//		Response for the client
	//============================================================================
	if($result){
		$json = array("status" => "OK RPi - OK WEB", "key" => $user_key, "moisture" => $user_moisture, "date" => $user_date);
		echo(json_encode($json) . PHP_EOL);	
	}else{
		$json = array("status" => "OK RPi - ERRO WEB", "key" => $user_key, "moisture" => $user_moisture, "date" => $user_date);
		echo(json_encode($json) . PHP_EOL);	
	}	
	//============================================================================
*/
?>