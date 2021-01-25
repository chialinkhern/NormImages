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
items_df = add_all_responses(items_df, trials_df)
items_df = add_top_3(items_df)
items_df = add_proportion_correct(items_df)


# write.csv(items_df, "analyses/out/items.csv", row.names=FALSE)
