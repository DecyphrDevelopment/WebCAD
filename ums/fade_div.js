var mouseX;
var mouseY;

window.onload = function() {
    $(document).mousemove( function(e) {
        mouseX = e.pageX; 
        mouseY = e.pageY;
     });  
     $("#spanhovering").mouseover(function(){
       $('#divtoshow').css({'top':mouseY,'left':mouseX}).fadeIn('slow');
     });
  };