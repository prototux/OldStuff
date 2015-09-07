' Little spin "wrapper" for chip gracey's vga hires text object
' Some functions (put*) are based on vga text 32*15 by chip gracey
' Under WTFPLv2 license (http://sam.zoy.org/wtfpl/COPYING)
CON
  _clkmode = xtal1 + pll16x
  _xinfreq = 5_000_000

  cols = driver#cols
  rows = driver#rows
  chrs = cols * rows

OBJ
  driver : "vga_hires_text"

VAR
  'sync long - written to -1 by VGA driver after each screen refresh
  long  sync
  'screen buffer - could be bytes, but longs allow more efficient scrolling
  long  screen[cols*rows/4]
  'row colors
  word  colors[rows]
  word  palette[8]
  'cursor control bytes
  byte  cx0, cy0, cm0, cx1, cy1, cm1
  'position
  long col, row

PUB start(startPin) | i, j

  'Init driver
  driver.start(startPin, @screen, @colors, @cx0, @sync)
  col := 0
  row := 0

  'Init colors and palette
  palette[0] := %%3000_3330 'white on red               error style
  palette[1] := %%0000_0300 'green on black             terminal style
  palette[2] := %%1100_3300 'yellow on gold             bling bling style
  palette[3] := %%0020_3330 'white on blue              hello microsoft!
  palette[4] := %%3330_0000 'black on white             terminal style
  palette[5] := %%0000_3330 'white on black             terminal style
  palette[6] := %%1330_0000 'black on ocean             need some palmtrees
  palette[7] := %%0020_3300 'yellow on blue             warning style
  repeat i from 0 to rows - 1
    colors[i] := palette[5]
  repeat i from 0 to chrs - 1
    putchar(32)

  'Init position
  setPos(0,0)

  'Ini... disabling cursors
  cm0 := %000
  cm1 := %000

PUB stop
  driver.stop

PUB clearLine | i
  setCol(0)
  repeat i from 0 to cols-2
    putchar(32)
  setCol(0)

PUB putstr(stringptr)
  repeat strsize(stringptr)
    putchar(byte[stringptr++])

PUB putdec(value) | i
  if value < 0
    -value
    putchar("-")
  i := 1_000_000_000
  repeat 10
    if value => i
      putchar(value / i + "0")
      value //= i
      result~~
    elseif result or i == 1
      putchar("0")
    i /= 10

PUB puthex(value, digits)
  value <<= (8 - digits) << 2
  repeat digits
    putchar(lookupz((value <-= 4) & $F : "0".."9", "A".."F"))

PUB putbin(value, digits)
  value <<= 32 - digits
  repeat digits
    putchar((value <-= 1) & 1 + "0")

PUB setCol(x)
  col := x

PUB setRow(y)
  row := y

PUB setPos(x, y)
  col := x
  row := y

PUB setPalette(id)
  setColor(palette[id])

PUB setColor(color)
  colors[row] := color

PUB putchar(c)
  screen.byte[row * cols + col++] := c
  if col == cols
    col := 0
    if (++row) == rows
      row := 0
