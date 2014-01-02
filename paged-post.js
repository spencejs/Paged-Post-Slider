// remap jQuery to $
(function($){

})(window.jQuery);

/*
 * jQuery hashchange event - v1.3 - 7/21/2010
 * http://benalman.com/projects/jquery-hashchange-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);

jQuery(document).ready(function($) {

var address = window.location.href.replace(window.location.hash,'');

	// Bind the event.
	$(window).hashchange( function(){
	//set the value as a variable, and remove the #
		var hash_value = window.location.hash.replace('#', '');
		$.ajax({
			url:window.location.pathname + window.location.search + hash_value,
			dataType:'html',
			type: 'post',
			success:function(res){
				$(".pps-wrap-content").html($(res).find(".pps-the-content").fadeIn('slow').addClass('done'));
				$('.pps-slider-nav a').bind('click',ajaxNav);
				if (pps_options_object.scroll_up) {
					$('html, body').animate({
						scrollTop: 0
					}, 2000);
				};
			}
		});
	});

	// Trigger the event (useful on page load).
	if ($(".pps-slider-nav").length > 0) {
		$(window).hashchange();
		};

if(address.indexOf("?p=") > -1) {

		//Default Permalink Structure
		var ajaxNav = function(event) {
			var url = this.href;
			var parts = url.split("&");
			var result = '&' + parts[parts.length-1];
			if(typeof result != 'undefined' && result == 'pagination'){
				var result = '';
				}
			event.preventDefault();
			location.hash = result; 
			}

	} else if(address.slice(-1) === "/") {

		//Trailing Slash Permalink Structure
		var ajaxNav = function(event) {
			var url = this.href;
			var parts = url.split("/");
			var result = parts[parts.length-2];
			if(typeof result != 'undefined' && result == 'pagination'){
				var result = '';
				}
			event.preventDefault();
			location.hash = result; 
			}

	} else if(address.slice(-5) === ".html" || address.slice(-4) === ".htm") {

		//Trailing Slash Permalink Structure
		var ajaxNav = function(event) {
			var url = this.href;
			var parts = url.split("/");
			var result = '/' + parts[parts.length-1];
			if(typeof result != 'undefined' && result == 'pagination'){
				var result = '';
				}
			event.preventDefault();
			if(url.slice(-5) === ".html" || url.slice(-4) === ".htm"){
				location.hash = '';
				} else {
				location.hash = result; 
				}
			}

	} else {

		//Most likely a permalink structure with no trailing slash
		var ajaxNav = function(event) {
			var url = this.href;
			var parts = url.split("/");
			var result = '/' + parts[parts.length-1];
			if(typeof result != 'undefined' && result == 'pagination'){
				var result = '';
				}
			event.preventDefault();
			location.hash = result; 
			}

		}

	$('.pps-slider-nav a').bind('click',ajaxNav);

});