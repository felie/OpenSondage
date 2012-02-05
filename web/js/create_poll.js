var execute = function ()
{
  // Go to page 2
  $('#create-poll-step1 button[name=next]').click(function(){
    if (checkStep1() == true) {
      $('#create-poll-step1').animate({
        marginLeft: '-50%'
      }, 'slow');
    }
    return false;
  });
  
  // Return to page 1
  $('#create-poll-step2 button[name=previous]').click(function(){
    $('#create-poll-step1').animate({
      marginLeft: '0%'
    }, 'slow');
    return false;
  });
  
  // Check field's poll
  $('#create-poll-step2 button[name=submit]').click(function(){
    if (checkStep2() == false) {
      $('#create-poll-step2 .form').prepend('<div class="error">Vous devez entrer au moins une question</div>');
      return false;
    }
    
    return true;
  });
  
  // Add field
  $('#create-poll-step2 #add-fields').click(function(){
    var html = '';
    var default_field = '<div class="field"><label for="question_%%i%%" class="">Choix %%j%%</label><input type="text" name="question[%%i%%]" id="question_%%i%%" value="" class="" /></div>';
    var current_id = parseInt(/\[([0-9]+)\]/.exec($('#create-poll-step2 .field input:last').attr('name'))[1])
    for (var i = current_id + 1; i <= current_id + 10; i++) {
      html += default_field.replace(/%%i%%/g, i).replace(/%%j%%/g, i + 1);
    }
    $('#create-poll-step2 .form').append(html);
    return false;
  });
  
  // Hide input 'value of maybe" if maybe_authorized isn't selected
  $('#maybe_authorized').change(function(){
    if (this.checked == true) {
      $('.field.maybe').fadeIn();
    } else {
      $('.field.maybe').fadeOut();
    }
  });
}

function checkStep1()
{
  var error = false;
  if (isEmpty('#name')) {
    error = true;
    addError('#name', 'Le titre du sondage est obligatoire');
  } else {
    removeError('#name');
  }
  
  if (isEmpty('#username')) {
    error = true;
    addError('#username', 'Votre nom est obligatoire');
  } else {
    removeError('#username');
  }
  
  if (isEmpty('#mail')) {
    error = true;
    addError('#mail', 'Votre adresse e-mail est obligatoire');
  } else {
    removeError('#mail');
  }
  if (isEmpty('#mail') === false && /^([a-zA-Z0-9])+([\.a-zA-Z0-9_\+-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/.test($('#mail').val()) == false) {
    error = true;
    addError('#mail', 'Votre adresse e-mail n\'est pas valide');
  } else if (isEmpty('#mail') === false) {
    removeError('#mail');
  }
  
  return !error;
}

function checkStep2()
{
  var error = true;
  
  $('#create-poll-step2 .field input').each(function(){
    if (isEmpty($(this)) == false) {
      error = false;
      return;
    }
  });
  
  return !error;
}

function addError(field, error)
{
  removeError(field)
  $(field).parent().append('<div class="error">' + error + '</div>');
}

function removeError(field)
{
  $(field).parent().find('.error').remove();
}