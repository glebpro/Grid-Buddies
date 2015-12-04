jQuery(document).ready(function($){	

	//set color styles
	function setStyles(){
		$('.gridBuddyBox').css("background-color", "#"+boxColorOUT);
		$('.gridBuddyBoxWords').css("background-color", "#"+boxColorOUT);
		$('.gridBuddyBoxEntryTitle > h4').css("color", "#"+titleTextColorOUT);
		$('.gridBuddyBoxEntryContent > p').css("color", "#"+excerptTextColorOUT);
	}
	
	//get if any boxes are overflowing
	function notAllBoxesInWrapper(){
		var isOverFlowing = false;
		$(".gridBuddyBox").each(function( index ) {
			if($(this).position().top+$(this).outerHeight(true) > ($('.gridBuddyWrapper').position().top+$('.gridBuddyWrapper').outerHeight(true))){
				isOverFlowing = true;
			}
		});
		return isOverFlowing;
	}

	//set max box height, will remove image if doesn't fit, next will remove excerpt if doesn't fit
	function setMaxBoxHeight(){
		if(maxBoxHeightOUT != 0){
			$(".gridBuddyBox").each(function( index ) {
				if($(this).height() > maxBoxHeightOUT){
					$(this).css('height', maxBoxHeightOUT+"px");
					var imgHeight = maxBoxHeightOUT - $(this).find('.gridBuddyBoxWords').height();
					var imgHeightSTR = imgHeight + 'px';
					$(this).find('.gridBuddyBoxThumbnail').css('height', imgHeightSTR);
				}
			});
		}
	}

	//set column width in accordance to container and number of column selections
	function setColWidth(){
		var numCol = numColSelectOUT;
		var containerWidth = $('.gridBuddyWrapper').width();		  //missing pixels slightly here??
		var oneCol = Math.round(containerWidth/numCol)-gutterWidthOUT;
		var gridBoxWidth = oneCol;
		$('.gridBuddyBox').css("width", gridBoxWidth);
		return oneCol;		
	}

	//initilize packery container
	var $container = $('.gridBuddyWrapper').packery({	
		itemSelector: '.gridBuddyBox',
		gutter: gutterWidthOUT,
		columnWidth: setColWidth(),
		isInitLayout: false
	});
	
	//stamp added widget areas
	$('.registeredWidget').each(function(i, obj){
		$container.packery('stamp', obj);
	});
	
	//If all images are loaded, init Packery
	var imgCount = $("img").length;
	$("img").one("load", function() {
		imgCount--;
		if (imgCount === 0){
			setStyles();
			setMaxBoxHeight();
			$container.packery();
		}
	}).each(function() {
		if(this.complete) $(this).load();
	});
});