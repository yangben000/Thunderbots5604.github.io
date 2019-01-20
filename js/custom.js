$(document).ready(function() {
        // Transition effect for navbar 
        $(window).scroll(function() {
          // checks if window is scrolled more than 500px, adds/removes solid class
          if($(this).scrollTop() > $(document).height() * .4) { 
              $('.navbar').addClass('bg-primary');
              $('.navbar').removeClass('bg-transparent');
          } else {
              $('.navbar').removeClass('bg-primary');
              $('.navbar').addClass('bg-transparent');
          }
        });
});