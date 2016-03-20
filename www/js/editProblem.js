$(function() {
  // Switch to the parameters tab if it has errors (and the main tab doesn't).
  if (!$('#statementTab .has-error').length &&
      $('#parametersTab .has-error').length) {
    $('.nav-tabs a[href="#parametersTab"]').tab('show');
  }
});
