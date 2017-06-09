/* 
 * Script		: smsmarketing.js
 * Created by	: Divyang
 * Created on	: OCTOBER-20-2016
 * @package AxilSolutions
 * @subpackage SMS-Marketing
 */

$(document).ready(function(){
	
	var $nav_btn		= $('.nav-toggle-btn'),
		$navbar_custom	= $('.navbar-custom');
	
	// Responsive Mobile Menu
	$nav_btn.click(function(){
		$navbar_custom.toggleClass('nav-show');
	});
	
	$(window).scroll(function() {
		var $navbar_custom = $('.navbar-custom').not('.keep-nav-small');
		
		// Responsive Menu
		if ($(this).scrollTop() > 40) { // this refers to window
			$navbar_custom.addClass('nav-small');
		}
		else
		{
			$navbar_custom.removeClass('nav-small');
		}
		
		/*var $animated_objs = $('.animate-ltr, .animate-rtl'),
			$bottom_of_window = $(this).scrollTop() + $(this).height();
		
		if ($animated_objs.length) {		// If selected class/id exist 
			
			$animated_objs.each(function(){
				
				var	$bottom_of_obj = $(this).offset().top + $(this).outerHeight();

				// If the object is completely visible in the window, animate arrow it
				if( $bottom_of_window > $bottom_of_obj ){
					if( $( this ).hasClass( 'animate-ltr' ) )
					{
						$(this).addClass('animate-slide-in-left').removeClass('animate-ltr');
					}
					else if($( this ).hasClass( 'animate-rtl' ))
					{
						$(this).addClass('animate-slide-in-right').removeClass('animate-rtl');
					}
					else {}
				}
			});
		}*/
		
		if( $(window).width() > 767 )
		{
			var $animated_objs = $('.section-bg'),
			$bottom_of_window = $(this).scrollTop() + $(this).height();
		
			if ($animated_objs.length) {		// If selected class/id exist 
				
				$animated_objs.each(function(){
					
					var	$bottom_of_obj = $(this).offset().top + $(this).outerHeight();

					/* If the object is completely visible in the window, animate arrow it */
					if( $bottom_of_window > $bottom_of_obj ){
						$( this ).find( '.animate-ltr' ).addClass('animate-slide-in-left').removeClass('animate-ltr');
						$( this ).find( '.animate-rtl' ).addClass('animate-slide-in-right').removeClass('animate-rtl');
					}
				});
			}
		}
		else
		{
			var $animated_objs = $('.animate-ltr, .animate-rtl'),
			$bottom_of_window = $(this).scrollTop() + $(this).height();
		
			if ($animated_objs.length) {		// If selected class/id exist 
			
				$animated_objs.each(function(){
					
					var	$bottom_of_obj = $(this).offset().top + $(this).outerHeight();

					// If the object is completely visible in the window, animate arrow it
					if( $bottom_of_window > $bottom_of_obj ){
						if( $( this ).hasClass( 'animate-ltr' ) )
						{
							$(this).addClass('animate-slide-in-left').removeClass('animate-ltr');
						}
						else if($( this ).hasClass( 'animate-rtl' ))
						{
							$(this).addClass('animate-slide-in-right').removeClass('animate-rtl');
						}
						else {}
					}
				});
			}
		}
		
		
		
		
	});
	
	$(window).scroll();
});

		  