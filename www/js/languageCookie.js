$(function() {
    var language = $.cookie( 'language' );
    !language || $('#languageSelector').val( language );
    $('#languageSelector').on('change', function() {
        language = this.value;
        $.cookie( 'language', language );
    })
    .change();
});