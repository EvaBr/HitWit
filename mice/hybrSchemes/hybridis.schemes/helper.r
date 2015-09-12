convertPos <- function ( vector, rowz, colz ) {
  vector <- (cbind((vector-1)%%rowz+1, ceiling(vector/rowz)))
  colnames(vector) <- c("row", "col")
  return (vector)
}


extractOrgMutID <- function ( subtab ) {
  colz <- ncol(subtab)
  if (is.null(colz)|colz==1) {
    #extract the organ
    organ <- subtab[grep(".*rgan.*", subtab)]
      #Remark: grep is here always used just in case some files have different sequence of organ/id/mutant
    organ <- strsplit(organ, ".*rgan:[ ]*")[[1]][2]
    #extract the mutant, just in case its different from name (which one then to use??)
    mutant <- subtable[grep(".*utant.*", subtab)]
    mutant <- strsplit(mutant, ".*utant.*:[ ]*")[[1]][2]
    #extract the Array Id, for second part of the name
    arrayID <- subtab[grep(".*rray.*", subtab)]
    arrayID <- strsplit(arrayID, ".*rray.*:[ ]*")[[1]][2]

  } else {
    #extract the organ
    organ <- (subtab[grep(".*rgan.*", subtab[,1]), ])[2]
    #extract the mutant, just in case its different from name (which one then to use??)
    mutant <- (subtab[grep(".*utant.*", subtab[,1]), ])[2]
    #extract the Array Id, for second part of the name
    arrayID <- (subtab[grep(".*rray.*", subtab[,1]), ])[2]
  }

  return (c(arrayID, mutant, organ))
}
