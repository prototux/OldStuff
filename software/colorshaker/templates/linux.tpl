#!/bin/sh
if [ "$TERM" = "linux" ]; then
  /bin/echo -e "
  \e]P0{{>0}}
  \e]P1{{>1}}
  \e]P2{{>2}}
  \e]P3{{>3}}
  \e]P4{{>4}}
  \e]P5{{>5}}
  \e]P6{{>6}}
  \e]P7{{>7}}
  \e]P8{{>8}}
  \e]P9{{>9}}
  \e]PA{{>10}}
  \e]PB{{>11}}
  \e]PC{{>12}}
  \e]PD{{>13}}
  \e]PE{{>14}}
  \e]PF{{>15}}
  "
  # get rid of artifacts
  clear
fi
