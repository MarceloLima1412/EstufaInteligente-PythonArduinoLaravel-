<?php

use Illuminate\Http\Request;
use App\Tempehumid;
use App\Atuadore;
use App\Agua;
use App\Luz;
use App\Moisture;
use App\Config;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getAtuador', function(){
        $data = Atuadore::all();
        $dataPump = $data[count($data)-1];
        $dataJanela = $data[count($data)-2];
        $dataLamp = $data[count($data)-3];
        $lampState = $dataLamp["ativo"];
        $janelaState = $dataJanela["ativo"];
        $pumpState = $dataPump["ativo"];
	$headerStringValue = $_SERVER['HTTP_AUTENTICAR'];
	header('Content-Type: application/json');
	//============================================================================
	//		Auth
	//============================================================================
	if($headerStringValue != "atuadorState")
	{
		http_response_code(402);
		echo('{"erro": "auth falhou!"}');
		exit();		
	}	
	//============================================================================
	return response()->json([
	    'janela' => $janelaState,
	    'pump' => $pumpState,
	    'lampada' => $lampState
	    
	    
	]);
});

Route::post('post_temp_humid', function(){
	header('Content-Type: text/html; charset=utf-8');
	//		Post - verify data
	//============================================================================
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['auth']) || !isset($DATA['temperatura']) || !isset($DATA['humidade']) || !isset($DATA['date']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//VARIAVEIS
	//===========================================
	$user_pwd = $DATA['auth'];
	$user_temperatura = $DATA['temperatura'];
	$user_humidade = $DATA['humidade'];
	$user_date = $DATA['date'];
	//===========================================
	//============================================================================
		//		Credenciais
	//=======================================================
	if ($user_pwd != "esspassword")
	{	
		http_response_code(401);		
		echo('{"erro": "Erro de autenticação!"}' . PHP_EOL);		
		exit();
	}
	//=======================================================
	$result=DB::insert("insert into tempehumids (temperatura, humidade, data) values(?,?,?)",[$user_temperatura, $user_humidade, $user_date]);
});

#LUZ
Route::post('post_qtd_luz', function(){
	header('Content-Type: text/html; charset=utf-8');
	//		Post - verify data
	//============================================================================
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['auth']) || !isset($DATA['qtdLuz']) || !isset($DATA['date']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//VARIAVEIS
	//===========================================
	$user_pwd = $DATA['auth'];
	$user_qtdLuz = $DATA['qtdLuz'];
	$user_date = $DATA['date'];
	//===========================================
	//============================================================================
		//		Credenciais
	//=======================================================
	if ($user_pwd != "esspassword")
	{	
		http_response_code(401);		
		echo('{"erro": "Erro de autenticação!"}' . PHP_EOL);		
		exit();
	}
	//=======================================================
	$result=DB::insert("insert into luzs (qtdLuz, data) values(?,?)",[$user_qtdLuz, $user_date]);
});

#Moisture
Route::post('post_moisture', function(){
	header('Content-Type: text/html; charset=utf-8');
	//		Post - verify data
	//============================================================================
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['auth']) || !isset($DATA['moisture']) || !isset($DATA['date']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//VARIAVEIS
	//===========================================
	$user_pwd = $DATA['auth'];
	$user_moisture = $DATA['moisture'];
	$user_date = $DATA['date'];
	//===========================================
	//============================================================================
		//		Credenciais
	//=======================================================
	if ($user_pwd != "esspassword")
	{	
		http_response_code(401);		
		echo('{"erro": "Erro de autenticação!"}' . PHP_EOL);		
		exit();
	}
	//=======================================================
	$result=DB::insert("insert into moistures (moisture, data) values(?,?)",[$user_moisture, $user_date]);
});

#Qtd Agua
Route::post('post_qtd_agua', function(){
	header('Content-Type: text/html; charset=utf-8');
	//		Post - verify data
	//============================================================================
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['auth']) || !isset($DATA['qtdAgua']) || !isset($DATA['date']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//VARIAVEIS
	//===========================================
	$user_pwd = $DATA['auth'];
	$user_qtdAgua = $DATA['qtdAgua'];
	$user_date = $DATA['date'];
	//===========================================
	//============================================================================
		//		Credenciais
	//=======================================================
	if ($user_pwd != "esspassword")
	{	
		http_response_code(401);		
		echo('{"erro": "Erro de autenticação!"}' . PHP_EOL);		
		exit();
	}
	//=======================================================
	$result=DB::insert("insert into aguas (qtdAgua, data) values(?,?)",[$user_qtdAgua, $user_date]);
});

#image
Route::post('post_image', function(){
	header('Content-Type: text/html; charset=utf-8');
	$headerStringValue = $_SERVER['HTTP_AUTENTICAR'];
	$headerStringValueS = $_SERVER['HTTP_DATE'];
	$image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));
	
	//============================================================================
		//		Credenciais
	//=======================================================
	if($headerStringValue != "essImage")
	{
		http_response_code(402);
		echo('{"erro": "auth falhou!"}');
		exit();		
	}	
	//=======================================================

	$result=DB::insert("INSERT into image (image, data) values(?,?)",[$imgContent, $headerStringValueS]);
	return $headerStringValueS;
});

Route::get('getConfig', function(){
    $data = Config::all();
    $atualizacao = $data[0]["variavel"];
    $automacao = $data[1]["variavel"];
    $fotografia = $data[2]["variavel"];
	$headerStringValue = $_SERVER['HTTP_AUTENTICAR'];
	header('Content-Type: application/json');
	//============================================================================
	//		Auth
	//============================================================================
	if($headerStringValue != "essConfig")
	{
		http_response_code(402);
		echo('{"erro": "auth falhou!"}');
		exit();		
	}	
	//============================================================================
	return response()->json([
	    'atualizacao' => $atualizacao,
	    'automacao' => $automacao,
	    'fotografia' => $fotografia
	]);
});


