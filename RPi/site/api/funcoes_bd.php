<?php

	require_once("configs.php");


	//============================================================================
	//		Funcoes Temperatura e Humidade
	//============================================================================
	function bd_InsertTempHumidHistory($temperatura, $humidade, $data){

	$conn =@new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$sql = "INSERT INTO `historicoTempEHumid`(`temperatura`, `humidade`, `data`) VALUES (?, ?, ?)";	
	$cmd = $conn->prepare($sql);			
	$cmd->bind_param("dds", $temperatura, $humidade, $data);	
	$status = $cmd->execute();
	$conn->close();		
	return $status;
	}

	//Get the current values of Temperature and Humidity from database
	function bd_GetCurrentTempHumid(){

		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT `temperatura`, `humidade`,  `data` FROM `historicoTempEHumid` WHERE `data` = ( select max(`data`) from historicoTempEHumid) LIMIT 1";
		$cmd = $conn->prepare($sql);
		$cmd->execute();
		$result =$cmd->get_result();
		$first_row = $result->fetch_assoc();
		$conn->close();		
		return $first_row;	
	}

	//Get history of temp and humid values
	function bd_ObterHistorico(){

		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 	
		$sql = "SELECT * FROM `historicoTempEHumid` ORDER BY `data` DESC LIMIT 100";
		$cmd = $conn->prepare($sql);		
		$cmd->execute();
		$result = $cmd->get_result();
		$array = $result->fetch_all(MYSQLI_ASSOC);
		$conn->close();		
		return $array;
	}
	//============================================================================
	




	//============================================================================
	//		Funcoes Moisture
	//============================================================================
	
	//Get current percentage of Moisture
	function bd_GetCurrentMoisture(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT `moisture`,  `data` FROM `historicoMoisture` WHERE `data` = ( select max(`data`) from historicoMoisture) LIMIT 1";
		$cmd = $conn->prepare($sql);
		$cmd->execute();
		$result =$cmd->get_result();
		$first_row = $result->fetch_assoc();
		$conn->close();		
		return $first_row;	
	}

	//Get history of moisture
	function bd_ObterHistoricoMoisture(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 	
		$sql = "SELECT * FROM `historicoMoisture` ORDER BY `data` DESC LIMIT 100";
		$cmd = $conn->prepare($sql);		
		$cmd->execute();
		$result = $cmd->get_result();
		$array = $result->fetch_all(MYSQLI_ASSOC);
		$conn->close();		
		return $array;
	}

	function bd_InsertMoisture($moisture, $data)
	{
	$conn =@new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$sql = "INSERT INTO `historicoMoisture`(`moisture`, `data`) VALUES (?, ?)";	
	$cmd = $conn->prepare($sql);			
	$cmd->bind_param("ds", $moisture, $data);	
	$status = $cmd->execute();
	$conn->close();		
	return $status;
	}


	function tipoSolo($moisture)
	{
		$stringTemp='';
		if ($moisture<=25 && $moisture>=0) {
			$stringTemp='Solo Seco';
		}
		else if ($moisture<=50 && $moisture>25) {
			$stringTemp='Solo Normal';
		}
		else if ($moisture<=75 && $moisture>50) {
			$stringTemp='Solo Molhado';
		}
		else{
			$stringTemp='Solo Encharcado';
		}
		return $stringTemp;
	}


	//============================================================================
	

	//Get current percentage of qtdAgua
	function bd_GetCurrentqtdAgua(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT `qtdAgua`,  `data` FROM `historicoQtdAgua` WHERE `data` = ( select max(`data`) from historicoQtdAgua) LIMIT 1";
		$cmd = $conn->prepare($sql);
		$cmd->execute();
		$result =$cmd->get_result();
		$first_row = $result->fetch_assoc();
		$conn->close();		
		return $first_row;	
	}

	//Get history of qtdAgua
	function bd_ObterHistoricoQtdAgua(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 	
		$sql = "SELECT * FROM `historicoQtdAgua` ORDER BY `data` DESC LIMIT 100";
		$cmd = $conn->prepare($sql);		
		$cmd->execute();
		$result = $cmd->get_result();
		$array = $result->fetch_all(MYSQLI_ASSOC);
		$conn->close();		
		return $array;
	}

	function bd_InsertqtdAgua($qtdAgua, $data)
	{
	$conn =@new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$sql = "INSERT INTO `historicoQtdAgua`(`qtdAgua`, `data`) VALUES (?, ?)";	
	$cmd = $conn->prepare($sql);			
	$cmd->bind_param("ds", $qtdAgua, $data);	
	$status = $cmd->execute();
	$conn->close();		
	return $status;
	}

		//============================================================================
	

	//Get current percentage of qtdLuz
	function bd_GetCurrentqtdLuz(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT `qtdLuz`,  `data` FROM `historicoQtdLuz` WHERE `data` = ( select max(`data`) from historicoQtdLuz) LIMIT 1";
		$cmd = $conn->prepare($sql);
		$cmd->execute();
		$result =$cmd->get_result();
		$first_row = $result->fetch_assoc();
		$conn->close();		
		return $first_row;	
	}

	//Get history of qtdLuz
	function bd_ObterHistoricoQtdLuz(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 	
		$sql = "SELECT * FROM `historicoQtdLuz` ORDER BY `data` DESC LIMIT 100";
		$cmd = $conn->prepare($sql);		
		$cmd->execute();
		$result = $cmd->get_result();
		$array = $result->fetch_all(MYSQLI_ASSOC);
		$conn->close();		
		return $array;
	}

	function bd_InsertqtdLuz($qtdLuz, $data)
	{
		$conn =@new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "INSERT INTO `historicoQtdLuz`(`qtdLuz`, `data`) VALUES (?, ?)";	
		$cmd = $conn->prepare($sql);			
		$cmd->bind_param("ds", $qtdLuz, $data);	
		$status = $cmd->execute();
		$conn->close();		
		return $status;
	}




	function bd_GetCurrentStates(){
		$conn = @new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT `lampada`,`pump`,`janela` FROM `atuadores` WHERE `id` = ( select max(`id`) from atuadores) LIMIT 1";
		$cmd = $conn->prepare($sql);
		$cmd->execute();
		$result =$cmd->get_result();
		$first_row = $result->fetch_assoc();
		$conn->close();		
		return $first_row;	
	}

	function bd_StoreImage(){
		$conn =@new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		die("ola");

		
	}
	


/*
$conn =@new mysqli($GLOBALS["bd_server"], $GLOBALS["bd_user"], $GLOBALS["bd_password"], $GLOBALS["bd_name"]);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "INSERT INTO image (image) VALUES(?)";	
		$cmd = $conn->prepare($sql);	
		$null = NULL;		
		$cmd->bind_param("b", $null);
		$cmd->send_long_data(0, file_get_contents($image));	
		$status = $cmd->execute();
		$conn->close();
		return $status;
*/

?>