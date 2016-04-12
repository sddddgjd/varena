{extends file="layout.tpl"}

{block name=title}{"tags"|_|ucfirst}{/block}

{block name=content}
  <h3>{"tags"|_}</h3>

  <div class="container">
    <div class="alert alert-info col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
      {"Click on any line for a menu. Don't forget to save your changes!"|_}
    </div>
  </div>

  <li id="stem">
    <div class="expand"></div>
    <div class="value" data-id="" data-can-delete="1"></div>
  </li>
  {include file="bits/tagTree.tpl" tags=$tags id="tagTree"}

  <div id="menuBar">
    <input type="text" name="value" value="" id="valueBox" size="20">
    <div id="menuActions">
      <button id="butUp"
              title="{"the tag changes places with the previous sibling"|_}"
              >⇧</button>
      <button id="butDown"
              title="{"the tag changes places with the next sibling"|_}"
              >⇩</button>
      <button id="butLeft"
              title="{"the tag becomes its parent's next sibling"|_}"
              >⇦</button>
      <button id="butRight"
              title="{"the tag becomes its previous sibling's child"|_}"
              >⇨</button>
      <button id="butAddSibling">{"add a sibling"|_}</button>
      <button id="butAddChild">{"add a child"|_}</button>
      <button id="butDelete">{"delete"|_}</button>
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
