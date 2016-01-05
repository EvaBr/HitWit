setwd("/home/eva/Desktop/HelmHoltz/mice/hybrSchemes/hybridis.schemes/hybridisation")
library("gtools", lib.loc="~/R/x86_64-pc-linux-gnu-library/3.0")
library("gdata", lib.loc="~/R/x86_64-pc-linux-gnu-library/3.0")

source('~/Desktop/HelmHoltz/mice/hybrSchemes/hybridis.schemes/helper.r')
source('~/Desktop/HelmHoltz/mice/hybrSchemes/hybridis.schemes/posthelper.r')

toAnalyze <- read.table('../../code/results/names.csv', header = FALSE, sep = "\n", as.is = TRUE, col.names = "files")
namesPart2 <- as.array(strsplit(toAnalyze$files, "[ ]*hyb.*"))
rownames( namesPart2 ) <- toAnalyze$files

unsuccess <- "TODO: Reading Failed in:"
additional <- "NA"

for (currentFile in toAnalyze[2:6,1]) {
  print(paste("Reading file: ", currentFile, sep = " "))
  dat <- as.matrix(read.xls(xls = currentFile, as.is=TRUE))
  rows <- nrow(dat)
  cols <- ncol(dat)

  #get positions of all subtables to analyze
  newTablesPos <- c(grep("Array ID.*", dat), grep("Array S.*", dat)) #assumption: inside one file you have EITHER ArrayID or Array S/N
  #convert to matrix representation of positions
  newTablesPos <- convertPos(newTablesPos, rows, cols)
  nummtab <- nrow(newTablesPos)
  print(paste("   ( Numb. of tables found in this file: ", nummtab, ")", sep=" "))
  
  tabRow <- c(unique(newTablesPos[,1]), rows+3) # unique rows
  #actually, we need unique rows for every column separately: sowe'll do it IN the for loop.
  #UPDATE: no we wont. we'll just add check for empty tables.
  tabCol <- c(unique(newTablesPos[,2]), cols+1) # unique colms

  tabcounter <- 0
  #print(dat)
  #print(newTablesPos)
  #for all subtables in one row do:         #assumption: if there are more tables in a file, they are alligned!
  for (j in 1:(length(tabCol)-1)) {
    table <- dat[,tabCol[j]:(tabCol[j+1]-1)] #split by unique columns
    #tabRow <- c(unique(newTablesPos[newTablesPos[,2]==tabCol[j],1]), rows+3) #unique rows in current column. unique is actually not needed here. It is os by default.
    #UPDATE: as written few lines up - instead of this, well add check for empty matrices
    
    #for all subtables in one col do:
    for (i in 1:(length(tabRow)-1)) {
      oneTable <- table[(tabRow[i]-2):(tabRow[i+1]-3),]
      rowz <- tabRow[i+1]-tabRow[i]
      
      #check for empty matrices:
      if (length( oneTable[(oneTable!="") & (!is.na(oneTable))] )==0) { #we have an empty matrix, so we shouldnt do anything
          #print("were in")
          next 
      }
      tabcounter <- tabcounter+1
      print(paste("      - reading table", tabcounter, "...", sep=" "))
      
      #prepare subtable for extraction of mutant, organ and ID:
      #update: in current set of files, it can happen that we get some additional info (usually 'bout organs) 
      #       in first rows, before actual table. Lets try to extract these. TODO: try to implement them so that they're used automatically 
      nonempty <- oneTable[1:3,][which(oneTable[1:3,]!="")]
      #take care of case where theres sth given as a comment, and would be caught in this (e.g. the remark "hippo=hippocampus, CRX=cortex" 
      #   in Agrin file.
      ostanek <- length(nonempty)%%3
      #print(ostanek)
      if (ostanek!=0) { #then sth more is given. try to extract it.
          #but! it might be given even in some rows before the idmutorg...
          #the column with additional data:  
          additional <- which(oneTable[1,]!="")
       #   print(additional)
          if (length(additional)>3) { #we assume, that if theres addit. data, its in one column. if not, lets mark it as UNKNOWN FILE TYPE!
              unsuccess <- rbind(unsuccess, currentFile)
              next
          } else { 
              #now get the data from table:
              additionalCol <- tail(additional, 1)
              additional <- dat[2:(tabRow[i+1]-3), tabCol[j] - 1 + additionalCol]
              additional <- additional[additional!=""] #TODO: integrate this additional info into our table!
              nonempty <- nonempty[1:(length(nonempty)-ostanek)] 
              oneTable <- oneTable[, -additionalCol] #lets get rid of this extra column after we extracted the infos
          }
      }
      #Problem: there is also a possibility that this additional info isnt alligned with idmutorg table. but then it is
      #   alligned with our general table; which means we prolly have some additional rows...we take care of this in reading!
      #   by seting it as an error - it will be written into the TODO file and wont be read until manually changed and run again.
      subtable <- matrix(nonempty, nrow=3)  
      
      #print(subtable)
      IDMutOrg <- extractOrgMutID ( subtable ) #here do extraction

      #construct proper name for current table from mutant and arrayID
      curTabName <- paste ( IDMutOrg[1], namesPart2[currentFile], sep="_" )

      success <- FALSE
      #rearrange the rest of table and if all went OK, postprocess it and write it into a csv file with proper name
      try ( {oneTable <- doReading ( oneTable[4:rowz,], IDMutOrg[3])
           success <- TRUE}, silent=FALSE )
      if ( (!is.integer(oneTable)) & success ) {
        flagy <- oneTable$Flag
        oneTable <- postprocess ( oneTable$Table[,1:6], IDMutOrg[3], curTabName, flagy ) #here we postproc:get rid of "NA", pad missing values, etc., and write to csv
      } else { unsuccess <- rbind(unsuccess, currentFile) }
    }
  }
}
#print names of files that could not be successfully read in a new file. just as a log, to know what needs to be checked
#   or maybe adapted a little by hand.
write.table(unique(unsuccess), file="unsuccessful.csv", row.names=FALSE, col.names=FALSE)


