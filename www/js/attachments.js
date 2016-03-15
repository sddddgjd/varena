$(function() {
  $('.btn-file :file').change(onFileSelect);

  $('#master-checkbox').click(function() {
    var state = $(this).prop('checked');
    $('[name="attachmentIds[]"]').prop('checked', state);
  });
});
