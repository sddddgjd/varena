$(function() {
  $('.btn-file :file').change(function() {
    var maxSize = $(this).data('max-size');
    var count = this.files.length;

    for (var i = 0; i < count; i++) {
      if (this.files[i].size > maxSize) {
        alert('The file size limit is ' + maxSize + ' bytes');
        return false;
      }
    }

    // Replace some filename clutter
    var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');

    var text = $(this).parents('.input-group').find(':text');
    var msg = count > 1 ? count + ' files selected' : label;
    text.val(msg);
  });

  $('a.deleteAttachment').click(function() {
    var name = $(this).closest('td').siblings().first().text();
    return confirm('Really delete «' + name + '»?');
  });
});
