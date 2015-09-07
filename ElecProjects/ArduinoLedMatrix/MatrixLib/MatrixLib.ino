/***********************************************************************
*  .--.              .-.   .-.  ****************************************
* / _.-' .-.   .-.  | OO| | OO| * Prototux's Arduino LED matrix helper *
* \  '-. '-'   '-'  |   | |   | *2013| github/prototux/ArduinoLedMatrix*
*  '--'             '^^^' '^^^' ****************************************
************************************************************************
*  This program is free software. It comes without any warranty, to    *
* the extent permitted by applicable law. You can redistribute it      *
* and/or modify it under the terms of the Do What The Fuck You Want    *
* To Public License, Version 2, as published by Sam Hocevar. See       *
* http://www.wtfpl.net/ for more details.                              *
************************************************************************
* The simplest example possible...                                     *
***********************************************************************/

byte leds[8][8];
int pins[17]= {-1, 5, 4, 3, 2, 14, 15, 16, 17, 13, 12, 11, 10, 9, 8, 7, 6};
int cols[8] = {pins[13], pins[3], pins[4], pins[10], pins[06], pins[11], pins[15], pins[16]};
int rows[8] = {pins[9], pins[14], pins[8], pins[12], pins[1], pins[7], pins[2], pins[5]};

void setup()
{
  for (int i = 1; i <= 16; i++)
    pinMode(pins[i], OUTPUT);
  for (int i = 0; i <= 7; i++)
    digitalWrite(cols[i], LOW);
  for (int i = 0; i <= 7; i++)
    digitalWrite(rows[i], LOW);
}

void loop()
{
  int emillis = millis();
  clearDisplay();
  
  //Your code here
  setPixel(5,5,1);

  while (millis() - emillis <= 75)
    render();
}

void clearDisplay()
{
  for (int i = 0; i < 8; i++)
    for (int j = 0; j < 8; j++)
      leds[i][j] = 0;
}

void setPixel(int x, int y, int value)
{
  leds[y][x] = value;
}

void render()
{
  for (int y = 0; y < 8; y++)
  {
    digitalWrite(rows[y], HIGH);
    for (int x = 0; x < 8; x++)
    {
      digitalWrite(cols[x], (leds[y][x])? LOW : HIGH);
      digitalWrite(cols[x], HIGH);
    }
    digitalWrite(rows[y], LOW);
  }
}

