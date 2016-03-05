{extends file="layout.tpl"}

{block name=title}{$problem->name}{/block}

{block name=content}
  <h3>{"edit problem"|_}</h3>

  <form method="post" role="form">
    <div class="form-group">
      <label for="name">{"name"|_}</label>
      <input type="text"
             class="form-control"
             id="name"
             name="name"
             value="{$problem->name}"
             placeholder="{"problem name"|_}"
             required>
    </div>
    <div class="form-group">
      <label for="statement">{"statement"|_}</label>
      {strip}
      <textarea class="form-control"
                rows="20"
                id="statement"
                placeholder="{"problem statement"|_}"
                autofocus
                required>
        {$problem->statement}
      </textarea>
      {/strip}
    </div>
    <input type="submit" class="btn btn-default" value="{"save"|_}">
  </form>
{/block}
