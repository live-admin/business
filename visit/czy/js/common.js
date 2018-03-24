/*E家访公共JS*/
(function($) {

    /*扩展通用方法*/
    $.extend(window, {
        
        isMobileNumber: function(mobile) {
            return /^1[3-9]\d{9}$/.test(mobile);
        },
		getUrl : function(){
			var url = "http://evisit.czytest.colourlife.com";
			return url;
		},
		GetRequest:function() {
		   var url = location.search; //获取url中"?"符后的字串  
		   var theRequest;  
		   if (url.indexOf("?") != -1) {  
		      var theRequest = decodeURIComponent(url.substr(1));   
		   }  
		   return theRequest;  
		}, 
		/**
		 * 显示提示信息
		 * @param string msg 显示的信息
		 * @param int delay 显示延时
		 */
		toastMsg : function(msg, delay)
		{
			clearToastMsg();
			
			if(typeof(delay) === "undefined" || delay === null || delay <= 0)
			{
				delay = 2000;
			}
			var animateTime = 500;
			delay += animateTime;
			
			var toast = $("<div></div>").addClass("msg-toast");
			toast.html(msg);
			
			$("body").append(toast);
			
			toast.animate(
				{
					bottom: "5em",
					opacity: 'show'
				}, animateTime
			);
			
			setTimeout(function(){
				toast.animate(
					{
						bottom: "0em",
						opacity: 'hide'
					},
					animateTime,
					function(){
						toast.remove();
					});
				},
				delay);
		},
		
		clearToastMsg : function()
		{
			$(".msg-toast").remove();
		}
	});

    $(function() {
    	

    });
})(jQuery);