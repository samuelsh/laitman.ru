// BB Dev: creating thumb images from youtube videos and loadin them on click
jQuery(function() {
	var $ytbURL; 		// youtube URL
	var $ytbID;		// youtube ID
	var $ytbImg;		// URL of thumb image of youtube video
	var $tableTemplate = '<table border="0" class="ytbTbl"><tr><td class="ytbObject"></td><td class="ytbText" align="left" valign="top"></td></tr>';	// Table template to format post dynamically on display
	var $thisTable; 	// Current table
	
	//jQuery("object").hide();
	

	
	jQuery("object").each(function(){
		
		//Getting youtube ID from the link
		var $params = jQuery(this).children('param');
		$params.each(function() {
			if((jQuery(this).attr('name')) == 'src') {
				$ytbURL = jQuery(this).val();
				return;
			}
		});
		
		$ytbID = $ytbURL.match("(.+?)(\/v/)([a-zA-Z0-9_-]{11})+");
		$ytbImg = "http://img.youtube.com/vi/"+$ytbID[3]+"/default.jpg";
		
		//building container to for youtube image
		jQuery(this).before('<div class="youtoobin" align="left"></div>');
		jQuery(this).prev(".youtoobin").append('<div class="thumby" style="background-image:url(' + $ytbImg +'); width:120px; height:90px;cursor:pointer"><img style="margin:31px 38px" src="/myscripts/mini-play.png"/></div>');	
		
		// building dynamic table that wraps youtube object and its text
		$thisTable = jQuery($tableTemplate).insertBefore(jQuery(this).prev(".youtoobin"));
		$thisTable.find(".ytbText").html(jQuery(this).parent().prev("p"));
		$thisTable.find(".ytbObject").html(jQuery(this).prev(".youtoobin"));
	});
	
	// Listening to click event
	jQuery(".thumby").live("click", function() {
		var $thisObject = jQuery(this).parent(); // current youtube div container
		var $oldObject = $thisObject.parents().eq(4).children('object'); // hidden flash object	
		var $URL;
		
		// Getting flash URL 
		$oldObject.children('param').each(function() {
			if((jQuery(this).attr('name')) == 'src') {
				$URL = jQuery(this).val();
				return;
			}
		});
			
            	// Generating unique ID for current object so we could work with them lately
            	var $obj_id = 'obj_' + Math.floor(Math.random() * 1000);
            	$thisObject.attr('id',$obj_id);
            	
            	// Here we go - palying video!
            	jQuery('#' + $obj_id).flash({ 
                swf: $URL,
                params: { allowScriptAccess: "always"},   
                flashvars: {enablejsapi: '1', autoplay: '1', allowScriptAccess: "always", id: 'ytPlayer' },   
                height: 267,   width: 320 });
                
                $thisObject.children('object').css('display','block');
	});
	
}); //end function
