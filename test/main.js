function loadLog(url){		
		var oldscrollHeight = $("#main_chat_area").attr("scrollHeight") - 20; //Scroll height before the request
		$.ajax({
			url: url,
			cache: false,
			success: function(html){		
				$("#main_chat_area").html(html); //Insert chat log into the #main_chat_area div	
				
				//Auto-scroll			
				var newscrollHeight = $("#main_chat_area").attr("scrollHeight") - 20; //Scroll height after the request
				if(newscrollHeight > oldscrollHeight){
					$("#main_chat_area").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				}				
		  	},
		});
	}