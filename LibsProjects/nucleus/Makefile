####################
## SFML  Makefile ##
####################

NAME = sfml

CFLAGS = -g3 -Werror -Wno-write-strings -Wno-deprecated -I ./engine
LFLAGS = -g3 -framework SFML -framework OpenGL -framework sfml-window -framework sfml-system -framework sfml-network -framework sfml-audio -framework sfml-graphics -framework sndfile
CC = g++
RM = rm -f

SRC = 	main.cpp \
		engine/Engine.cpp \
		engine/Objects.cpp \
		engine/Audio.cpp

OBJ = $(SRC:.cpp=.o)

all:		$(NAME)

$(NAME):	$(OBJ)
			$(CC) $(LFLAGS) -o $(NAME) $(OBJ)

clean:
			$(RM) $(OBJ)

fclean: 	clean
			$(RM) $(NAME)

re:			fclean all
