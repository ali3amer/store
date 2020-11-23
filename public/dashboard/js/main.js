$(function () {
    'use strict';
    $('.toggle-sidebar').on('click', function () {
        $(".content-area, .sidebar").toggleClass("no-sidebar");
    });
    $(".toggle-submenu").on("click", function() {
      $(this)
        .find(".fa-angle-right")
        .toggleClass("down");
      $(this)
        .next(".child-links")
        .slideToggle();
    });

    $(".toggle-fullscreen").on("click", function () {
      $(this).toggleClass('full-screen');
      if($(this).hasClass('full-screen')) {
        openFullscreen();
      } else {
        closeFullscreen();
      }
    });

    $(".toggle-settings").on("click", function() {
      $(this).find("i").toggleClass("fa-spin");
      $(this)
        .parent()
        .toggleClass("hide-settings");
    });

    var themesClasses = [];

    $(".color-options li").each(function() {
      themesClasses.push($(this).data("theme"));
    });

    $(".color-options li").on("click", function () {
      $(this).addClass("active").siblings().removeClass("active");
      $("body")
        .removeClass(themesClasses.join(" "))
        .addClass($(this).data("theme"));
    });

    var fontClasses = [];
    $(".font-options select option").each(function() {
      fontClasses.push($(this).val());
    });

    $(".font-options select").on("change", function() {
      $("body")
        .removeClass(fontClasses.join(" "))
        .addClass(
          $(this)
            .find("option:selected")
            .val()
        );
    });



    /* Get the documentElement (<html>) to display the page in fullscreen */
    var elem = document.documentElement;

    /* View in fullscreen */
    function openFullscreen() {
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
      } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
        elem.webkitRequestFullscreen();
      } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
      }
    }

    /* Close fullscreen */
    function closeFullscreen() {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.mozCancelFullScreen) { /* Firefox */
        document.mozCancelFullScreen();
      } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) { /* IE/Edge */
        document.msExitFullscreen();
      }
    }



});
// document.getElementById("btnPrint").onclick = function () {
//     printElement(document.getElementById("printThis"));
// }
//
// function printElement(elem) {
//     var domClone = elem.cloneNode(true);
//
//     var $printSection = document.getElementById("printSection");
//
//     if (!$printSection) {
//         var $printSection = document.createElement("div");
//         $printSection.id = "printSection";
//         document.body.appendChild($printSection);
//     }
//
//     $printSection.innerHTML = "";
//     $printSection.appendChild(domClone);
//     window.print();
// }
