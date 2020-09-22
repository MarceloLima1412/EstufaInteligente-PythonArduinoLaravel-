#include <ESP8266WiFi.h>
#include <Servo.h>
#include <time.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>

//Network
//=========================
char ssid[] = "joel-laptop";
char pass[] = "esspassword";

//    Time
//=========================HIGH
int timezone = 1 * 3600;
int dst = 0;

//    Lampada
//=========================
#define LAMPADA D1

//    Pump
//=========================
#define BOMBA D0

//    States
//=========================
int lampada_state = 0;
int pump_state = 0;
int janela_state = 0;

Servo servo;

//***************               PROTÓTIPOS                ***************
//***********************************************************************
String getTime();
void postQtdLuz(String qtdLuz, String dataHoraAtual);
void acenderLampada();
float luz();
void abrirJanela();
void fecharJanela();
void getState();
void ligarBomba();
void desligarBomba();


//    POST - Dados Móveis
//=======================================================================
#define URLPostQtdLuz      "http://192.168.100.58/api/post_qtd_luz.php"
#define URLGetState       "http://192.168.100.58/api/get_state.php"
#define API_PASSWORD    "esspassword"  


void setup() {
  Serial.begin(9600);
  pinMode(LAMPADA, OUTPUT);

  //Wifi
  WiFi.begin(ssid, pass);
  Serial.println("Waiting for connection");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(F("."));
  }
  Serial.println("");
  Serial.println("Connected");
  Serial.print("IP Address ---> ");
  Serial.println(WiFi.localIP() );
  Serial.print("MAC Address ---> ");
  Serial.println(WiFi.macAddress() );

  //Time
  configTime(timezone, dst, "pool.ntp.org","time.nist.gov");
  Serial.println("\nWaiting for Internet time");
  while(!time(nullptr)){
     Serial.print("*");
     delay(1000);
  }
  Serial.println("\nTime response....OK");  

  
  //Servo
  servo.attach(D2); //D2
  servo.write(0);

  //Bomba
  pinMode(BOMBA, OUTPUT);
  analogWrite(BOMBA, 0); 
  
  pinMode(D5, OUTPUT);
  analogWrite(D5, HIGH);
  
  delay(2000);

}

void loop() {   // Always according to the same datasheet, both pins should be low.

  

  if (WiFi.status() == WL_CONNECTED) {

   Serial.println("Conecção Wifi: OK!!");
  
   
   //getTime
   String dataHoraAtual = getTime();

   float voltagemLuz = luz();
   float qtdLuz = map(voltagemLuz, 0, 5, 0, 100);
   Serial.print("Quantidade de Luz: ");
   Serial.println(qtdLuz);
   String strQtdLuz = String(qtdLuz);
   
   postQtdLuz(strQtdLuz, dataHoraAtual);

    getState();

   if (lampada_state == 1) {
    Serial.println("Acender a lampada");
    acenderLampada();
   } else {
    Serial.println("Apagar a lampada");
    apagarLampada();
   }
   if (pump_state == 1) {
    Serial.println("Ligar bomba de água");
    ligarBomba();
   }else{
    Serial.println("Desligar a bomba de água");
    desligarBomba();
   }
   if (janela_state == 1) {
    Serial.println("Abrir a janela");
    abrirJanela();
   }else{
    Serial.println("Fechar a janela");
    fecharJanela();
   }


    Serial.println("A testar o servo...");
    //abrirJanela();
    
  } else {
    Serial.println("Conecção Wifi: ERRO!!");
  }

  delay(10000);

}


String getTime(){
  String dataHoraAtual = "?";
  time_t now = time(nullptr);
  struct tm* p_tm = localtime(&now);

  //String
  dataHoraAtual = p_tm->tm_year + 1900;
  dataHoraAtual += "-";
  if((p_tm->tm_mon + 1)<10){
    dataHoraAtual += "0";
  }
  dataHoraAtual += p_tm->tm_mon + 1;
  dataHoraAtual += "-";
  if((p_tm->tm_mday)<10){
    dataHoraAtual += "0";
  }
  dataHoraAtual += p_tm->tm_mday;
  dataHoraAtual += " ";
  if((p_tm->tm_hour)<10){
    dataHoraAtual += "0";
  }
  dataHoraAtual += p_tm->tm_hour;
  dataHoraAtual += ":";
  if((p_tm->tm_min)<10){
    dataHoraAtual += "0";
  }
  dataHoraAtual += p_tm->tm_min;
  dataHoraAtual += ":";
  if((p_tm->tm_sec)<10){
    dataHoraAtual += "0";
  }
  dataHoraAtual += p_tm->tm_sec;
  
  return dataHoraAtual;
}


void acenderLampada(){
   digitalWrite(LAMPADA, LOW); //Set the pin to HIGH (3.3V)
   Serial.println("Ligada");              
}

void apagarLampada(){
   digitalWrite(LAMPADA, HIGH); //Set the pin to HIGH (3.3V)
   Serial.println("Desligada");              
}

float luz(){
  int sensorValue = analogRead(A0);   // read the input on analog pin 0
  Serial.print("Teste de analog read do LDR: ");
  Serial.print(sensorValue);
  float voltagemLuz = sensorValue * (5.0 / 1023.0);   // Convert the analog reading (which goes from 0 - 1023) to a voltage (0 - 5V)

  return voltagemLuz;
}

void abrirJanela(){
    servo.write(90);
    Serial.println("Janela aberta");
}

void fecharJanela(){
  servo.write(0);
  Serial.println("Janela fechada");
}

//***********************************************************************
//***************                  POST                   ***************
//***********************************************************************
void postQtdLuz(String qtdLuz, String dataHoraAtual){
  
  String jsonCode = "?";
  Serial.println("A enviar dados (voltagem luz)");
  
  jsonCode = "{\"auth\": \"";
  jsonCode += API_PASSWORD;
  jsonCode += "\", \"key\": \"qtdLuz\", \"qtdLuz\": \"";
  jsonCode += qtdLuz;
  jsonCode += "\", \"date\": \"";
  jsonCode += dataHoraAtual;
  jsonCode += "\"}";

  
  HTTPClient http;

  http.begin(URLPostQtdLuz);      //Specify request destination
  http.addHeader("Content-Type", "application/json");  //Specify content-type header

  int httpCode = http.POST(jsonCode);   //Send the request
  String payload = http.getString();                                        //Get the response payload
 
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
 
  http.end();

}

void getState(){
  
  Serial.println("Get STATE");
  HTTPClient http;

  http.begin(URLGetState);      //Specify request destination
  http.addHeader("autenticar", "essState");  //Specify content-type header

  int httpCode = http.GET();   //Send the request
  String payload = http.getString();                                        //Get the response payload
 
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
 


const size_t capacity = JSON_OBJECT_SIZE(3) + 30;
DynamicJsonDocument doc(capacity);

deserializeJson(doc, payload);

lampada_state = doc["lampada"]; // 1
pump_state = doc["pump"]; // 0
janela_state = doc["janela"]; // 1

Serial.println(payload);

  http.end();
  

}

void ligarBomba(){
  analogWrite(D5, 255);
  Serial.println();
  Serial.println();
  Serial.println();
  Serial.println();
  Serial.println("Supostamente HIGH");
  Serial.println();
  Serial.println();
  Serial.println();
  Serial.println();
}

void desligarBomba(){
  analogWrite(D5, 0);
    Serial.println();
  Serial.println();
  Serial.println();
  Serial.println();
  Serial.println(BOMBA);
  Serial.println("Supostamente LOW");
  Serial.println();
  Serial.println();
  Serial.println();
  Serial.println();
}



 
