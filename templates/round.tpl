{extends file="layout.tpl"}

{block name=title}{$round->name}{/block}

{block name=content}
  <h1>{$round->name}</h1>

  <table class="table table-bordered">
    <tbody>
      <tr>
        <th>{"start date/time"|_}</th>
        <td>{include "bits/dateTime.tpl" ts=$round->start}</td>
        <th>{"added by"|_}</th>
        <td>{include "bits/userLink.tpl" u=$round->getUser()}</td>
      </tr>
      <tr>
        <th>{"duration"|_}</th>
        <td>{$round->duration} {"minutes"|_}</td>
        <th></th>
        <td></td>
      </tr>
    </tbody>
  </table>

  <div id="statement">
    {$round->getHtml()}
  </div>

  {if $canManage}
    <a href="editRound.php?id={$round->id}">{"edit"|_}</a>
  {/if}

{/block}
