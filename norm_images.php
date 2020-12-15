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
    function shuffle(array) {
        var currentIndex = array.length, temporaryValue, randomIndex;

        // While there remain elements to shuffle...
        while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
        }
      return array;
    }

    function prep_data(data) { // Trisha's function
        var datacsv = "";
        var labels = Object.keys(data); //grabs all the properties of data

        for (n = 0; n < labels.length; n++){
            datacsv = datacsv + labels[n] + ',';
            }
        datacsv = datacsv + '\n';

        let ntoloop = data[Object.keys(data)[0]].length;
        for (n = 0; n < ntoloop; n++){
            for (var i in data){
                if (data.hasOwnProperty(i)){
                    datacsv = datacsv + data[i][n] + ','; //in "str" + num, num is converted to a string.
                    }
                }
            datacsv = datacsv + '\n';
            }
        return datacsv;
    }

    function save_data(name, data){
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'write_data.php'); // 'write_data.php' is the path to the php file
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({filename: name, filedata: data}));
    }

    let sub_num = Date.now()
    let out_data = {images: [], responses: [], rts: []}

	let filenames = <?php echo json_encode($dirdata, JSON_HEX_TAG); ?>;
	filenames = shuffle(filenames)


    show_image = {
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
	      if (iterate_images.img_num === 2){
	          return false
	      }
	      else {return true}
      }
    }

    timeline = [iterate_images]
    jsPsych.init({
        timeline: timeline,
        show_preload_progress_bar: true,
        on_finish: function(){
            let name = "subj" + String(sub_num)
            let data = prep_data(out_data)
            save_data(name, data)
        }
    })

</script>

