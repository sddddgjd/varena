$(function() {

  $('#signup-link').click(function() {
    $('#login-box').hide();
    $('#signup-box').show();
    return false;
  });

  $('#login-link').click(function() {
    $('#signup-box').hide();
    $('#login-box').show();
    return false;
  });

});
