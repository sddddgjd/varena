$(function() {
  // Read the timestamp, then initialize the datetimepicker with a date string.
  var timestamp = $('#start').val();
  $('#start-dtp').datetimepicker({
    allowInputToggle: true,
    format: 'YYYY-MM-DD HH:mm',
    showTodayButton: true,
    sideBySide: true,
  });
  var dp = $('#start-dtp').data("DateTimePicker");
  if (timestamp) {
    dp.date(moment.unix(timestamp));
  }

  // Convert the date back to a timestamp on form submit
  $('#roundForm').submit(function() {
    var date = $('#start').val();
    var timestamp = moment(date, 'YYYY-MM-DD HH:mm').unix();
    $('#start').val(timestamp);
    return true;
  });
});
