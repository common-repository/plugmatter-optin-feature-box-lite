jQuery(document).ready(function($){
	$(".pmfb_tab").on("click", function(e){
		e.preventDefault();
		$(this).parent().siblings().removeClass('pmfb_active_tab');
		$(this).parent().addClass('pmfb_active_tab');
		$('.pmfb_theme_item').removeClass('pmfb_theme_item_active');
		$($(this).attr('href')).addClass('pmfb_theme_item_active');
	} );

	$("#pmfb_code_verify").on('click',function(){
		setTimeout(function(){
    		location.reload();
		},1500);
	});
	
});