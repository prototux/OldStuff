#!/bin/bash

gconftool-2 -s -t string /apps/guake/style/background/color '{{>background}}'
gconftool-2 -s -t string /apps/guake/style/font/color '{{>foreground}}'
gconftool-2 -s -t string /apps/guake/style/font/palette '{{>0}}:{{>1}}:{{>2}}:{{>3}}:{{>4}}:{{>5}}:{{>6}}:{{>7}}:{{>8}}:{{>9}}:{{>10}}:{{>11}}:{{>12}}:{{>13}}:{{>14}}:{{>15}}'
