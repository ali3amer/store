$(function(){"use strict";$(".toggle-sidebar").on("click",function(){$(".content-area, .sidebar").toggleClass("no-sidebar")}),$(".toggle-submenu").on("click",function(){$(this).find(".fa-angle-right").toggleClass("down"),$(this).next(".child-links").slideToggle()}),$(".toggle-fullscreen").on("click",function(){$(this).toggleClass("full-screen"),$(this).hasClass("full-screen")?t.requestFullscreen?t.requestFullscreen():t.mozRequestFullScreen?t.mozRequestFullScreen():t.webkitRequestFullscreen?t.webkitRequestFullscreen():t.msRequestFullscreen&&t.msRequestFullscreen():document.exitFullscreen?document.exitFullscreen():document.mozCancelFullScreen?document.mozCancelFullScreen():document.webkitExitFullscreen?document.webkitExitFullscreen():document.msExitFullscreen&&document.msExitFullscreen()}),$(".toggle-settings").on("click",function(){$(this).find("i").toggleClass("fa-spin"),$(this).parent().toggleClass("hide-settings")});var e=[];$(".color-options li").each(function(){e.push($(this).data("theme"))}),$(".color-options li").on("click",function(){$(this).addClass("active").siblings().removeClass("active"),$("body").removeClass(e.join(" ")).addClass($(this).data("theme"))});var s=[];$(".font-options select option").each(function(){s.push($(this).val())}),$(".font-options select").on("change",function(){$("body").removeClass(s.join(" ")).addClass($(this).find("option:selected").val())});var t=document.documentElement});