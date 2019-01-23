$(document).ready(function() {
        // Transition effect for navbar 
        $(window).scroll(function() {
          if($(this).scrollTop() > $('.nav-fade').height()) { 
              $('.navbar').addClass('bg-primary');
              $('.navbar').removeClass('bg-transparent');
          } else {
              $('.navbar').removeClass('bg-primary');
              $('.navbar').addClass('bg-transparent');
          }
        });
});