<!-- TODO: you need to write save_data(); find new images for some; write randomize() -->

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="jspsych-6.0.1/jspsych.js"></script>
    <script src="jspsych-6.0.1/plugins/clk-norm-image.js"></script>
    <script src="jspsych-6.0.1/plugins/jspsych-survey-text.js"></script>
    <link href="jspsych-6.0.1/css/jspsych.css" rel="stylesheet" type="text/css"></link>
</head>
<body>
</body>

<?php
	$dir = 'images';
	$data = scandir($dir, 1);
	$dirdata = array_slice($data, 0, count($data)-2);
?>

<script>

	let filenames = <?php echo json_encode($dirdata, JSON_HEX_TAG); ?>;

    // initialize empty data structure
    show_image = {
        type: "clk-norm-image",
        stimulus: "",
        prompt: "Type the name of this object. <p>Hit ENTER to proceed to next.</p>",
        on_start: function(){
            this.stimulus = "images/"+String(filenames[iterate_images.img_num])
             //TODO autofocus!
        },
        on_finish: function(trialdata){
        	// iterate_images.img_num = iterate_images.img_num + 1
            console.log(trialdata)
            // TODO needs to push data in readable format
        }
    }

    let iterate_images = {
      timeline: [show_image],
      img_num: 0,
      loop_function: function(){
	      iterate_images.img_num = iterate_images.img_num + 1
	      if (iterate_images.img_num === filenames.length){
	          return false
	      }
	      else {return true}
      }
    }

    timeline = [iterate_images]
    jsPsych.init({
        timeline: timeline,
        show_preload_progress_bar: true,
    })

</script>

