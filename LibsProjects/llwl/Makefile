########################################################################
#  .--.              .-.   .-.  ########################################
# / _.-' .-.   .-.  | OO| | OO| #   Prototux's LLWL graphics library   #
# \  '-. '-'   '-'  |   | |   | #    2013 - codeoverflow.org/p/llwl    #
#  '--'             '^^^' '^^^' ########################################
########################################################################
#  This program is free software. It comes without any warranty, to    #
# the extent permitted by applicable law. You can redistribute it      #
# and/or modify it under the terms of the Do What The Fuck You Want    #
# To Public License, Version 2, as published by Sam Hocevar. See       #
# http://www.wtfpl.net/ for more details.                              #
########################################################################
# Dat makefile...                                                      #
########################################################################

CC = gcc

NAME = libllwl.a

SRC	= init.c surface.c tools.c

OBJ = $(SRC:.c=.o)

#For OSX, must be changed for linux/bsd
CFLAGS = -I/usr/X11R6/include -O3 -m32

$(NAME)	: all

all:	$(OBJ)
		ar -r $(NAME) $(OBJ)
		ranlib $(NAME)

clean:
		rm -f $(OBJ) $(NAME)

re:		clean all