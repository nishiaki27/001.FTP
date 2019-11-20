$(document).ready(function() {
	$('.pagetop').hide();
	$(window).on("scroll", function() {
		if ($(this).scrollTop() > 100) {
			$('.pagetop').slideDown("fast");
		} else {
			$('.pagetop').slideUp("fast");
		}

		scrollHeight = $(document).height();
		scrollPosition = $(window).height() + $(window).scrollTop();
		footHeight = $('.footer').innerHeight();
		if ( scrollHeight - scrollPosition  <= footHeight ) {
			$('.pagetop').css({
				'position':'absolute',
				'bottom': footHeight,
			});
		} else {
			$('.pagetop').css({
				'position':'fixed',
				'bottom': '50px',
				'padding': '0px'
			});
		}
	});
});

// 透過
$(function() {
	$('.overimg').hover(
		function(){
			$(this).stop().animate({opacity: 0.6}, 'fast');
		},function(){
			$(this).stop().animate({opacity: 1}, 'fast');
	});
});

$(function(){
	$('#menu2 li a').each(function(){
		// リンク先のURLを取得
		var url = $(this).attr('href');
		// 現在表示されているページのURLを取得し、リンク先のURLと照合
		if(location.href == url) {
			// マッチすれば、「class="current"」を付加
			$(this).addClass('current');
			// マッチしなければ、「class="current"」を削除
		} else {
			$(this).removeClass('current');
		}
	});
});


// 検索
$(function(){
	$('.nav_search #text').focus(function(){
		if((location.href).indexOf('/housing_aircon/') != -1){
			if(this.value == '住宅用エアコン型番') {
				$(this).val('').css('color','#000000');
			}
		}else{
			if(this.value == '業務用エアコン型番') {
				$(this).val('').css('color','#000000');
			}
		}
		$(this).css('color','#000000');
	});
	$('.nav_search #text').blur(function(){
		if((location.href).indexOf('/housing_aircon/') != -1){
			if(this.value == '') {
				$(this).val('住宅用エアコン型番').css('color','#999999');
			}
		}else{
			if(this.value == '') {
				$(this).val('業務用エアコン型番').css('color','#999999');
			}
		}
	});
});

$(function() {
	$('.search_top :checkbox[name="search_maker_all"]').on('click', function() {
		$(':checkbox[name="search_maker[]"]').prop('checked', this.checked);
	});

	$('.search_top :checkbox[name="search_group_all"]').on('click', function() {
		$(':checkbox[name="search_group[]"]').prop('checked', this.checked);
	});

	$('.search_top .ac_checkbox').on('click', function() {
		if ($('.search_maker :checked').length == $('.search_maker :checkbox').length){
			$(':checkbox[name="search_maker_all"]').prop('checked', 'checked');
		}else{
			$(':checkbox[name="search_maker_all"]').prop('checked', false);
		}

		if ($('.search_group :checked').length == $('.search_group :checkbox').length){
			$(':checkbox[name="search_group_all"]').prop('checked', 'checked');
		}else{
			$(':checkbox[name="search_group_all"]').prop('checked', false);
		}
	});
});

$(function(){
	$('.column_explorer #text').focus(function(){
		if(this.value == 'キーワード入力') {
			$(this).val('').css('color','#000000');
		}
	});
	$('.column_explorer #text').blur(function(){
		if(this.value == '') {
			$(this).val('キーワード入力').css('color','#999999');
		}
	});
});

$(function(){
	$('.boxlink').click(function(){
		if($(this).find('a').attr('target') == '_blank'){
			window.open($(this).find('a').attr('href'), '_blank');
		} else {
			window.location = $(this).find('a').attr('href');
		}
	return false;
	});
});


$(function(){
	$('.img_change').each(function(){
		var set = $(this);
		var btn = set.find('ul.thum li img');
		var image = set.find('.ic_main_img img');
		var image_s = $('.ic_main_img img').attr('src');
		// hover時
		$(btn).hover(
			function(){
				$(image).attr('src',$(this).attr('src')).fadeIn();
			},function(){
				$(image).attr('src',image_s).fadeIn();
			}
		);
	});
});


$(function(){
	var index = 0;
	if ($.cookie('index')) {
		index = $.cookie('index');
		$('.tab ul li').removeClass('active').eq(index).addClass('active');
		$('.panel').hide().eq(index).show();
	}

	$('.tab ul li').click(function() {
		if (index != $('.tab ul li').index(this)) {
			index = $('.tab ul li').index(this);
			$('.panel').hide().eq(index).fadeIn('fast');
			$('.tab ul li').removeClass('active').eq(index).addClass('active');
			$.cookie('index', index, { expires: 1 });
		}
	});
});


$(function(){
	$('a[href^=#]').click(function(){
		var speed = 500;
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top;
		$("html, body").animate({scrollTop:position}, speed, "swing");
		return false;
	});
});

$(function(){
	$('.form .ac_btn').on('click', function() {
		$(this).next().slideToggle();
		$(this).toggleClass('active');
	});
});

$(function(){
	$('.ac_btn_ab').on('click', function() {
		$(this).next().slideToggle();
		$(this).toggleClass('active');
	});
});


$(function(){
	$(".menubtn").click(function(){
		$("#menu2").slideToggle();
		$("#menu2").toggleClass('togmenu');
	});	
});

$(function() {
	var timer = false;
	$(window).resize(function() {
		if (timer !== false) {
			clearTimeout(timer);
		}
		timer = setTimeout(function() {
			if($(window).width()>=480){
				$(".aaa").next(".bbb").removeAttr("style");
			}
		}, 200);
	});
	$(".aaa").click(function() {
		if($(window).width()<480){
			$(this).next(".bbb").slideToggle();
		}
	});
});

// スマホ入替時必要
$(function(){
	$('.img_change2').each(function(){
		var set = $(this);
		var btn = set.find('ul.thum2 li img');
		var image = set.find('.ic_main_img2 img');
		var image_s = $('.ic_main_img2 img').attr('src');
		// hover時
		$(btn).hover(
			function(){
				$(image).attr('src',$(this).attr('src')).fadeIn();
			},function(){
				$(image).attr('src',image_s).fadeIn();
			}
		);
	});
});
// タブレットでプルダウン
;(function( $, window, document, undefined )
{
	$.fn.doubleTapToGo = function( params )
	{
		if( !( 'ontouchstart' in window ) &&
			!navigator.msMaxTouchPoints &&
			!navigator.userAgent.toLowerCase().match( /windows phone os 7/i ) ) return false;

		this.each( function()
		{
			var curItem = false;

			$( this ).on( 'click', function( e )
			{
				var item = $( this );
				if( item[ 0 ] != curItem[ 0 ] )
				{
					e.preventDefault();
					curItem = item;
				}
			});

			$( document ).on( 'click touchstart MSPointerDown', function( e )
			{
				var resetItem = true,
					parents	  = $( e.target ).parents();

				for( var i = 0; i < parents.length; i++ )
					if( parents[ i ] == curItem[ 0 ] )
						resetItem = false;

				if( resetItem )
					curItem = false;
			});
		});
		return this;
	};
})( jQuery, window, document );

$( function()
 {
 $( '.menu_s li:has(ul)' ).doubleTapToGo();
 });
