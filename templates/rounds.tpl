{extends file="layout.tpl"}

{block name=title}{"rounds"|_|ucfirst}{/block}

{block name=content}
  <h3>{"rounds"|_}</h3>

  <table class="table table-bordered">
    <thead>
      <th>{"name"|_}</th>
      <th>{"start date/time"|_}</th>
      <th>{"duration"|_}</th>
    </thead>
    <tbody>
      {foreach from=$rounds item=r}
        <tr class="{$r|round_class}">
	        <td><a href="{$wwwRoot}round.php?id={$r->id}">{$r->name}</a></td>
	        <td>{include "bits/dateTime.tpl" ts=$r->start}</td>
	        <td>{$r->duration} {"minutes"|_}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>

  {if $canAdd}
    <a href="editRound.php">{"add a round"|_}</a>
  {/if}
{/block}
