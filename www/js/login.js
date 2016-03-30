$(function() {

  $('#signup-link').click(function() {
    $('#login-box').hide();
    $('#signup-box').show();
    $('#signup-box input[name="email"]').focus();
    return false;
  });

  $('#login-link').click(function() {
    $('#signup-box').hide();
    $('#login-box').show();
    $('#login-box input[name="email"]').focus();
    return false;
  });

});
