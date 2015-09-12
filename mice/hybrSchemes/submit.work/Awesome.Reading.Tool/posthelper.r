postprocess <- function ( tab, tissue, fajl, flagy, missing, additional ) {
  tab[,2:5][tab[,2:5]==""] <- "NA"
  #(tab[,6])[tab[,6]=="NA"] <- ""  #this is not needed bcs I changed default value of comments to rep("", 8) everywhere.
  #print(tab)

  #now (supposedly) our matrix has values where they could be extracted, and "NA" everywhere else, except in comments column; there we have "" if comm. not given
  #But it can happen that sex, mutant or organ was 'lazily' given - only here and there written, assuming repetitions. Lets take care of that.
  #first: get indexes of existing data.
  idSex <- (1:8)[tab[,4]!="NA"]
  idOrg <- (1:8)[tab[,5]!="NA"]
  idMut <- (1:8)[tab[,3]!="NA"]
  #second: find out how many places need to be padded. if thats even possible.
  #and padd 'em, under the assumption that, if we extracted sex, organ or mutant at all, it was certainly given for the fist table entry (see assumptions!)
  if (!invalid(idSex)) { #we encountered coulmn full of NA's. leave it at that.
      paddS <- diff(c(idSex, 9))
      tab[,4] <- rep( (tab[,4])[idSex], paddS )
  }
  if (!invalid(idOrg)) { #else we encountered coulmn full of NA's. leave it at that.
      paddO <- diff(c(idOrg, 9))
      tab[,5] <- rep( (tab[,5])[idOrg], paddO )
  }
  if (!invalid(idMut)) { #else we encountered coulmn full of NA's. leave it at that.
      paddM <- diff(c(idMut, 9))
      #print(rep( (tab[,3])[idMut], paddM ))
      #print(tab[,3])
      tab[,3] <- rep( (tab[,3])[idMut], paddM )
  }

  #another thing that can happen is, that we dont have organ/tissue inside the table. but we extracted it from preambule before... so we
  # need to set it from NAs to that.
  if (tab[1, 5]=="NA") {
      tab[,5] <- tissue #rep(tissue, 8)
  }
  #in any case, it is possible that we got additional info about the organs at the beginning..
  #they are currently written into file AdditionalData.csv. TODO:check if its possible to directly integrate this data
  
  #we also need to take care of the case where only 7 instead of 8 rows were given:
  if (sum(flagy)) {
      tab[8,] <- c("H", rep("NA", 4), "")
      if (length(flagy)==2) {
          tab[7,] <- c("G", rep("NA", 4), "")  
    }
  }
  #and now take care of the rows, that originally had no data given:
  msd <- c("A","B","C","D","E","F")
  for (miss in missing) {
    tab[miss, ] <- c(msd[miss], rep("NA", 4), "")  
  }
  
  tab <- trim(tab)
  #now our table seems to be complete. Lets save it:
  write.csv ( tab[,2:6], file = paste("results/", fajl, ".csv", sep=""), na = "NA", row.names = tab[,1] )
  
  #print(tab)
}



doReading <- function ( tab, tissue ) {
  #print(tab)
  
  #how many stuff do we have to read? plus padd the matrix with NA's to right size
  lines <- nrow(tab)
  tab <- matrix(tab[!is.na(tab)], nrow=lines) #make sure we loose read NA columns. assumption: if one value of column is NA, others cannot be strings!
  columns <- ncol(tab)
  
  #maybe last row is given just bcs of the reading; and its all "". then get rid of it:
  #print(tab)
  if (columns==2) {
    while (tab[lines, 2] %in% c("", "-")) {
        tab <- tab[-lines,]
        lines <- lines-1
    }
  } else {
    while (sum(tab[lines,3:columns]=="")==columns-2 & (tab[lines, 2] %in% c("", "-"))) { #cases, when last (two) lines not given. ("","", ""...)|(G, "", ""...)|(G,-,"",...)
        tab <- tab[-lines,]
        lines <- lines-1
    } 
  } 
  
  #since here we also have a column for ABCD, we need 6 columns
  tab <- cbind(tab, matrix(rep("NA", lines*(7-columns)), ncol = 7-columns))[,1:6]
  #check, if some other rows in the middle of table are missing:
  missing <- (1:lines)[rowSums(matrix(tab[, 2:columns]=="", ncol=columns-1))==(columns-1)]
  #just in case, if our form is ("", "A(1):..", "", ...), lets create the columns for the sake of grep.
  tab[missing, 2] <- "Z(1): 0"
  
  #temp <- which(tab[,1]!="") #### See TODO lower...
  flag <- FALSE
  #we also need 8 rows:
  if (lines<8) { 
    tab <- rbind(tab, matrix(rep(tab[lines, ], 8-lines), ncol=6, byrow=TRUE)) #c("H", rep("NA", 5))) #assumption: if we miss rows, we're missing just one
    #make sure, that we wont overwrite last row, if it is not given (that sometimes happened...:/)
    flag <- rep(TRUE, 8-lines) #all(tab[8, -1]=="NA")
  }
  ########## TODO: (but probably not; just change the files slightly by hand - coppy add. data so that it suits the form) 
  #here we take care of case additional info is alligned with general table
  ##########
  #print(tab)

  #lets initialize vectors we will need with NA's or default.
  sex   <- rep("NA", 8)
  mousey  <- rep("NA", 8)
  organs   <- rep("NA", 8)
  mutants   <- rep("NA", 8)
  comments   <- rep("", 8) #rep("NA", 8)

  #EXTRACT ABCDEFGH
  #----------------------------------
  #check if ABCDEFGH are already in own column; drop (rewrite) it, if its empty:
  if (tab[1,1]==""){
    #we have to split strings (in second column we have A(1):...), and we can forget first column (or rewrite it with ABC...)
    tab[,1:2] <- trim(matrix(unlist(strsplit(tab[,2], "[ ]*[(][0-9][)]:[ ]*")), byrow=TRUE, ncol=2))
  }
  #flag = (flag | all(tab[8, -1]=="NA"|tab[8,-1]=="")) #this is now changed; see lines 79-80

  #after extraction of ABCDEFGH to first column, we now work on next one.
  #EXTRACT MOUSE ID
  #----------------------------------
  mousey[grepl("[J]{0,1}[0-9]+[-]{0,1}[0-9]*", tab[,2])] <- regmatches(tab[,2], regexpr("[J]{0,1}[0-9]+[-]{0,1}[0-9]*", tab[,2])) #assumption: if MouseID given, it's directly after ABCDEFGH
  #everything else, that might be given in this same column is saved in genOD
  genOD <- regmatches(tab[,2], regexpr("[J]{0,1}[0-9]+[-]{0,1}[0-9]*[ ]*", tab[,2]), invert=TRUE)
  #in those elements of genOD, that consist of two string, we have to remove first (the empty) one
  for (i in 1:8) {
    if (length(genOD[[i]])==2) { #only possibilities are len=1 (OK), or len=2 (then first one is empty, since our expression (ASSUMPTION!) starts at the beginning)
      genOD[[i]] <- genOD[[i]][2]
    }
  }
  genOD <- trim(unlist(genOD)) #tu bi blo ok, ce predpostavis da mas zmer vse ID al pa nic kar tole: trim(genOD[genOD!=""])
  tab[,2] <- genOD
  #(tab[,2])[genOD==""] <- "NA"  #take care of not having "" in the first line of any column. just for easier search for comments etc.
  #nope, this on top should not be done - if you have eg c("", "mut", "wt"...) and do this, you wont find any mutants, even though you have them.
  #but it is an elegant and easier way to do it, if you KNOW, that if you have mutants, you will have one in first line also.

  #now we can check if it is possible to extract organ from any remaining column (or newly produced soon-to-be-columns genOD - TODO)
  #EXTRACT ORGAN
  #----------------------------------
  fromWhere <- (sapply(trim(tab[1,3:6]), grepl, x=tissue) & (tab[1, 3:6]!=""))
  if (sum(fromWhere) > 1) { #then organs could be extracted from more columns. UNKNOWN FILE TYPE!
      return(1)
  } else if (sum(fromWhere)==1){
      fromWhere <- (3:6)[fromWhere]
      organs <- tab[,fromWhere]  #TODO:DONE! organs need to be checked in postprocessing. where missing, padd!
      tab[,fromWhere] <- rep("NA", 8)
  } #else:organs could not be extracted: remain NA.
  #TODO: maybe you can extract organs from the genOD? currently not supported! UNKNOWN FILE TYPE! (but this time no precautions taken!!)
  #print(tab)

  #now we continue with extraction of sex. this one is longer and we parallelly extract mutants and comments
  #(remaining cases are [mutsex][com], [sexmut][com], [mut][sex][com], [sex][mut][com])
  #EXTRACT SEX, MUTANT, COMMENTS
  #------------------------------------
  fromWhere <- grepl("^[ ]*[mfwMFW][ ]*$", tab[1,2:6])
  if (sum(fromWhere) > 1) { #then sexes could be extracted from more columns. UNKNOWN FILE TYPE!
      return(1)

  } else if (sum(fromWhere)==1){ #sex can be extracted directly from some column
      fromWhere <- (2:6)[fromWhere]
      sex <- tab[, fromWhere]  #TODO:DONE! genders need to be checked in postprocessing. where missing, padd!
      tab[,fromWhere] <- rep("NA", 8)

      #so, we have extracted sexes, now we check if we have comments and mutants
      mutcom <- MutComHelper ( tab )
      if (is.integer(mutcom)) { return (1) } #too much is given, see function MutComHelper
      mutants <- mutcom[,1]
      comments <- mutcom[,2]

  } else { #sex cannot be extracted directly. We need to check if we can Extract it from begin or end of some column
      #check the beginning
      fromWhere <- grepl("^[ ]*[mfwMFW][ ]+", tab[1,2:6])
      if (sum(fromWhere) > 1) { #more than one sex could be extracted. sth went wrong. UNKNOWN FILE TYPE!
        return(1)
      } else if (sum(fromWhere)==0) { #sex could not be extracted from left. check if can be from right:
          #check the end
          fromWhere <- grepl("[ ]+[mfwMFW][ ]*$", tab[1,2:6])
          if (sum(fromWhere) > 1) { #again, more than one sex could be extracted, so we have an UNKNOWN FILE TYPE!
              return (1)
          } else if (sum(fromWhere)==0) { #sex could not be extracted from right either. so: we dont have it.
              #so we dont have sex given. but if there are any nonempty columns left, then first one is mut, and second one comm (see assumptions)
              mutcom <- MutComHelper ( tab )
              #print(tab)
              #print(mutcom)
              if (is.integer(mutcom)) { return (1) } #too much is given, see function MutComHelper
              mutants <- mutcom[,1]
              comments <- mutcom[,2]
              #print(mutants)
              #print(comments)
          } else { #sex can be successfully extracted from right. Lets do that.
              #extraction of sex from right, ...remaining in 2.col is: mut, if another nonempty exists, it is: comments
              sex[grep("[ ]+[wfmWFM][ ]*$", tab[,2])] <- trim(regmatches(tab[,2], regexpr("[ ]+[wfmWFM][ ]*$", tab[,2])))
              mutants <- sub("[ ]+[wfmWFM][ ]*$", "", tab[,2])  #as said, what remains in 2nd col is mutants
              #check, if theres more nonempty cols, for comments
              remaining <- (3:6)[tab[1,3:6]!="NA"] #TODO: spet, pogoj morda ni okej. glej spodaj pod MORDA!
              tmpl <- length(remaining)
              if (tmpl>1) { #too much is left, sth went wrong. UNKNOWN FILE TYPE!
                  return (1)
              } else if (tmpl==1) { #we found comments column
                  comments <- tab[,remaining]  #here (as in function MutComHelper) we assume that if col with some comments exist, none of its elts is "NA"
              }
          }
      } else {  #else, sex can be successfully extracted from left. Lets do that.
          #extraction of sex from left, ...remaining in 2.col=mut, if another nonempty exists, it is comments
          sex[grep("^[ ]*[wfmWFM][ ]+", tab[,2])] <- trim(regmatches(tab[,2], regexpr("^[ ]*[wfmWFM][ ]+", tab[,2])))
          mutants <- sub("^[ ]*[wfmWFM][ ]+", "", tab[,2])  #as said, what remains in 2nd col is mutants
          #check, if theres more nonempty cols, for comments
          remaining <- (3:6)[tab[1,3:6]!="NA"] #TODO: spet, pogoj morda ni okej. glej spodaj pod MORDA!
          tmpl <- length(remaining)
          if (tmpl>1) { #too much is left, sth went wrong. UNKNOWN FILE TYPE!
              return (1)
          } else if (tmpl==1) { #we found comments column
              comments <- tab[,remaining]  #here (as in function MutComHelper) we assume that if col with some comments exist, none of its elts is "NA"
          }
      }
  }
  tab <- cbind(tab[,1], mousey, mutants, sex, organs, comments)
  #print(tab)
  return ( list(Table=tab, Flag=flag, Missing=missing) )
}



MutComHelper <- function ( tab ) {
    remaining <- (2:6)[c(!all(tab[,2]==""), tab[1, 3:6]!="NA")] #TODO: MORDA! pogoj ni ok! za comments skor ziher ne bos mel v prvi vrstici non NA vrednosti... OZ JE OK? KER BOS MEL ITAK KR SAM "", kar pa je !="NA", ANE?
    tmpl <- length(remaining)
    if (tmpl>2) { #then we have more than mut and comms left. sth went wrong. UNKNOWN FILE TYPE!
        return (1)
    } else if (tmpl==1) { #we only have mutants given (see assumptions!)
        muts <- tab[, remaining[1]]
        comms <- rep("", 8) #rep("NA", 8)
    } else if (tmpl==2) { #we have mutants AND comments given. comments are last - see assumptions!
        muts <- tab[, remaining[1]]
        comms <- tab[, remaining[2]]
        #print("set muts:  2")
        #print("set comms:  3")
        #print(tab)
    } else { #we have neither comments, nor mutants given.
        muts <- rep("NA", 8)
        comms <- rep("", 8) #rep("NA", 8)
    }
    return (cbind(muts, comms))
}









################################################################################ f i r s t      t r y
    #Extract mouseId, genotype, sex, tissue and comments
    #  final <- ExtractAll ( table )   (this should do all the following:)
  ##################
    #now extract the mousey id:
#    mousey <- regmatches(tab[,2], regexpr("[J]{0,1}[0-9]+[-]{0,1}[0-9]*", tab[,2]))
    #now extract the lovely genotypes and/or genders:
#    genOD <- unlist(regmatches(tab[,2], regexpr("[J]{0,1}[0-9]+[-]{0,1}[0-9]*[ ]*", tab[,2]), invert=TRUE))
#    genOD <- trim(genOD[genOD!=""])
    #check if we have sex, and then save it:
#    sex <- rep("NA", 8)
#    sex[grep("[ ]+[wfmWFM][ ]*$", genOD)] <- trim(regmatches(genOD, regexpr('[ ]+[wfmWFM][ ]*$', genOD)))
      # Remark: here w=weiblich, m=male/mann..., f=female? or is f part of genotype?
    #what we are now left with is genotype:
#    genotype <- sub("[ ]+[wfmWFM][ ]*$", "", genOD) #in case it doesnt work properly: maybe number of spaces should be adjusted?
                                                    #or maybe some WFM's are parts of genotypes and not sex?

#    if (all(sex=="NA")) { #then we didnt find sex at the ends. Lets check...
      #just in case our sex is before the mutant (that happens only once, with M/F, right? or is that not sex?) - wiederholung:
#      sex[grep("^[ ]*[wfmWFM][ ]+", genOD)] <- trim(regmatches(genOD, regexpr('^[ ]*[wfmWFM][ ]+', genOD)))
#      genotype <- sub("^[ ]*[wfmWFM][ ]+$", "", genOD)
#    }
    #if genotype we get is NA, maybe it is not in this, but in the next column... TODO, but should not happen logically.
#    if (all(genotype=="NA")|| all(genotype=="")){
#      genotype <- tab [,3]
#      comments <- tab [,4] #TODO: search in this case for sex in tab[,4], before setting it as comments
#    }
    #it can happen, that there was no sex given at the beginning either. Is it than maybe in the next column? (although
    #   in current set of data files this shouldnt happen...)
#    else if (all(sex=="NA")) { ##else if just bcs this should not happen at the same time as genotype=NA. or can it? TODO
#      sex[grep('^[ ]*[wfmWFM][ ]*$', tab[,3])] <- trim(regmatches(tab[,3], regexpr('^[ ]*[wfmWFM][ ]*$',tab[,3])))
#      if (all(sex=="NA")) {
#        comments <- tab[,3]
#      } else { comments <- tab[,4] }
#    }
#    else { comments <- tab[,3] }
    #if (all(sex=="NA") { #if we still didnt find sex, then there is none. we only have possible comments.
    #  comments <- tab[,3]
    #}
  ###################
  #except sex column, others might have "NA", NA, or "" for missing values... take care of it in postprocessing!
#  }
#  else {
    #if were here, the ABCD are in their own coulmn. But we might still need to split the Id+genotype+sex column:
#    mousey <- regmatches(tab[,2], regexpr("[J]{0,1}[0-9]+[-]{0,1}[0-9]*", tab[,2])) #get the mouse id


#    ####TODO
#    sex <- tab[,3]
#    genotype <- tab[,4]
#    comments <- tab[,5]
    ####+manjkajo ti organi!!
#  }
#  tab[,2:5] <- cbind(mousey, genotype, sex, comments)
#  colnames(tab[,2:6]) <- c("MouseID", "genotype", "sex", "tissue", "comments")
  #print(tab)
#  write.csv ( tab[,2:6], file = paste("../../code/results/", tabName, ".csv", sep=""), na = "NA", row.names = tab[,1] )

#  #TODO: organs! +should be col.names = c("mouseID", "genotype", "sex", "tissue", "comments"). take care of these at importing
#}
