/*
window.onYouTubeIframeAPIReady = function() {
	console.log('Player init with video: ' + window.ID);
	
	window.player = new YT.Player(window.ID, {
		height: '267',
		width: '320',
		videoId: window.ID,
		events: {
		  'onReady': onPlayerReady,
		//  'onStateChange': onPlayerStateChange
		},
		playerVars: {
			autoplay: 1
		}
	});
};


window.onPlayerReady = function(event) {

	var replay = document.getElementById(window.ID);
	replay.addEventListener('click', function() {
		window.player.playVideo();
	});
};
*/

// BB Dev: creating thumb images from youtube videos and loadin them on click
(function($) {
	$.fn.ytbthumb_plugin = $(window).load( function () {
	var ytbURL; 		// youtube URL
	var ytbID;		// youtube ID
	var ytbImg;		// URL of thumb image of youtube video
	var tableTemplate = '<table border="0" class="ytbTbl"><tr><td class="ytbObject"></td><td class="ytbText" align="left" valign="top"></td></tr>';	// Table template to format post dynamically on display
	var thisTable; 	// Current table
		
	$.getScript( "https://www.youtube.com/iframe_api")
		.done(function( script, textStatus ) {
		console.log(script);
		console.log(textStatus);
	});
	
	$("iframe").not('#wpnt-notes-iframe2').each(function(){
		
		//Getting youtube ID from the link
		ytbURL = $(this).attr('src');
		try { 	
			ytbID = ytbURL.match('(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})');
			ytbImg = "https://img.youtube.com/vi/"+ytbID[1]+"/default.jpg";
 
			//window.ID = ytbID[1];		
			
			//building container to for youtube image
			$(this).before('<div class="youtoobin" id='+ ytbID[1] +' align="left"></div>');
			$(this).prev(".youtoobin").append('<div class="thumby_iframe" style="background-image:url(' + ytbImg +'); width:120px; height:90px;cursor:pointer"><img style="margin:31px 38px" src="/myscripts/mini-play.png"/></div>');	
			
			// building dynamic table that wraps youtube object and its text
			thisTable = $(tableTemplate).insertBefore($(this).prev(".youtoobin"));
			thisTable.find(".ytbText").html($(this).parent().prev("p"));
			thisTable.find(".ytbObject").html($(this).prev(".youtoobin"));
		} catch(e){
			if ( e instanceof TypeError){
				console.error('Bad youtube link or iframe' + ytbURL + '. skipping...');
			}
		}
	});
	
	// Listening to click event
	$(".thumby_iframe").on("click", function() {
		
		var thisYtb = jQuery(this).parent(); // current youtube div container
		window.ID = thisYtb.attr('id');
		thisYtb.css('display', 'block');
		//window.player.playVideo();i
		new YT.Player(window.ID, {
			height: '267',
			width: '320',
			videoId: window.ID,
			playerVars: {
				rel: 0,
				autoplay: 1
			}
		})
	});
   });	
}( jQuery )); //end function
