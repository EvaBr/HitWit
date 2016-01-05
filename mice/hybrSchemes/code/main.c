#include <stdio.h>
#include <stdlib.h>
#include "readAll.h"
#include <string.h>
//#include <regex.h>


int main ( int argc, char * argv[] ) {
/*	bool read = true;
	bool dir = true;
	char *readdir = READ_DIRECTORY;
	if (argc>=2) {
		//char *file = argv[0];
		if (strstr(argv[0], ".xls")!=NULL) {
			read = false;
			dir = false;
			const char *namesfile = argv[0];
		} else if (strstr(argv[0], ".txt")!=NULL) {
			dir = false;
			const char *namesfile = argv[0];
		} else { readdir = argv[0]; }

	}

	if (dir) {*/
		/* namesfile is a .txt, to where names of all files to be analyzed will be written */
		const char *namesfile = "names.csv";
		printf ( "Reading directory \t %s \n", READ_DIRECTORY); //readdir );

		/* readNames function writes names of all the files that are to be analyzed into the given .txt file */
		readNames ( namesfile );//, readdir);
	//}

	//python? ########################################################
	/* now analyze al the xls files you found */
/*	FILE *fp;
	fp = fopen ( namesfile, "r" );
	if (fp==NULL) {
		printf ( "Can't read name-file   %s!\n", fileNames );
		exit(EXIT_FAILURE);
	}
	while(line):
		openfile(line, 'r')
		popravi na pravo obliko, organiziraj, parse
*/
	/* close file mit names */
//	fclose(fp);
	//################################################################
	printf("Done :) \n");
	return 0;
}
