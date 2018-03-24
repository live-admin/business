(function($){
	
	
	$(function(){
	
		$(".get_prize").click(function(){
			$(".prize_win").show();	
			
			$(".prize_win dl").css("margin-top", "-10em");
			
			$(".prize_win dl").animate(
				{
					"margin-top":"50%"				
				}, 600 , function(){			
					
					$(".prize_win").one("click",function(){
						window.location.href = commentUrl; 
					});
					
					setTimeout(function(){window.location.href = commentUrl; }, 6000);
				}
			);
		});
	});	
})(jQuery);

