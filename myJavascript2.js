// New button clicked
function doNewButton() {
  maxDBSize++;
  reset(); 
}

// Left button clicked
function doLeftButton() {
  textBoxValue = $("#UID").val();
  textBoxValue--;
  if (textBoxValue > 0) {
    $("#UID").val(textBoxValue);    
  }
  load();
}

// Right button clicked
function doRightButton() {
  textBoxValue = $("#UID").val();
  textBoxValue++;
  if (textBoxValue <= maxDBSize) {
    $("#UID").val(textBoxValue);    
  }
  load();
}

// Load button clicked
function doLoadButton() {
  load();
}

// Save button clicked
function doSaveButton() {
  formArray = {};
  $("#input_form").find(':input').each(function() {
    formArray[this.name] = $("#" + this.name).val();
  });

  $.get('backend.php', { Function: "SaveRow", DataArray: formArray }, function(data) {
    // NOP
  });

}

// Preview button clicked
function doPreviewButton() {
  formArray = {};
  $("#input_form").find(':input').each(function() {
    formArray[this.name] = $("#" + this.name).val();
  });
  renderPreview(formArray);
}

function renderPreview(inputArray) {
  $("#previewTitle").html("<h3>" + inputArray['Title'] + "<\h3>");
  $("#previewDescription").html("<h4>" + inputArray['Description'] + "<\h4>");
  var tagArray = inputArray['Tags'].split(",");
  var tagString = "Tags: ";
  var word = "";
  for (var loop = 0; loop < tagArray.length; loop++) {
    word = tagArray[loop];
    tagString += "<a href='#'>" + word.charAt(0).toUpperCase() + word.slice(1) + "</a>&nbsp;";
  }
  $("#previewTags").html("<h4>" + tagString + "<\h4>");
  $("#previewBootstrap").html("<hr/>" + inputArray['Code'] + "<br/><hr/>");
  $("#previewCode").html("<xmp>" + inputArray['Code'] + "</xmp>");
}

// Load from the DB
function load() {
  var uid = $("#UID").val();
  $.get('backend.php', { Function: "GetWholeRow", UID: uid }, function(data) {
    var dataArray = $.parseJSON(data);
    for (var key in dataArray) {
      $("#" + key).val(dataArray[key]);
    }
    renderPreview(dataArray);
  });
}
// Update UID text box
function updateTextBox(value) {
  $("#UID").val(value);
}

// Reset the form and the preview section
function reset() {
  $("#input_form")[0].reset();
  $("#previewTitle").html("");
  $("#previewDescription").html("");
  $("#previewTags").html("");
  $("#previewBootstrap").html("");
  $("#previewCode").html("");
  $("#UID").val(maxDBSize);
}

// Global variable for current max DB size
var maxDBSize;

// Initialisation function run on document load
$(document).ready(function() {

  // Catch AJAX errors
  $.ajaxSetup({
    error: function(xhr, state, error) {
      alert("An AJAX error occured: " + state + "\nError: " + error);
    }
  });

  // Initialise current DB size
  $.get('backend.php', { Function: 'GetDBSize'}, function(data) {
    var results_array = $.parseJSON(data);
    currentDBSize = results_array["COUNT(*)"];
    maxDBSize = currentDBSize;
    updateTextBox(currentDBSize);
    load();
  });

  // Register trigger for New button
  $('#new_button').on('click', function(e) {
    e.preventDefault();
    doNewButton();
  });

  // Register trigger for Left button
  $('#left_button').on('click', function(e) {
    e.preventDefault();
    doLeftButton();
  });

  // Register trigger for Right button
  $('#right_button').on('click', function(e) {
    e.preventDefault();
    doRightButton();
  });

  // Register trigger for Load button
  $('#load_button').on('click', function(e) {
    e.preventDefault();
    doLoadButton();
  });

  // Register trigger for Save button
  $('#save_button').on('click', function(e) {
    e.preventDefault();
    doSaveButton();
  });

  // Register trigger for Preview button
  $('#preview_button').on('click', function(e) {
    e.preventDefault();
    doPreviewButton();
  });
});