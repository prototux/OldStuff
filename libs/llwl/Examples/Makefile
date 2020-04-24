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
# make SRC=file.c to compile other examples...                         #
########################################################################

NAME = example

CFLAGS = -Wall -I../ -I/usr/X11/include -m32 --std=c99
LDFLAGS = -L../ -L/usr/X11/lib -m32 -lllwl -lX11 -lXext

CC = gcc
RM = rm -f

SRC = noise.c

OBJ = $(SRC:.c=.o)

all:		$(NAME)

$(NAME):	$(OBJ)
			$(CC) $(LDFLAGS) -o $(NAME) $(OBJ)

clean:
			$(RM) $(OBJ)

fclean: 	clean
			$(RM) $(NAME)

re:			fclean all