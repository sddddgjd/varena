{extends file="layout.tpl"}

{block name=title}{"evaluator"|_|ucfirst}{/block}

{block name=content}
  <h3>{"evaluator queue"|_}</h3>

  <table class="table">
    <thead>
      <tr>
        <th>{"ID"|_}</th>
        <th>{"user"|_}</th>
        <th>{"problem"|_}</th>
        <th>{"size"|_}</th>
        <th>{"date"|_}</th>
        <th>{"status"|_}</th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$sources item=s}
        <tr>
          <td><a href="source.php?id={$s->id}">{$s->id}</a></td>
          <td>{include "bits/userLink.tpl" u=$s->getUser()}</td>
          <td>{include "bits/problemLink.tpl" p=$s->getProblem()}</td>
          <td>{include "bits/fileSize.tpl" s=$s->sourceCode|count_characters:true}</td>
          <td>{include "bits/dateTime.tpl" ts=$s->created}</td>
          <td>{$s->getStatusName()}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>
{/block}
