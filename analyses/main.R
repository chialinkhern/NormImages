# Title     : main
# Objective : does main stuff
# Created by: chialinkhern
# Created on: 12/18/20

trials_df = read.csv("analyses/out/data.csv")

attach(trials_df)
items_df = aggregate(rts~images, FUN="mean")
detach(trials_df)
colnames(items_df) = c("images", "mean_rts")
items_df = add_all_responses(items_df, trials_df)
items_df = add_top_3(items_df)
items_df = add_proportion_correct(items_df)
