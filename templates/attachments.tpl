{extends file="layout.tpl"}

{block name=title}
  {$problem->name} - {"attachments"|_}
{/block}

{block name=content}
  <h3>{$problem->name}</h3>

  <div class="panel panel-default">
    <div class="panel-heading">{"attachments"|_} ({$attachments|count})</div>

    <table class="table">
      <thead>
        <tr>
          <th>{"name"|_}</th>
          <th>{"size"|_}</th>
          <th>{"uploader"|_}</th>
          <th>{"date"|_}</th>
        </tr>
      <tbody>
        {foreach from=$attachments item=a}
          <tr>
            <td>{$a->name}</td>
            <td>{include "bits/fileSize.tpl" s=$a->size}</td>
            <td>{include "bits/userLink.tpl" u=$a->getUser()}</td>
            <td>{include "bits/dateTime.tpl" ts=$a->created}</td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  
  <a href="problem.php?id={$problem->id}">{"back"|_}</a>
{/block}
