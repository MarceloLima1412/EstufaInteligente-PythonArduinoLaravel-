#include <SPI.h>
#include <ESP8266WiFi.h>
#include <DHT.h>
#include <time.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>

//Networking

char ssid[] = "joel-laptop";
char pass[] = "esspassword";



//time
int timezone = 1 * 3600;
int dst = 0;

//    POST - Dados Móveis
//=======================================================================
#define URLPostTempHumid   "http://192.168.100.58/api/post_temp_humid.php"
#define URLPostMoisture    "http://192.168.100.58/api/post_moisture.php"
#define URLPostQtdAgua     "http://192.168.100.58/api/post_qtd_agua.php"
#define API_PASSWORD    "esspassword"

//    Sensor Humidade e temperatura
//=======================================================================
#define DHTPIN 2
#define DHTTYPE DHT11

//    Humidade solo
//=======================================================================
#define SECO 550
#define MOLHADO 160
int sensor_pin = A0; 
int moisture_value ;

//    Nivel de água
//=======================================================================
const int echoPin = D7;  //D7
const int trigPin = D8;  //D8
long duration;
int distance;
int agua;
#define CHEIO 3
#define VAZIO 25

//    Protótipos
//=======================================================================
String getTime();
void postTempHumid(String temperatura, String humidade, String dataHoraAtual);
void postMoisture(String moisture, String dataHoraAtual);
void postQtdAgua(String dataHoraAtual);
void nivelAgua();

DHT dht(DHTPIN, DHTTYPE);


//***********************************************************************
//***************                 SETUP                   ***************
//***********************************************************************
void setup() {
  Serial.begin(9600);
  dht.begin();

  //Wifi
  WiFi.begin(ssid, pass);
  Serial.println("");
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
   Serial.println(WiFi.macAddress());

   //Time
  configTime(timezone, dst, "pool.ntp.org","time.nist.gov");
  Serial.println("\nWaiting for Internet time");
  while(!time(nullptr)){
     Serial.print("*");
     delay(1000);
  }
  Serial.println("\nTime response....OK");  

  //Nivel água
  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Inpu

  Serial.println("Fim da inicialização...");
  delay(10000);

}

//***********************************************************************
//***************                  LOOP                   ***************
//***********************************************************************

void loop() {

  //READ DATA
  
      //AGUA
  nivelAgua();
  agua = map(distance, VAZIO, CHEIO, 0, 100);
  
      //TEMPERATURA HUMIDIDADE
  float humidade = dht.readHumidity();
  float temperatura = dht.readTemperature();
  String strHumidade = String(humidade);
  String strTemperatura = String(temperatura);
  
      //MOISTURE
  moisture_value= analogRead(sensor_pin);
  moisture_value = map(moisture_value,SECO,MOLHADO,0,100);
  String strMoisture = String(moisture_value);
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("Conecção Wifi: OK!!");
    //  TIME
    //===============================
    String dataHoraAtual = getTime();
    
    //  POST DATA TO RPI DATABASE
    //========================================================
    postTempHumid(strTemperatura, strHumidade, dataHoraAtual);
    postMoisture(strMoisture, dataHoraAtual);
    postQtdAgua(dataHoraAtual);

  } else {
    Serial.println("Conecção Wifi: ERRO!!");
  }

  delay(5000);
}


//***********************************************************************
//***************                FUNCOES                  ***************
//***********************************************************************

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

//    Agua
void nivelAgua(){
  // Clears the trigPin
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  
  // Sets the trigPin on HIGH state for 10 micro seconds
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  
  // Reads the echoPin, returns the sound wave travel time in microseconds
  duration = pulseIn(echoPin, HIGH);
  
  // Calculating the distance
  distance= duration*0.034/2;
  // Prints the distance on the Serial Monitor
  Serial.print("Distance: ");
  Serial.println(distance);
}

//    POSTS
//==============================================================================

//  Temperatura e Humidade
void postTempHumid(String temperatura, String humidade, String dataHoraAtual){

  String jsonCode = "?";
  Serial.println("A enviar dados (temperatura e humidade)");
  
  jsonCode = "{\"auth\": \"";
  jsonCode += API_PASSWORD;
  jsonCode += "\", \"key\": \"temperatura\", \"temperatura\": \"";
  jsonCode += temperatura;
  jsonCode += "\", \"humidade\": \"";
  jsonCode += humidade;
  jsonCode += "\", \"date\": \"";
  jsonCode += dataHoraAtual;
  jsonCode += "\"}";

  HTTPClient http;
  http.begin(URLPostTempHumid);      //Specify request destination
  http.addHeader("Content-Type", "application/json");  //Specify content-type header
  int httpCode = http.POST(jsonCode);   //Send the request
  String payload = http.getString();                                        //Get the response payload
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
  http.end();
}

//  Moisture
void postMoisture(String moisture, String dataHoraAtual){

  String jsonCode = "?";
  Serial.println("A enviar dados (temperatura e humidade)");
  
  jsonCode = "{\"auth\": \"";
  jsonCode += API_PASSWORD;
  jsonCode += "\", \"key\": \"moisture\", \"moisture\": \"";
  jsonCode += moisture;
  jsonCode += "\", \"date\": \"";
  jsonCode += dataHoraAtual;
  jsonCode += "\"}";

  HTTPClient http;
  http.begin(URLPostMoisture);      //Specify request destination
  http.addHeader("Content-Type", "application/json");  //Specify content-type header
  int httpCode = http.POST(jsonCode);   //Send the request
  String payload = http.getString();                                        //Get the response payload
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
  http.end();
}

void postQtdAgua(String dataHoraAtual){

  String strAgua = String(agua);
  
  String jsonCode = "?";
  Serial.println("A enviar dados (distância água)");
  
  jsonCode = "{\"auth\": \"";
  jsonCode += API_PASSWORD;
  jsonCode += "\", \"key\": \"qtdAgua\", \"qtdAgua\": \"";
  jsonCode += strAgua;
  jsonCode += "\", \"date\": \"";
  jsonCode += dataHoraAtual;
  jsonCode += "\"}";

  HTTPClient http;
  http.begin(URLPostQtdAgua);      //Specify request destination
  http.addHeader("Content-Type", "application/json");  //Specify content-type header
  int httpCode = http.POST(jsonCode);   //Send the request
  String payload = http.getString();                                        //Get the response payload
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
  http.end();
}
