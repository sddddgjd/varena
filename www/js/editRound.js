$(function() {
  // Read the timestamp, then initialize the datetimepicker with a date string.
  var timestamp = $('#start').val();
  $('#start-dtp').datetimepicker({
    allowInputToggle: true,
    format: 'DD.MM.YYYY HH:mm',
    sideBySide: true,
  });
  var dp = $('#start-dtp').data("DateTimePicker");
  dp.date(moment.unix(timestamp));

  // Convert the date back to a timestamp on form submit
  $('#roundForm').submit(function() {
    var date = $('#start').val();
    var timestamp = moment(date, 'DD.MM.YYYY HH:mm').unix();
    $('#start').val(timestamp);
    return true;
  });
});
