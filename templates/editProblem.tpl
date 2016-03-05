{extends file="layout.tpl"}

{block name=title}{$problem->name}{/block}

{block name=content}
  <h3>{"edit problem"|_}</h3>

  <form method="post" role="form">
    <div class="form-group {if $errors.name}has-error{/if}">
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

    <div class="form-group {if $errors.statement}has-error{/if}">
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

    <input type="submit" class="btn btn-default" name="save" value="{"save"|_}">
  </form>
{/block}
