$(function() {
  $("#ep_ul").addClass("open");
  $("#ep_ul").children("ul").slideDown()

  $('#player-container').find('iframe').each(function(){
      // $(this).attr("width", "550");
      // $(this).attr("height", "320");

      // $(this).load( function(){
      //   $(this).contents().find('div').attr("width", "550");
      // });

      // $(this).contents().find('body').css({'width' : '550px'});
  });
  $('#player-container').find('embed').each(function(){
    // $(this).attr("width", "550");
    // $(this).attr("height", "320");
  });

});
