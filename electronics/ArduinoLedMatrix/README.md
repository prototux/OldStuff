ArduinoLedMatrix
================

Some led matrix stuff for arduino (based on http://playground.arduino.cc//Main/DirectDriveLEDMatrix ).

## Man ledMatrix-hardware
Connect the LED matrix as http://playground.arduino.cc/uploads/Main/Matrix.gif

## Man ledMatrix-software
See MatrixLib for example. this code doesn't use any timer library, like the DirectDriveLEDMatrix page.

* Basically, you need to clearDisplay() on each loop. to take millis() to a variable, to do your stuff and to call render() for some time. that's what the example code does.
* To change the speed of the display, just change the constant in the loop...
* Warn: leds[][] is stored as [y][x], not [x][y]!
* Warn bis: My LED matrix wasn't col-to-row (anode in row, cathode in col), like the Matrix.gif, but row-to-col, if your LED matrix is col-to-row, just invert the HIGH and LOW in render()

## Exemples (aka. random stuff)
* LedMatrixMessage: display some text, with a left-to-right or right-to-left scroller, or char by char. NB: BITE1 and BITE2 is a dick (in two parts).
* PongMatrix: a basic pong with random player movement.

## TODO:
* Optimise letters (using 1 byte per line instead of 1 byte per pixel)
* Infinite loop on scrollers instead of freeze+return to first char
* Serial messages?
* Using driver IC or demux to reduce pin usage