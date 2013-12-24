/*
  Voltmeter
  Voltmeter base on voltage divider concept.

  by Clarence's Wicked Mind - news from Clarence, stuff that doesn't matter
  Code based on: http://www.clarenceho.net:8123/blog/articles/2009/05/17/arduino-test-voltmeter
  Coded by: arduinoprojects101.com
  
  Updated by Martin Lorton (http://mjlorton.com and http://www.youtube.com/mjlorton )for use in 
  Arduino tutorial series: https://www.youtube.com/playlist?list=PLF86F263013F106C0
  
  Updated by Kyle Klaus for Dad's Christmas gift :)
*/

// Include and setup the LCD Screen
#include <LiquidCrystal.h>
LiquidCrystal lcd(8, 9, 4, 5, 6, 7);

// Define the meter parameters
float R1 = 1000000;        // measured value of R1
float R2 = 320000;         // measured value of R2
int sensorPin = A1;        // analog pin for meter
int refreshSpeed = 500;   // refresh rate of application

// ~~~ Shouldn't need to adjust anything below this line ~~~ //

// Application variables
int sensorValue;
float vRead;
float vAvg;
float vMin;
float vMax;
int cSamples;
int show;

// define some values used by the panel and buttons
int lcd_key     = 0;
int adc_key_in  = 0;
#define btnRIGHT  0
#define btnUP     1
#define btnDOWN   2
#define btnLEFT   3
#define btnSELECT 4
#define btnNONE   5

// read the buttons
int read_LCD_buttons() {
 adc_key_in = analogRead(0);      // read the value from the sensor
 // my buttons when read are centered at these valies: 0, 144, 329, 504, 741
 // we add approx 50 to those values and check to see if we are close
 if (adc_key_in > 1000) return btnNONE; // We make this the 1st option for speed reasons since it will be the most likely result
 if (adc_key_in < 50)   return btnRIGHT; 
 if (adc_key_in < 195)  return btnUP;
 if (adc_key_in < 380)  return btnDOWN;
 if (adc_key_in < 555)  return btnLEFT;
 if (adc_key_in < 790)  return btnSELECT;  
 return btnNONE;  // when all others fail, return this...
}

void setup() {
  // Startup the LCD display
  lcd.begin(16, 2);
  //Serial.begin(115200);
  
  // Setup applicaiton variables
  sensorValue = 0;
  vRead = 0.00;
  vAvg = 0.00;
  vMin = 0.00;
  vMax = 0.00;
  cSamples = 0;
  show = 0;
}

void loop() { 
   // read the value on analog input
  sensorValue = analogRead(sensorPin);

  // Throw an error if we're above max
  if (sensorValue >= 1023) {
    lcd.clear();
    lcd.println("     MAX");
    delay(refreshSpeed);
    return;
  }
  
  // Throw an error if we're below min
  else if (sensorValue < 0) {
    lcd.clear();
    lcd.println("     MIN");
    delay(refreshSpeed);
    return;
  }

  // Count samples and time for averages
  cSamples = cSamples + 1;  
  
  // Do all the required calculations
  float vRead = sensorValue * (20.625 / 1023.0);
  if (vMax < vRead) {vMax = vRead;}
  if (vMin > vRead) {vMin = vRead;}
  vAvg = (vAvg * (cSamples - 1) + vRead) / cSamples;
  
  // Display!
  lcd.clear();
  lcd.home();
  
  // Show the current reading
  lcd.print(vRead);
  lcd.print(" Volts");
  
  // Check the buttons
  lcd_key = read_LCD_buttons();  // read the buttons
  switch (lcd_key) {
   case btnUP: {
     show = show + 1;
     if (show > 4) {show = 0;}
     break;
     }
   case btnDOWN: {
     show = show - 1;
     if (show < 0) {show = 4;}
     break;
     }
 }  
  
  // Show the Avg
  if (show == 0) {
    lcd.setCursor(0, 1);
    lcd.print("Average: ");
    lcd.print(vAvg);
    lcd.print("V");
  }
  
  // Show the Avg
  if (show == 1) {
    lcd.setCursor(0, 1);
    lcd.print("Min: ");
    lcd.print(vMin);
    lcd.print("V");
  }
  
  // Show the Avg
  if (show == 2) {
    lcd.setCursor(0, 1);
    lcd.print("Max: ");
    lcd.print(vMax);
    lcd.print("V");
  }
  
  // Show the Avg
  if (show == 3) {
    lcd.setCursor(0, 1);
    lcd.print("Time: ");
    lcd.print(millis()/1000);
    lcd.print(" sec");
  }  
  
  // Show the Avg
  if (show == 4) {
    lcd.setCursor(0, 1);
    lcd.print("Samples: ");
    lcd.print(cSamples);
  }   
  
  // Sleep...
  delay(refreshSpeed); 
}
