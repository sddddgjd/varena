$(function() {

  // Ensures the subtree is expanded and the glyphicon is correct.
  $.fn.ensureOpen = function() {
    $(this).find('> .list-group-item > .left > .expander')
      .removeClass('glyphicon-none')
      .removeClass('glyphicon-plus')
      .addClass('glyphicon-minus');
    $(this).find('> .children').slideDown();
    return $(this);
  }

  // Check or set the number of span.indent
  $.fn.indent = function(level) {
    if (typeof level === 'undefined') {
      return $(this).find('> .list-group-item > .left > .indent').length;
    } else {
      var p = $(this).find('> .list-group-item > .left');
      for (var i = 0; i < level; i++) {
        p.prepend('<span class="indent"></span>');
      }
      return $(this);
    }
  }

  var menuBar = null;
  var stem = null;
  var sel = null; // selected .wrapper

  function init() {
    $('.expander').click(glyphClick);
    $('.wrapper, #stem').click(tagClick);
    $('#butUp').click(moveTagUp);
    $('#butDown').click(moveTagDown);
    $('#butLeft').click(moveTagLeft);
    $('#butRight').click(moveTagRight);
    $('#butAddSibling').click(addSibling);
    $('#butAddChild').click(addChild);
    $('#butDelete').click(deleteTag);
    $('#butSave').click(saveTree);
    menuBar = $('#menuBar').detach();
    stem = $('#stem').detach().removeAttr('id');
  }

  function glyphClick(e) {
    if ($(this).is('.glyphicon-plus, .glyphicon-minus')) {      // if it has a subtree
      $(this).toggleClass('glyphicon-plus glyphicon-minus');    // toggle the glyph
      $(this).closest('.wrapper').find('> .children').slideToggle(); // toggle the children
    }
    return false;
  }

  function tagClick(e) {
    if ($(e.target).is('button, input')) { // ignore button and input clicks
      return false;
    }
    e.stopPropagation();
    endEdit();
    sel = $(this);
    sel.addClass('selected');
    var value = $(this).find('> .list-group-item > .center > .value').hide();
    menuBar.insertAfter(value).show();
    menuBar.find('#valueBox').val(value.text()).focus();
  }

  /* End the previous edit, if any */
  function endEdit() {
    if (sel) {
      var value = menuBar.prev();
      value.text($('#valueBox').val()).show();
      menuBar.detach();
      sel.removeClass('selected');
      sel = null;
    }
  }

  function moveTagUp() {
    sel.prev().before(sel);
  }

  function moveTagDown() {
    sel.next().after(sel);
  }

  function moveTagLeft() {
    var p = sel.parent().closest('.wrapper');
    if (p.length) {
      p.after(sel);
      // remove one level of indentation from the whole subtree
      sel.find('.indent:first-child').remove();
      if (!p.find('.wrapper').length) {          // the node has no children left
        p.find('.expander').toggleClass('glyphicon-minus glyphicon-none');
      }
    }
  }

  function moveTagRight() {
    var p = sel.prev();
    if (p.length) {
      // add one level of indentation to the whole subtree
      sel.find('.left').prepend('<span class="indent"></span>');
      p.ensureOpen().children('.children').append(sel);
    }
  }

  function addSibling() {
    stem.clone(true).indent(sel.indent()).insertAfter(sel).click();
  }

  function addChild() {
    var node = stem.clone(true).indent(1 + sel.indent());
    sel.ensureOpen().children('.children').append(node);
    node.click();
  }

  function deleteTag() {
    var blockers = sel.find('[data-can-delete=0]');
    if (blockers.length) {
      alert('Cannot delete tag - some problems use it.');
    } else {
      var p = sel.parent().closest('.wrapper');
      var toDelete = sel;
      endEdit();
      toDelete.remove();

      if (!p.find('.wrapper').length) {          // the node has no children left
        p.find('.expander').toggleClass('glyphicon-minus glyphicon-none');
      }
    }
  }

  function validate() {
    var empty = $('.value').filter(function() {
      return $(this).text() == '';
    });
    if (empty.length) {
      alert('Tag text may not be empty.');
      return false;
    }
    return true;
  }

  function saveTree() {
    var results = [];
    endEdit();

    if (!validate()) {
      return false;
    }

    $('.value').each(function(i) {
      var level = $(this).parentsUntil($('#tagTree'), '.wrapper').length;
      results.push({
        id: $(this).data('id'),
        value: $(this).text(),
        level: level,
      });
    });

    $('input[name=jsonTags]').val(JSON.stringify(results));
    return true;
  }

  init();
});
