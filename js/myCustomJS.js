//scroll
(function($){
	$(function(){
		if( $("#wpadminbar").length || !$(".suffusion-custom").length )return;

		var $body = $("body") , $win = $(window) , $nav = $("#nav-top").clone().appendTo('body');
		$nav.addClass("animate nav-fixed out");

		setInterval(function(){
			var top = $body.scrollTop() || $win.scrollTop();

			$nav.toggleClass("in" , top > 300);
			
		},800);
	});
})(window.jQuery);