$(document).ready(function(){
  execute();
});

function nothing(){}



function isEmpty(field)
{
  var value = $.trim($(field).val());
  
  if (value == '') {
    return true;
  } else {
    return false;
  }
}