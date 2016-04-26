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

  <h3>{"problems"|_}</h3>

  <ul>
    {foreach $problems as $p}
      <li>{include "bits/problemLink.tpl"}</li>
    {/foreach}
  </ul>

  <h3>{"scoreboard"|_}</h3>

  {if $scoreboard}
    <table class="table table-bordered table-condensed table-hover">
      <thead>
        <tr>
          <th>{"rank"|_}</th>
          <th>{"user"|_}</th>
          {foreach $problems as $p}
            <th>{include "bits/problemLink.tpl"}</th>
          {/foreach}
          <th>{"total"|_}</th>
        </tr>
      </thead>
      <tbody>
        {foreach $scoreboard as $i=> $rec}
          <tr>
            <td>{$i+1}</td>
            <td>{include "bits/userLink.tpl" u=$rec.user}</td>
            {foreach $rec.scores as $s}
              <td>{$s|default:"&mdash;"}</td>
            {/foreach}
            <td>{$rec.total}</td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  {else}
    <div>
      {"No submissions yet."|_}
    </div>
  {/if}

  {if $canManage}
    <a href="editRound.php?id={$round->id}">{"edit"|_}</a>
  {/if}

{/block}
