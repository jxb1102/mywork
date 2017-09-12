// JavaScript Document
var n = $(".nav-box .show-box:visible ul:visible").index();
var m = $(".nav-box .show-box:visible ul:last").index();
function prevGroup(){
	$(".nav-box .show-box:visible ul").hide();
	if(n > 0){
		$(".nav-box .show-box:visible ul").eq(n - 1).show();
		n = n - 1;
		$(".nav-box .show-box:visible .rolling-box em").removeClass("current").eq(n).addClass("current");
	}else{
		$(".nav-box .show-box:visible ul:last").show();
		n = m;
		$(".nav-box .show-box:visible .rolling-box em").removeClass("current").eq(n).addClass("current");
		}
	}
function nextGroup(){
	$(".nav-box .show-box:visible ul").hide();
	if(n < m){
		$(".nav-box .show-box:visible ul").eq(n + 1).show();
		n = n + 1;
		$(".nav-box .show-box:visible .rolling-box em").removeClass("current").eq(n).addClass("current");
		
	}else{
		$(".nav-box .show-box:visible ul:first").show();
		n = 0;
		$(".nav-box .show-box:visible .rolling-box em").removeClass("current").eq(n).addClass("current");
		}
	}
$(document).ready(function(){
	$(".nav-tab li").mouseover(function(){
		$(this).addClass("focus");
		});
	$(".nav-tab li").mouseout(function(){
		$(this).removeClass("focus");
		});
	$('.nav-tab li').eq(0).addClass('current');
	$(".nav-tab li").click(function(){
		$(".nav-tab li").removeClass("current");
		$(this).addClass("current");
		$(".show-box").hide();
		$(".show-box").eq($(this).index()).show();
		m = $(".nav-box .show-box:visible ul:last").index();
		});
	$(".rolling-box em").eq(0).addClass("current");
	$(".rolling-box em").click(function(){
		$(".nav-box .show-box:visible .rolling-box em").removeClass("current");
		$(this).addClass("current");
		n = $(this).index();
		$(".nav-box .show-box:visible ul").hide().eq(n).show();
		});
	});