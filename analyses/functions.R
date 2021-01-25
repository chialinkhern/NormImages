# Title     : functions
# Objective : various functions
# Created by: chialinkhern
# Created on: 12/18/20

compute_proportion_correct = function(correct_response, all_responses){
  all_responses = strsplit(all_responses, ",")[[1]]
  num_responses = length(all_responses)
  num_correct = 0
  for (response in all_responses){
    if ((response==correct_response)|is_typo(correct_response, response)){
      num_correct = num_correct + 1
    }
  }
  proportion_correct = num_correct/num_responses
  return(proportion_correct)
}

compute_proportion_top = function(responses){
  responses = strsplit(responses, ",")[[1]]
  num_responses = length(responses)
  top = names(sort(table(responses), decreasing=TRUE)[1])
  num_top = 0
  for (response in responses){
    if ((response==top)|is_typo(top, response)){
      num_top = num_top + 1
    }
  }
  proportion_top = num_top/num_responses
  return(proportion_top)
}

get_all_responses = function(image, trials_df){
  responses = trials_df[trials_df$images==image, "responses"]
  responses = as.vector(responses)

  # this block trims whitespace
  i = 0
  for (response in responses){
    if (response==""){
      response = "NO_RESPONSE"
    }
    responses[i] = trimws(response)
    i = i + 1
  }
  responses = paste(responses, collapse=",")
  return(responses)
}

get_top_3 = function(responses){
  responses = strsplit(responses, ",")[[1]]
  responses = paste(names(sort(table(responses), decreasing=TRUE)[1:3]), collapse=",")
  return(responses)
}

is_typo = function(correct_response, response){ # returns whether or not response was a typo of correct_response
  correct_response = strsplit(correct_response, "")[[1]]
  response = strsplit(response, "")[[1]]
  num_match = 0
  for (i in correct_response){
    j_index = 1
    for (j in response){
      if (i==j){
        response[j_index] = "nope"
        num_match = num_match + 1
        break
      }
      j_index = j_index + 1
    }
  }
  proportion_match = num_match/length(correct_response)
  if (proportion_match > 0.7 & (0<(length(correct_response)-length(response))) & (length(correct_response) - length(response)<2)){ # if 60% of correct_response's letters are found AND if response is not longer than correct_response
    return(TRUE)
  }
  else {return(FALSE)}
}

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

read_and_attach_name = function(path_to_file){ # because I was too shortsighted to include subnum in data files
  df = read.csv(path_to_file, stringsAsFactors=FALSE)
  df$subj_num = gsub(".csv", x=toString(path_to_file), replacement="")
  return(df)
}
