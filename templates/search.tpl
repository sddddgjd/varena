{extends file="layout.tpl"}

{block name="title"}{"Search results"|_|ucfirst}{/block}
{block name="content"}
  <h3>{"Your search results:"|_}</h3><br>
  {foreach $results as $problem}
    <a href="problem.php?id={$problem->id}">{$problem->name}</a>
    <br>
  {/foreach}
{/block}