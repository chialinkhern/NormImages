# Title     : functions
# Objective : various functions TODO: split them up
# Created by: chialinkhern
# Created on: 12/18/20

rbindall = function(indir, outdir){
  originalwd = getwd()
  setwd(indir)
  files = list.files(pattern="*.csv")
  trials = do.call(rbind, lapply(files, function(x) read.csv(x, stringsAsFactors = FALSE)))
  trials$X = NULL
  trials$rts = as.numeric(trials$rts)
  setwd(originalwd)
  write.csv(trials, outdir, row.names=FALSE)
}