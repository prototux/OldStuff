' Little demo for that "wrapper"
' There are really people who read that lines?
' Under WTFPLv2 license (http://sam.zoy.org/wtfpl/COPYING)

CON
  _clkmode = xtal1 + pll16x
  _xinfreq = 5_000_000
  cols = vgaOutput#cols
  rows = vgaOutput#rows

OBJ
  vgaOutput : "vga_hires_functions"
  serialInput : "Parallax Serial Terminal"

VAR
  byte inputString[80]

PUB start | i, y
  'Inits
  i := 0
  y := 1
  vgaOutput.start(16)
  serialInput.start(115200)
  repeat
    vgaOutput.setPos(0,0)
    vgaOutput.setPalette(6)
    vgaOutput.putstr(string("Little demo for vga hires text wrapper for chip gracey's vga hires text object. pretty big isn't? :) "))
    if serialInput.RxCount => 1
      serialInput.StrIn(@inputString)
      serialInput.RxFlush
        if y == rows
            y := 1
      vgaOutput.setPalette(0)
      vgaOutput.setPos(0,y)
      vgaOutput.clearLine
      vgaOutput.setPos(0,y++)
      vgaOutput.putstr(@inputString)
    vgaOutput.setPos(0,rows-1)
    vgaOutput.setPalette(6)
    vgaOutput.putstr(string("COUNTER: "))
    vgaOutput.puthex(i++, 8)
    vgaOutput.putstr(string(" Prototux/2012 -- Please feed me with serial data! (this thing can really be used for a BSODomizer! :D)"))
