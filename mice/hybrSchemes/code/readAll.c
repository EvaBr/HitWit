#include <stdio.h>
#include <stdlib.h>
#include <dirent.h>
#include "readAll.h"
#include <string.h>

int readNames ( const char *fileNames ) {
	FILE *fp;
        fp = fopen ( fileNames, "w" );
        if (fp==NULL) {
                printf ( "Can't open name-file   %s!\n", fileNames );
                exit(EXIT_FAILURE);
        }


	DIR *dir;
	int len;
	struct dirent *ent;
	if ((dir=opendir(READ_DIRECTORY))!=NULL) {
		/* print all the files and directories within directory */
		while ((ent=readdir(dir))!=NULL) {
			len = strlen(ent->d_name)-4;
			if (strstr((ent->d_name+len), ".xls")!=NULL) {
				fprintf ( fp, "%s\n", ent->d_name );
			}
		}
		closedir ( dir );
		fclose ( fp );
		return EXIT_SUCCESS;
	} else {
		/* could not open directory */
		perror ( "Could not open Directory!\n" );
		fclose ( fp );
		return EXIT_FAILURE;
	}
}

int rearrange ( const char *file) {
	FILE *fp;
	fp = fopen ( file, "r" );
	if (fp==NULL) {
		printf ( "Analysis failed: can't read file  %s!\n", file );
		exit(EXIT_FAILURE);
	}

	//ustvari pravo ime
	//open file wih that name
	//write into file one table
	return EXIT_SUCCESS;
}
