{* Recursively displays a tag tree (or forest). The id, if not empty, is only set for the root <ul>. *}
<div class="list-group" id="{$id}">
  {include "bits/tagTreeChildren.tpl" tags=$tags level=0}
</div>
