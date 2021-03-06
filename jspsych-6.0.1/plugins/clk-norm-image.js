// TODO clk made this by frankensteining js plugins
// TODO change plugin.info


jsPsych.plugins['clk-norm-image'] = (function(){

  var plugin = {};

  jsPsych.pluginAPI.registerPreload('clk-norm-image', 'stimulus', 'image');

  plugin.info = {
    name: 'clk-norm-image',
    description: '',
    parameters: {
      stimulus: {
        type: jsPsych.plugins.parameterType.IMAGE,
        pretty_name: 'stimulus',
        default: undefined,
        description: 'The image to be displayed'
      },
      choices: {
        type: jsPsych.plugins.parameterType.KEYCODE,
        array: true,
        pretty_name: 'Choices',
        default: jsPsych.ALL_KEYS,
        description: 'The keys the subject is allowed to press to respond to the stimulus.'
      },
      prompt: {
        type: jsPsych.plugins.parameterType.STRING,
        pretty_name: 'Prompt',
        default: null,
        description: 'Any content here will be displayed below the stimulus.'
      },
      stimulus_duration: {
        type: jsPsych.plugins.parameterType.INT,
        pretty_name: 'Stimulus duration',
        default: null,
        description: 'How long to hide the stimulus.'
      },
      trial_duration: {
        type: jsPsych.plugins.parameterType.INT,
        pretty_name: 'Trial duration',
        default: null,
        description: 'How long to show trial before it ends.'
      },
      response_ends_trial: {
        type: jsPsych.plugins.parameterType.BOOL,
        pretty_name: 'Response ends trial',
        default: true,
        description: 'If true, trial will end when subject makes a response.'
      },
    }
  }

  plugin.trial = function(display_element, trial) {

    //add image
    var new_html =''
    new_html += '<form id="clk-norm-image-form">'
    new_html += '<div id="img" style="max-height:500px; max-width:500px; object-fit:contain; height:500px; width:500px; display:table-cell; vertical-align:middle">'
    new_html += '<img src="'+trial.stimulus+'" id="clk-norm-image-stimulus" style="max-height:100%; max-width:100%">';
    new_html += '</div>'
    // add response box
    new_html += '<div id="response_box" class="clk-norm-image-response">' // TODO lol idk what asd is supposed to be but it works
    new_html += '<p><input type="text" id="input" name="#clk-norm-image-text-box' + 40 + '" size="40" autofocus="autofocus"></p>'
    new_html += '</div>'

    // add prompt
    if (trial.prompt !== null){
      new_html += trial.prompt;
    }
    new_html += '</form>'

    // draw
    display_element.innerHTML = new_html;
    document.getElementById("input").focus()

    display_element.querySelector('#clk-norm-image-form').addEventListener('submit', function() {
      // measure response time
      var endTime = performance.now();
      var response_time = endTime - startTime;

      // get response
      var matches = display_element.querySelectorAll('div.clk-norm-image-response');
      var response = matches[0].querySelector('textarea, input').value;

      // save data
      var trialdata = {
        "rt": response_time,
        "response" : response,
        "filename": trial.stimulus
      };

      display_element.innerHTML = '';

      // next trial
      jsPsych.finishTrial(trialdata);
    });

    var startTime = performance.now()
  };

  return plugin;
})();