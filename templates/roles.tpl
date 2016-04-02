{extends file="layout.tpl"}

{block name=title}{"roles"|_|ucfirst}{/block}

{block name=content}
  <h3>{"roles"|_}</h3>

  <ul>
    {foreach from=$roles item=r}
      <li><a href="{$wwwRoot}editRole.php?id={$r->id}">{$r->name}</a></li>
    {/foreach}
  </ul>

  {if $user}
    <a href="editRole.php">{"add a role"|_}</a>
  {/if}
{/block}
