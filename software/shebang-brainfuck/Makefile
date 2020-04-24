#SBBF Makefile
NAME=sbbf

CC=gcc
CFLAGS= -std=c99 -g3 -O3 -Wall -pedantic

SRC = main.c

OBJ = $(SRC:.c=.o)

all: $(NAME)

$(NAME):	$(OBJ)
			$(CC) -o $(NAME) $(OBJ)

clean:
			rm -f $(NAME) $(OBJ)

install:
			cp ./$(NAME) /usr/local/bin/$(NAME)

re: clean all

.PHONY: all clean re install