#!/usr/bin/env bash

FILE="/home/$USER/.steam/steam/steamapps/common/Left 4 Dead 2/left4dead2/cfg/video.txt"

sed -i '12i\    "setting.mat_tonemapping_occlusion_use_stencil" "1"' "$FILE"
echo "Done"
