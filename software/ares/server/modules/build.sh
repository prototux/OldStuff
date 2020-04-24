#!/bin/zsh
gcc -c -fPIC mod_dir.c -o mod_dir.o && gcc -shared -Wl,-soname,mod_dir.so.1 -o mod_dir.so.1.0.1  mod_dir.o
