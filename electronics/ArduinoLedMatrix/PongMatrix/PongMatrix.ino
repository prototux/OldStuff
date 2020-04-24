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
* A basic PONG game                                                    *
***********************************************************************/
byte leds[8][8];
int pins[17]= {-1, 5, 4, 3, 2, 14, 15, 16, 17, 13, 12, 11, 10, 9, 8, 7, 6};
int cols[8] = {pins[13], pins[3], pins[4], pins[10], pins[06], pins[11], pins[15], pins[16]};
int rows[8] = {pins[9], pins[14], pins[8], pins[12], pins[1], pins[7], pins[2], pins[5]};

// Pong variables
int p1y = 0;
int p2y = 0;
int bx = 4;
int by = 2;
int dir = 1;
unsigned long emillis = 0;

void setup()
{
  for (int i = 1; i <= 16; i++)
    pinMode(pins[i], OUTPUT);
  for (int i = 0; i < 8; i++)
    digitalWrite(cols[i], LOW);
  for (int i = 0; i < 8; i++)
    digitalWrite(rows[i], HIGH);
}


void drawPlayers()
{
  //Player 1
  setPixel(0, p1y-1,1);
  setPixel(0, p1y,1);
  setPixel(0, p1y+1,1);
  
  //Player 2
  setPixel(7, p2y-1,1);
  setPixel(7, p2y,1);
  setPixel(7, p2y+1,1);
}

void loop()
{
  emillis = millis();
  
  clearDisplay();
  
  //Random players;
  p1y += random(-4,4);
  if (p1y <= 1)
    p1y = 1;
  else if (p1y >= 6)
    p1y = 6;
  p2y += random(-4,4);
  if (p2y <= 1)
    p2y = 1;
  else if (p2y >= 6)
    p2y = 6;

 
  //Move the ball
  if (dir == 1) //NW
  {
    bx += 1;
    by -= 1;
  }
  else if (dir == 2) //SW
  {
    bx += 1;
    by += 1;
  }
  else if (dir == 3) //NE
  {
    bx -= 1;
    by -= 1;
  }
  else if (dir == 4) //SE
  {
    bx -= 1;
    by += 1;
  }


  //Walls collision detection
  if (bx < 0)
  {
    if (dir == 3)
      dir = 1;
    else if (dir == 4)
      dir = 2;
      
    bx = 1;
  }
  else if (bx > 7)
  {
    if (dir == 1)
      dir = 3;
    else if (dir == 2)
      dir = 4;
    bx = 6;
  }
  if (by < 0)
  {
    if (dir == 1)
      dir = 2;
    else if (dir == 3)
      dir = 4;
    by = 1;
  }
  else if (by > 7)
  {
    if (dir == 2)
      dir = 1;
    else if (dir == 4)
      dir = 3;
    by = 6;
  }
  
  //Players collision detection
  if (bx == 0 && by >= p1y-1 && by <= p1y+1)
  {
    if (dir == 3)
      dir = 1;
    else if (dir == 4)
      dir = 2;
      
    bx = 1;
  }
   if (bx == 7 && by >= p1y-1 && by <= p1y+1)
  {
    if (dir == 1)
      dir = 3;
    else if (dir == 2)
      dir = 4;
    bx = 6;
  }
  setPixel(bx, by, HIGH);
  drawPlayers();

  while (millis() - emillis <= 175)
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
