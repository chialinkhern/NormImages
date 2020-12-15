<!DOCTYPE html>
<html lang="en">
<head>
    <script src="jspsych-6.0.1/jspsych.js"></script>
    <script src="jspsych-6.0.1/plugins/clk-norm-image.js"></script>
    <script src="jspsych-6.0.1/plugins/jspsych-survey-text.js"></script>
    <script src="jspsych-6.0.1/plugins/jspsych-instructions.js"></script>
    <script src="jspsych-6.0.1/plugins/jspsych-call-function.js"></script>
    <script src="helper_functions.js"></script>
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
	filenames = shuffle(filenames)
    let sub_num = Date.now()
    let out_data = {images: [], responses: [], rts: []}

    let show_image = {
        type: "clk-norm-image",
        stimulus: "",
        prompt: "Type the name of this object. <p>Hit ENTER to proceed to next.</p>",
        on_start: function(){
            this.stimulus = "images/"+String(filenames[iterate_images.img_num])
        },
        on_finish: function(trialdata){
            out_data.images.push(trialdata.filename.replace("images/", ""))
            out_data.responses.push(trialdata.response)
            out_data.rts.push(trialdata.rt)
        }
    }

    let iterate_images = {
      timeline: [show_image],
      img_num: 0,
      loop_function: function(){
	      iterate_images.img_num = iterate_images.img_num + 1
	      console.log(out_data)
	      if (iterate_images.img_num === filenames.length){
	          return false
	      }
	      else {return true}
      }
    }

    let instructions = {
        type: "instructions",
        pages: ["Name these objects!"],
        show_clickable_nav: true
    }

    let end = {
        type: "instructions",
        pages: ["You're done. Thanks!"],
        show_clickable_nav: false
    }

    let write_data = {
        type: "call-function",
        func: function(){
            let name = "subj_" + String(sub_num)
            let data = prep_data(out_data)
            save_data(name, data)
        }
    }

    timeline = [instructions, iterate_images, write_data, end]
    jsPsych.init({
        timeline: timeline,
        show_preload_progress_bar: true,
    })

</script>

