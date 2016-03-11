{extends file="layout.tpl"}

{block name=title}
  {($problem->name) ? {$problem->name} : "new problem"|_}
{/block}

{block name=content}
  <h3>
    {if $problem->id}
      {"edit problem"|_}
    {else}
      {"add a problem"|_}
    {/if}
  </h3>

  {if isset($previewed)}
    <div class="container">
      <div class="alert alert-warning col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        {"This is only a preview. Don't forget to save your changes!"|_}
      </div>
    </div>

    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{$problem->name}</div>
      </div>

      <div class="panel-body" id="statement">
        {$problem->getHtml()}
      </div>
    </div>
  {/if}

  <form method="post" role="form">
    <div class="form-group {if isset($errors.name)}has-error{/if}">
      <label for="name">{"name"|_}</label>
      <input type="text"
             class="form-control"
             id="name"
             name="name"
             value="{$problem->name}"
             placeholder="{"problem name"|_}"
             required>
      {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
    </div>

    <div class="form-group {if isset($errors.statement)}has-error{/if}">
      <label for="statement">{"statement"|_}</label>
      {strip}
      <textarea class="form-control"
                rows="20"
                id="statement"
                name="statement"
                placeholder="{"problem statement"|_}"
                autofocus
                required>
        {$problem->statement}
      </textarea>
      {/strip}
      {include "bits/fieldErrors.tpl" errors=$errors.statement|default:null}
    </div>

    <button type="submit" class="btn btn-default" name="preview">
      <i class="glyphicon glyphicon-refresh"></i>
      {"preview"|_}
    </button>
    <button type="submit" class="btn btn-default" name="save">
      <i class="glyphicon glyphicon-floppy-disk"></i>
      {"save"|_}
    </button>

    {if $problem->id}
      <a href="problem.php?id={$problem->id}">{"cancel"|_}</a>
    {else}
      <a href="problems.php">{"cancel"|_}</a>
    {/if}
  </form>
{/block}
