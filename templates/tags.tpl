{extends file="layout.tpl"}

{block name=title}{"tags"|_|ucfirst}{/block}

{block name=content}
  <h3>{"tags"|_}</h3>

  <div class="container">
    <div class="alert alert-info col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
      {"Click on any line for a menu. Don't forget to save your changes!"|_}
    </div>
  </div>

  <div class="wrapper" id="stem">
    <div class="list-group-item">
      <span class="left">
        <i class="expander glyphicon glyphicon-none"></i>
      </span>

      <span class="center">
        <span class="value" data-can-delete="1"></span>
      </span>
    </div>

    <div class="children"></div>
  </div>

  {include file="bits/tagTree.tpl" tags=$tags id="tagTree"}

  <div id="menuBar">
    <input type="text" name="value" value="" id="valueBox" size="20">
    <div id="menuActions">
      <button id="butUp" class="btn btn-default btn-xs"
              title="{"the tag changes places with the previous sibling"|_}">
        <i class="glyphicon glyphicon-arrow-up"></i>
      </button>
      <button id="butDown" class="btn btn-default btn-xs"
              title="{"the tag changes places with the next sibling"|_}">
        <i class="glyphicon glyphicon-arrow-down"></i>
      </button>
      <button id="butLeft" class="btn btn-default btn-xs"
              title="{"the tag becomes its parent's next sibling"|_}">
        <i class="glyphicon glyphicon-arrow-left"></i>
      </button>
      <button id="butRight" class="btn btn-default btn-xs"
              title="{"the tag becomes its previous sibling's child"|_}">
        <i class="glyphicon glyphicon-arrow-right"></i>
      </button>
      <button id="butAddSibling" class="btn btn-default btn-xs">
        <i class="glyphicon glyphicon-chevron-right"></i>
        {"add sibling"|_}
      </button>
      <button id="butAddChild" class="btn btn-default btn-xs">
        <i class="glyphicon glyphicon-chevron-down"></i>
        {"add child"|_}
      </button>
      <button id="butDelete" class="btn btn-default btn-xs">
        <i class="glyphicon glyphicon-trash"></i>
        {"delete"|_}
      </button>
    </div>
  </div>

  <form method="post" role="form">
    <input type="hidden" name="jsonTags" value="">

    <button id="butSave" type="submit" class="btn btn-default" name="save">
      <i class="glyphicon glyphicon-floppy-disk"></i>
      {"save"|_}
    </button>
  </form>
{/block}
