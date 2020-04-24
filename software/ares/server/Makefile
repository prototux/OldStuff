# Configuration
NAME = aresd
DEBUG = true

# Automatic stuff using the configuration
# Set warning flags and defines from debug
ifeq ($(DEBUG),true)
	WARNS = -Wall -pedantic
	DEFINES = -D DEBUG
	DFLAGS = -g3
	ANALYSIS = true
else
	WARNS =
	DFLAGS =
	ANALYSIS = false
endif

# C Compilation stuff
CC = clang
CFLAGS = -std=gnu99 -fno-caret-diagnostics -fno-diagnostics-show-option -I/usr/include/x86_64-linux-gnu/
CFLAGS += $(DEFINES) $(WARNS) $(DFLAGS)

# Final linking stuff
LDFLAGS = -ljson-c -lasyncd -lssl -lqlibc -lpthread -levent -levent_pthreads -levent_openssl -lssl -lcrypto -ldl

# Static analysis stuff
CHECK = cppcheck
CHECK_VERBOSITY = --suppress=unusedVariable --enable=warning,performance
CKFLAGS = $(CHECK_VERBOSITY) --language=c --std=c99 --template='\t[{severity}] Line {line}: {message}' $(DEFINES) -q

# Color... it's better to read with them.
CRED =		$(shell echo "\033[0;31m")
CGREEN =	$(shell echo "\033[0;32m")
CYELLOW =	$(shell echo "\033[0;33m")
CBLUE =		$(shell echo "\033[0;34m")
CMAGENTA =	$(shell echo "\033[0;35m")
CCYAN =		$(shell echo "\033[0;36m")
CEND =		$(shell echo "\033[0m")

# Error/warning highlight command for Clang and CppCheck
# Note: it also re-format Clang wmessages to be easier to read,
# CppCheck formating is already set in it's --template argument
HIGHLIGHT_START =  2>&1 | sed
HIGHLIGHT_COLOR = -e "s/\[error\]/\[$(CRED)error$(CEND)\]/g" -e "s/\[style\]/\[$(CYELLOW)style$(CEND)\]/g" -e "s/\[warning\]/\[$(CYELLOW)warning$(CEND)\]/g" -e "s/\[note\]/\[$(CCYAN)note$(CEND)\]/g"
CCHIGHLIGHT = $(HIGHLIGHT_START) -e "/^\(.*\)In function \(.*\):/d" -e "s/^\(.*\):\([0-9]\+\):\([0-9]\+\): \(warning\|error\|note\): \(.*\)/\t\[\4\] Line \2: \5/g" $(HIGHLIGHT_COLOR)
CKHIGHLIGHT = $(HIGHLIGHT_START) -e "/^\(.*\)Please report this to Cppcheck developers/d" $(HIGHLIGHT_COLOR)
LDHIGHLIGHT = $(HIGHLIGHT_START)  -e "/^\(.*\)no input files/d" -e "s/^\(.*\): \(warning\|error\|note\): \(.*\)/\t\[\2\]: \3/g" $(HIGHLIGHT_COLOR)

# Make the source files list
SRC = $(wildcard src/*.c) #example

# Make the objects files list, from the sources
OBJ = $(SRC:.c=.o)

# Make methods
ifeq ($(DEBUG),true)
all:		infos fclean $(NAME) run
else
all:		infos $(NAME)
endif

infos:
			@echo "$(CCYAN)Building $(NAME) (debug=$(DEBUG)) $(CEND)"

$(NAME):	$(OBJ)
			@echo "$(CCYAN)LD $(NAME) $(CEND)"
			@$(CC) $(LDFLAGS) -o $(NAME) $(OBJ) $(LDHIGHLIGHT)


%.o:		%.c
			@echo "$(CGREEN)CC $< $(CEND)"
			@$(CC) $(CFLAGS) -o $@ -c $< $(CCHIGHLIGHT)
ifeq ($(ANALYSIS),true)
			@$(CHECK) $(CKFLAGS) $< $(CKHIGHLIGHT)
endif

clean:
			@echo "$(CRED)RM *.o $(CEND)"
			@rm -f $(OBJ) $(ASMOBJ)

fclean:		clean
			@echo "$(CRED)RM $(NAME) $(CEND)"
			@rm -f $(NAME)

re:			fclean all

ifeq ($(DEBUG),true)
run:
			@echo "$(CMAGENTA)EX $(NAME) $(CEND)"
			@echo "$(CYELLOW)Everything below is $(NAME)'s debug messages: $(CEND)"
			@./$(NAME)
else
run:
			@echo "$(CRED)Cannot run if DEBUG is set to false! $(CEND)"
endif

.PHONY: all clean fclean re run
