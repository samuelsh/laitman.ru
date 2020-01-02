// BB Dev: Following script implements dynamic slideUp/slideDown for post or part of the post
jQuery(function() {
	var $closedThread = true;
	var $toggleLinkOn = "Далее...";
	var $toggleLinkOff = "Свернуть...";
	
	jQuery(".a-link-toggle").text($toggleLinkOn);
	jQuery(".a-link-toggle").show();
	jQuery(".hidden_post").after('<p style="text-align:right;"><a class="a-link-toggle" style="color:#000080; display: none;">Свернуть...</a></p>');
	jQuery(".hidden_post").hide();
	
	jQuery(".a-link-toggle").click(function() {
			if(jQuery(this).text() == $toggleLinkOn) { //clicking on opening link
					var $thisLink = jQuery(this);
     					jQuery(this).hide();
     					jQuery(this).parent().next(".hidden_post").slideDown("slow", function(){
						$thisLink.parent().next(".hidden_post").next("p").children(".a-link-toggle").show();
     					});
     					
     					$closedThread = false;
     			}
     			
     			else if(jQuery(this).text() == $toggleLinkOff){ //clicking on closing link
     					$thisLink = jQuery(this);
     					jQuery(this).hide();
					jQuery(this).parent().prev(".hidden_post").slideUp("slow", function (){
						$thisLink.parent().prev(".hidden_post").prev("p").children(".a-link-toggle").show();	
					});
					
					$closedThread = true;
			}
     		}); //end click
}); //end
