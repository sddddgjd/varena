{extends file="layout.tpl"}

{block name=title}{"rounds"|_|ucfirst}{/block}

{block name=content}
  <h3>{"rounds"|_}</h3>

  <ul>
    {foreach from=$rounds item=r}
      <li><a href="{$wwwRoot}round?id={$r->id}">{$r->name}</a></li>
    {/foreach}
  </ul>

  {if $canAdd}
    <a href="editRound.php">{"add a round"|_}</a>
  {/if}
{/block}
