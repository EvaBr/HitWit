CC = gcc
CFLAGS = -Wall -pedantic -Werror -std=c99
.c.o:  ; $(CC) -c $(CFLAGS) $<

OBJ = 	readAll.o\
	main.o\

all:  $(OBJ)
	$(CC) $(CFLAGS) -o readDir $(OBJ)  -lm

%.o : %.c
	$(CC) -c $(CFLAGS) $*.c -o $*.o

clean:
	rm $(OBJ)

readAll.o     : readAll.h

main.o        : readAll.h
