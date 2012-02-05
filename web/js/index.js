var execute = function ()
{
  // Effect when you go to the buttons
  $('.index button').hover(
    function(){
      $('img[rel=' + $(this).attr('id') + ']').removeClass('opacity');
    },
    function(){
      $('img[rel=' + $(this).attr('id') + ']').addClass('opacity');
    }
  );
  
  // submit form hen you click in the image
  $('.valid-choice').click(function(){
    $('#' + $(this).attr('rel')).click();
  });
}