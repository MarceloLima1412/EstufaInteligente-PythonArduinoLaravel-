import pymysql
import pymysql.cursors
import datetime
import requests
import socket
from time import sleep
from subprocess import call

#Iniciar variaveis iniciais
temperatura = None
humidade = None
luz = None
agua = None
moisture = None
dataTempHumid = None
dataLuz = None
dataAgua = None
dataMoisture = None

def is_connected():
	try:
		host = socket.gethostbyname("estufas.000webhostapp.com")
		s = socket.create_connection((host,80), 2)
		print(s)
		return True
	except:
		pass
	return False

def tempHumid(temperatura, humidade, dataTempHumid):
	#get data from database
	conn= pymysql.connect(host='localhost',user='admin',password='esspassword',db='bd-ess',charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
	c = conn.cursor()
	c.execute("SELECT * FROM historicoTempEHumid ORDER BY ID DESC LIMIT 1")
	rows = c.fetchall()
	for eachRow in rows:
		temperaturaOld = temperatura
		humidadeOld = humidade
		dataTest = dataTempHumid
		temperatura = eachRow.get('temperatura')
		humidade = eachRow.get('humidade')
		dataTempHumid = eachRow.get('data')
	#send to web
	if (dataTempHumid != dataTest and dataTest != None):
		#dataTempHumid = getTime();
		print ("Temperatura ou humidade do ar mudaram: *POST*")
		urlTempHumid = "https://estufas.000webhostapp.com/api/post_temp_humid"
		r = requests.post(urlTempHumid, json={'auth': 'esspassword',"temperatura": temperatura, "humidade": humidade,"date": str(dataTempHumid)})
		r.status_code
		print(r)
	else:
		print ("Temperatura e Humidade não mudaram")
		print ("Temperatura(old): %s " %temperaturaOld)
		print ("Temperatura: %s " %temperatura)
	return temperatura, humidade, dataTempHumid

def qtdLuz(luz, dataLuz):
	conn= pymysql.connect(host='localhost',user='admin',password='esspassword',db='bd-ess',charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
	c = conn.cursor()
	c.execute("SELECT * FROM historicoQtdLuz ORDER BY ID DESC LIMIT 1")
	rows = c.fetchall()
	for eachRow in rows:
		luzOld = luz
		luz = eachRow.get('qtdLuz')
		dataTest = dataLuz
		dataLuz = eachRow.get('data')
	#send to web
	if dataLuz != dataTest and dataTest != None:
		print ("Luz mudou: *POST*")
		urlQtdLuz = "https://estufas.000webhostapp.com/api/post_qtd_luz"
		r = requests.post(urlQtdLuz, json={'auth': 'esspassword',"qtdLuz": luz,"date": str(dataLuz)})
		r.status_code
		print(r)
	else:
		print ("LuzLuz não mudou")
	return luz, dataLuz

def qtdAgua(agua, dataAgua):
	conn= pymysql.connect(host='localhost',user='admin',password='esspassword',db='bd-ess',charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
	c = conn.cursor()
	c.execute("SELECT * FROM historicoQtdAgua ORDER BY ID DESC LIMIT 1")
	rows = c.fetchall()
	for eachRow in rows:
		aguaOld = agua
		agua = eachRow.get('qtdAgua')
		dataTest = dataAgua
		dataAgua = eachRow.get('data')
	if dataAgua != dataTest and dataTest != None:
		print ("Quantidade de água no tank mudou: *POST*")
		urlQtdAgua = "https://estufas.000webhostapp.com/api/post_qtd_agua"
		r = requests.post(urlQtdAgua, json={'auth': 'esspassword',"qtdAgua": agua,"date": str(dataAgua)})
		r.status_code
		print(r)
	else:
		print ("Agua não mudou")
	return agua, dataAgua

def postMoisture(moisture, dataMoisture):
	conn= pymysql.connect(host='localhost',user='admin',password='esspassword',db='bd-ess',charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
	c = conn.cursor()
	c.execute("SELECT * FROM historicoMoisture ORDER BY ID DESC LIMIT 1")
	rows = c.fetchall()
	for eachRow in rows:
		moistureOld = moisture
		moisture = eachRow.get('moisture')
		dataTest = dataMoisture
		dataMoisture = eachRow.get('data')
	if dataMoisture != dataTest and dataTest != None:
		print ("Humidade do solo mudou: *POST*")
		urlMoisture = "https://estufas.000webhostapp.com/api/post_moisture"
		r = requests.post(urlMoisture, json={'auth': 'esspassword',"moisture": moisture,"date": str(dataMoisture)})
		r.status_code
		print(r)
	else:
		print ("moisture não mudou")
	return moisture, dataMoisture

def automatizar(janela, pump, lampada):
	urlMoisture = "http://192.168.100.58/api/post_state.php"
	headers = {'autenticar': 'essState'}

	r = requests.post(urlMoisture, json={"lampada": lampada, "pump": pump, "janela": janela}, headers=headers)
	r.status_code
	print("status code debug state")
	print(r)

def saveState():
	data = getState()
	lampada = data["lampada"]
	pump = data["pump"]
	janela = data["janela"]
	urlMoisture = "http://192.168.100.58/api/post_state.php"
	headers = {'autenticar': 'essState'}

	r = requests.post(urlMoisture, json={"lampada": lampada, "pump": pump, "janela": janela}, headers=headers)
	r.status_code
	print("status code debug state")
	print(r)

def getState():
	urlState = 'https://estufas.000webhostapp.com/api/getAtuador'
	headers = {'autenticar': 'atuadorState'}
	r = requests.get(urlState, headers = headers)
	data = r.json()
	print ()
	print ("ESTADO DATA »»»»»»»")
	print (data)
	return data

def saveConfig():
	data = getConfig()
	atualizacao = data["atualizacao"]
	automacao = data["automacao"]
	fotografia = data["fotografia"]
	urlSaveConfig = "http://192.168.100.58/api/post_config.php"
	headers = {'autenticar': 'essConfig'}

	r = requests.post(urlSaveConfig, json={"atualizacao": atualizacao, "automacao": automacao, "fotografia": fotografia}, headers=headers)
	r.status_code
	print("status code debug state")
	print(r)
	return atualizacao, automacao, fotografia

def getConfig():
	urlState = 'https://estufas.000webhostapp.com/api/getConfig'
	headers = {'autenticar': 'essConfig'}
	r = requests.get(urlState, headers = headers)
	data = r.json()
	print ()
	print ("ESTADO DATA »»»»»»»")
	print (data)
	return data

def tirarFoto():
	file_name = "image.jpg"
	print("a capturar imagem...")
	call(["fswebcam", "-S 15" , file_name])
	sleep(1)
	return file_name

def foto(now):
	file_name = tirarFoto()
	paramUrl = 'https://estufas.000webhostapp.com/api/post_image'
	headers = {'autenticar': 'essImage', 'date': str(now)}
	paramFiles = {'image': (file_name, open(file_name, 'rb'), 'image/jpg', {'Expires':'0'})}
	print("a enviar para o serviço Web...")
	r = requests.post(paramUrl, files=paramFiles, headers=headers)
	print(r)

def getTime():
	#Get data e horas
	now = datetime.datetime.now()
	return now



try:  
	print("CTRL+C para terminar")
	while True:

		conectividade = is_connected()
		if conectividade == True:
			
			atualizacao, automacao, fotografia = saveConfig()

				#ENVIO DE VALORES PARA A WEB
			#Get data e horas
			now = datetime.datetime.now()
			#TEMPERATURA E HUMIDADE
			temperatura, humidade, dataTempHumid = tempHumid(temperatura, humidade, dataTempHumid)
			#LUZ
			luz, dataLuz = qtdLuz(luz, dataLuz)
			#AGUA
			agua, dataAgua = qtdAgua(agua, dataAgua)
			#MOISTURE
			moisture, dataMoisture = postMoisture(moisture, dataMoisture)


			if fotografia == 1:
				foto(now)

			if automacao == 1:
					#automação ligada
				#temperatura => janela
				if temperatura > 20:
					janela = 1
					print ("Temperatura -> Janela aberta")
				else:
					janela = 0
					print ("Temperatura -> Janela fechada")
				#humidade do solo => bomba agua
				if moisture < 30:
					pump = 1
					print ("Moisture -> Pump ligada")
				else:
					pump = 0
					print ("Moisture -> Pump desligada")
				#luz => lampada
				if luz < 50:
					lampada = 1
					print ("Luz -> lampada ligada")
				else:
					lampada = 0
					print ("Luz -> lampada desligada")
				automatizar(janela, pump, lampada)

			else:
				print ("Automacao desligada (atuadores a comando da aplicacao web)")
				saveState()

			if atualizacao == 0:
				print("Espera de 5 segundos...")
				sleep(5.0)
			else:
				print("Espera de 30 segundos...")
				sleep(30.0)
		else:
			print("Espera por conectividade (10 segundos)")
			sleep(10.0)
			

except KeyboardInterrupt:
	print("\nPrograma terminado pelo utilizador.")		
#except:
	#print("\nErro!!!")
finally:
	print("ok.")
print("Fim do programa.")

