{foreach $tags as $t}
  <div class="wrapper">
    <div class="list-group-item">
      <span class="left">
        {for $i = 1 to $level}
          <span class="indent"></span>
        {/for}
        <i class="expander glyphicon {if count($t->children)}glyphicon-minus{else}glyphicon-none{/if}"></i>
      </span>

      <span class="center">
        {strip}
        <span class="value"
              data-id="{$t->id}"
              data-can-delete="{$t->canDelete}">
          {$t->value}
        </span>
      {/strip}
      </span>
    </div>

    <div class="children">
      {include "bits/tagTreeChildren.tpl" tags=$t->children level=$level+1}
    </div>
  </div>
{/foreach}
