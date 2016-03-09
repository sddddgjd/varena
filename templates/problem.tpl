{extends file="layout.tpl"}

{block name=title}{$problem->name}{/block}

{block name=content}
  <h3>{$problem->name}</h3>

  <div>
    {$problem->getHtml()}
  </div>

  {if $problem->editableBy($user)}
    <a href="editProblem.php?id={$problem->id}">editează</a>
  {/if}
{/block}
