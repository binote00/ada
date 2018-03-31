// Redirect plugin - http://www.avramovic.info/razno/jquery/redirect/demo.php
(function(d){d.fn.redirect=function(a,b,c){void 0!==c?(c=c.toUpperCase(),"GET"!=c&&(c="POST")):c="POST";if(void 0===b||!1==b)b=d().parse_url(a),a=b.url,b=b.params;var e=d("<form></form");e.attr("method",c);e.attr("action",a);for(var f in b)a=d("<input />"),a.attr("type","hidden"),a.attr("name",f),a.attr("value",b[f]),a.appendTo(e);d("body").append(e);e.submit()};d.fn.parse_url=function(a){if(-1==a.indexOf("?"))return{url:a,params:{}};var b=a.split("?"),a=b[0],c={},b=b[1].split("&"),e={},d;for(d in b){var g= b[d].split("=");e[g[0]]=g[1]}c.url=a;c.params=e;return c}})(jQuery);

// App
$(function() {	
  	// Top bar - Scroll horizontal
  	$(window).scroll(function(){
        $('#navbar-menu-wrapper').css('left', 0 - $(this).scrollLeft());
    });

  	/* Open / close leftbar
  	$('#leftbar-minimizer').click(function(){

	  	if( $('#leftbar').hasClass('open') ){

			$('#leftbar').animate({
				left: 	'-251px'
			}, 150, function() {

				$('#leftbar').removeClass('open');
				$.removeCookie('leftmenu');

				$('#leftbar').animate({
					left: 	'0'
				}, 200);

				$('#page-wrapper').animate({
					marginLeft: 	'285px'
				}, 200);

			});

	  	}else{

		  	$('#leftbar').animate({
				left: 	'-251px'
			}, 200, function() {

				$('#leftbar').addClass('open');
				$.cookie('leftmenu', 'open', { expires: 365, path: '/' });

				$('#leftbar').animate({
					left: 	'-147px'
				}, 150);

				$('#page-wrapper').animate({
					marginLeft: 	'140px'
				}, 200);

			});

	  	}

  	});*/


  	// Hover menu - Pas en CSS parce que bug graphique sur le sous-menu
  	$('#navbar-menu-left > ul > li > a, #navbar-menu-right > ul > li > a').hover(function(){
	  	$(this).find('span').hide().fadeIn(900);
  	}, function(){
	  	$(this).find('span').fadeOut(400);
  	});


  	// Open / close account menu
  	$('#small-account-active').click(function(e){
  		e.preventDefault();

  		if( $( this ).hasClass('open') )
  			fadeOutAccountMenu( this );
  		else
  			fadeInAccountMenu( this );

	  	return false;
  	});


  	// Open / Close message shortcut menu
  	$('#message-shortcut-opener').click(function(e){
  		e.preventDefault();

	  	var parent = $( this ).parent();

	  	if( parent.hasClass('open') ){
		  	closeMessageShortcut( parent );
	  	}else{
		  	parent.addClass('open');
	  	}

	  	return false;
  	});


  	// Open / Close account menu
  	$('#account-change-title').click(function(e){
	  	e.preventDefault();

	  	var parent = $( this ).parent();

	  	if( parent.hasClass('open') ){
		  	closeAccountMenu( parent );
	  	}else{
	  		$( this ).children('.caret').addClass('caret-reversed');
		  	parent.addClass('open');
	  	}

	  	return false;
  	});

  	
  	// Ajax change account with post data
  	$('.post-data').click(function(){
	  	var postData = $(this).data('form').toString();
	  	$().redirect($(this).attr('href'), eval('('+postData+')') );
	  	
	  	return false;
  	});
  	
  	
  	// Reinitialize elements
  	$('body').click(function(){
		// Close the account menu
		fadeOutAccountMenu('#small-account-active');
		closeMessageShortcut( $('#message-shortcut') );
		closeAccountMenu( $('#account-change') );
	});
	
});

function fadeInAccountMenu( element ){
  	$( element ).addClass('open').children('.caret').addClass('caret-reversed');
  	$( element ).parent().children('ul').fadeIn(200);
}

function fadeOutAccountMenu( element ){
	$( element ).removeClass('open').children('.caret').removeClass('caret-reversed');
  	$( element ).parent().children('ul').fadeOut(200);
}

function closeMessageShortcut( element ){
	element.removeClass('open');
}

function closeAccountMenu( element ){
	element.removeClass('open');
	element.find('.caret').removeClass('caret-reversed');
}

$(".dropdown-toggle").click(function () {
    $(".nav-collapse").css('height', 'auto')
});