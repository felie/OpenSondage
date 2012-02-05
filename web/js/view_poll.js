var execute = function ()
{
  var ligne = new Array();
  
  // View Poll
  //$('#form-response-poll .poll td .edit').hide();
  
  // View Poll new answer
  $('#form-response-poll .poll tr.new-response td .edit').show();
  
  // Edit response
  $('#form-response-poll .poll td.edit .response').click(function(){
    ligne[$(this).parent().parent().attr('id')] = $(this).parent().parent().html();
    $(this).parent().parent().find('td .response').hide();
    $(this).parent().parent().find('td .edit').fadeIn();
  });
  
  // Cancel edit
  $('#form-response-poll .poll td.edit .cancel').click(function(){
    $(this).parent().parent().parent().find('td .edit').hide();
    $(this).parent().parent().parent().find('td .response').show();
    $(this).parent().parent().parent().html(ligne[$(this).parent().parent().parent().attr('id')]);
  });
  
  // Submit edit
  $('#form-response-poll .poll td.edit .save').click(function(){
    // show validation loader
    var loader = $(this).parent().parent().find('.loading');
    loader.show();
    
    var url = $('#form-response-poll').attr('action');
    var method = $('#form-response-poll').attr('method');
    var datas = '';
    
    $(this).parent().parent().parent().find('input:checked').each(function(){
      var name = $(this).attr('name');
      var value = $(this).val();
      if (datas != '') {
        datas += '&';
      }
      datas += name + '=' + value;
    });
    
    $.ajax({
      url: url,
      type: method,
      data: datas,
      success: function (data) {
        
        loader.hide();
      }
    });
  });
  
  // change selection
  $('.edit label').click(function(){
    $(this).parent().find('label').removeClass('selected');
    $(this).addClass('selected');
    
    $(this).parent().parent().removeClass('yes');
    $(this).parent().parent().removeClass('no');
    $(this).parent().parent().removeClass('maybe');
    $(this).parent().parent().addClass($(this).find('input').val());
  });
  
  // new response
  $('.new-response label').click(function(){
    $(this).parent().find('label').removeClass('selected');
    $(this).addClass('selected');
    
    $(this).parent().removeClass('yes');
    $(this).parent().removeClass('no');
    $(this).parent().removeClass('maybe');
    $(this).parent().addClass($(this).find('input').val());
    return true;
  });
}