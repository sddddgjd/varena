{extends file="layout.tpl"}

{block name=title}{$problem->name}{/block}

{block name=content}
  <h3>{$problem->name}</h3>

  <div>
    {$problem->getHtml()}
  </div>

  <a href="editProblem.php?id={$problem->id}">editează</a>
{/block}
