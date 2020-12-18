# Title     : functions
# Objective : various functions TODO: split them up
# Created by: chialinkhern
# Created on: 12/18/20

rbindall = function(indir, outdir){
  originalwd = getwd()
  setwd(indir)
  files = list.files(pattern="*.csv")
  trials = do.call(rbind, lapply(files, function(x) read_and_attach_name(x)))
  trials$X = NULL # no idea why X appears as a column; this is an easy fix
  trials$rts = as.numeric(trials$rts)
  trials$responses = tolower(trials$responses)
  setwd(originalwd)
  write.csv(trials, outdir, row.names=FALSE)
}

read_and_attach_name = function(path_to_file){ # because I was too shortsighted to include subnum in data
  df = read.csv(path_to_file, stringsAsFactors=FALSE)
  df$subj_num = gsub(".csv", x=toString(path_to_file), replacement="")
  return(df)
}

get_all_responses = function(image, trials_df){
  responses = trials_df[trials_df$images==image, "responses"]
  responses = as.vector(responses)

  # this block trims whitespace
  i = 0
  for (response in responses){
    responses[i] = trimws(response)
    i = i + 1
  }

  #TODO handle typos here?
  responses = paste(responses, collapse=",")
  return(responses)
}

add_all_responses = function(items_df){
  for (image in items_df$images){
    items_df$all_responses[items_df$images==image] = get_all_responses(image, trials_df)
  }
  return(items_df)
}

get_top_3 = function(responses){
  responses = strsplit(responses, ",")[[1]]
  responses = paste(names(sort(table(responses), decreasing=TRUE)[1:3]), collapse=",")
  return(responses)
}

add_top_3 = function(items_df){
  for (item in items_df$images){
    items_df$top_3[items_df$images==item] = get_top_3(items_df$all_responses[items_df$images==item])
  }
  return(items_df)
}