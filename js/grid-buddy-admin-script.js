jQuery(document).ready(function($){	

	//adding new widget area
	$('.addNewWidgButton').click(function(){
		if($.trim($('.addNewWidgTitle').val()) != ''){
			$.getScript("jscolor/jscolor.js");
			var appenedString = '<li> Title: <input style="width: 100px;" name="addedWidgetsTitles[' + $('.addNewWidgTitle').val() + ']" value="' + $('.addNewWidgTitle').val() + '" readonly><br>';		
			appenedString += 'Background Color: <input style="width: 100px;" class="color" name="addedWidgetsColors[' + $('.addNewWidgTitle').val() + ']" value="FFFFFF" required>';
			appenedString += 'Height: <input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsHeights[' + $('.addNewWidgTitle').val() + ']" value="100" required>';
			appenedString += 'Width: <input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsWidths[' + $('.addNewWidgTitle').val() + ']" value="100" required>';
			appenedString += 'Top Margin: <input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsTops[' + $('.addNewWidgTitle').val() + ']" value="0" required>';
			appenedString += 'Left Margin: <input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsLefts[' + $('.addNewWidgTitle').val() + ']" value="0" required>';
			appenedString += '<button type="button" class="deleteWidgetButton">Delete This Widget</button></li>';
			$('.listOfWidgetAreas').append(appenedString);
		}else{
			$('.addNewWidgTitle').val('Enter a name!');
		}	
	});
	
	//delete widget area
	$('.deleteWidgetButton').click(function(){
		$(this).parent().remove();
	});
});

