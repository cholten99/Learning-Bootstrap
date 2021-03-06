// Function to help sort returned array of examples
function compareExamples(a, b) {
  if (a.Title < b.Title) {
    return -1;
  } else if (a.last_nom > b.last_nom) {
    return 1;
  } else {
    return 0;
  }
}

// Display tag search results
function displayResults(data) {
  var dataArray = jQuery.parseJSON(data, true);
  dataArray.sort(compareExamples);
  var resultsString = "";

  for (var i = 0; i < dataArray.length; i++) {
    resultsString += "<h3>" + dataArray[i].Title + "</h3>\n";
    resultsString += "<h4>" + dataArray[i].Description + "</h4>\n";

    var tagArray = dataArray[i].Tags.split(",");
    var tagString = "Tags: ";
    var word = "";
    for (var loop = 0; loop < tagArray.length; loop++) {
      word = tagArray[loop];
      tagString += "<a href='javascript:ajaxCallForTag(\"" + word + "\");'>" + word.charAt(0).toUpperCase() + word.slice(1) + "</a>&nbsp;";
    }
    resultsString += "<h4>" + tagString + "</h4>\n";

    resultsString += "<hr/>" + dataArray[i].Code + "<hr/>\n";
    
    var insideOnclick = "$(\"#display_" + i + "\").toggle(1000);";
    
    resultsString += "<button onclick='" + insideOnclick + "'>Show</button>\n";
    resultsString += "<div id='display_" + i + "' style='display:none;'><xmp>" + dataArray[i].Code + "</xmp></div>";
    resultsString += "<hr/><hr/>\n";
  }

  $("#results").html(resultsString);

}

function ajaxCallForTag(tagValue) {
  $.get('backend.php', { Function: "GetExamples", Tag: tagValue }, function(data) {
    displayResults(data);
  });
}

// Initialisation function run on document load
$(document).ready(function() {

  // Catch AJAX errors
  $.ajaxSetup({
    error: function(xhr, state, error) {
      alert("An AJAX error occured: " + state + "\nError: " + error);
    }
  });

  // Event handler for select
  $("#tagSelect").change(function() {
    $value = $("#tagSelect").val();
    ajaxCallForTag($value);
  });


});
