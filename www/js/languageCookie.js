$(function() {
  var language = $.cookie( 'language' );
  !language || $('#languageSelector').val( language );
  $('#languageSelector').on('change', function() {
      language = this.value;
      $.cookie( 'language', language );
      var sel=document.getElementById('languageSelector');
      window.location.href='changeLanguage.php?url=' + window.location.href+'&locale='+sel.value;       
  });
});