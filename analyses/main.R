# Title     : main
# Objective : does main stuff
# Created by: chialinkhern
# Created on: 12/18/20
source("analyses/functions.R")
trials_df = read.csv("analyses/out/data.csv")

attach(trials_df)
items_df = aggregate(rts~images, FUN="mean")
detach(trials_df)
colnames(items_df) = c("images", "mean_rts")

for (item in items_df$images){
  items_df$all_responses[items_df$images==item] = get_all_responses(item, trials_df)
  image_name = strsplit(item, "\\.")[[1]][1]
  items_df$top_3[items_df$images==item] = get_top_3(items_df$all_responses[items_df$images==item])
  items_df$proportion_correct[items_df$images==item] = compute_proportion_correct(image_name, items_df[items_df$images==item,]$all_responses)
  items_df$proportion_top[items_df$images==item] = compute_proportion_top(items_df$all_responses[items_df$images==item])
}

write.csv(items_df, "analyses/out/items2.csv", row.names=FALSE)
