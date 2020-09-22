<?php
	require_once("configs.php");
	require_once("funcoes_bd.php");
 
	//		Debug erros
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	

	//header('Content-Type: text/html; charset=utf-8');
	$headerStringValue = $_SERVER['HTTP_AUTENTICAR'];
	header('Content-Type: application/json');



	//============================================================================
	//		Only GET allowed
	//============================================================================
	if($_SERVER['REQUEST_METHOD'] != "GET")
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

	$current_state = bd_GetCurrentStates();
	$lampada_state = $current_state["lampada"];
	$pump_state = $current_state["pump"];
	$janela_state = $current_state["janela"];


	$body = json_encode(array(
        'lampada' => $lampada_state,
        'pump' => $pump_state,
        'janela' => $janela_state
        ));

	echo ($body);

 
?>