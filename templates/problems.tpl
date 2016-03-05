{extends file="layout.tpl"}

{block name=title}{"problems"|_|ucfirst}{/block}

{block name=content}
  <h3>{"problems"|_}</h3>

  <ul>
    {foreach from=$problems item=p}
      <li><a href="{$wwwRoot}problem?id={$p->id}">{$p->name}</a></li>
    {/foreach}
  </ul>
{/block}
