{extends file="layout.tpl"}

{block name=title}
  {($r->name) ? {$r->name} : "new round"|_}
{/block}

{block name=content}
  <h3>
    {if $r->id}
      {"edit round"|_}
    {else}
      {"add a round"|_}
    {/if}
  </h3>

  {if isset($previewed)}
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{$r->name}</div>
      </div>

      <div class="panel-body" id="statement">
        {$r->getHtml()}
      </div>
    </div>
  {/if}

  <form method="post" role="form" id="roundForm">
    {include "bits/fgf.tpl" type="text" field="name" value=$r->name label={"name"|_}}

    <div class="form-group {if isset($errors.statement)}has-error{/if}">
      <label for="statement">{"description"|_}</label>
      <textarea class="form-control"
                rows="5"
                id="description"
                name="description"
                placeholder="{"round description (optional)"|_}"
                >{$r->description}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.description|default:null}
    </div>

    {* Cannot reuse code here -- date picker *}
    <div class="container nopadding">
      {* TODO Fix width *}
      <div class="form-group {if isset($errors.start)}has-error{/if}">        
        <label for="start">{"start date/time (YYYY-MM-DD HH:MM)"|_}</label>
        <div class="input-group date" id="start-dtp">
          <input type="text"
                 class="form-control"
                 id="start"
                 name="start"
                 value="{$r->start}">
        </div>
        {include "bits/fieldErrors.tpl" errors=$errors.start|default:null}
      </div>
    </div>

    {include "bits/fgf.tpl" field="duration" value=$r->duration label={"duration (minutes)"|_}}

    <div class="form-group">
      <label for="problemIds">{"problems"|_}</label>
      <div class="select2-container form-control select2">
        <select id="problemIds" name="problemIds[]" multiple="multiple" style="width: 100%">
          {foreach $problems as $p}
            <option value="{$p->id}" selected>{$p->name}</option>
          {/foreach}
        </select>
      </div>
    </div>
    
    <button type="submit" class="btn btn-default" name="preview">
      <i class="glyphicon glyphicon-refresh"></i>
      {"preview"|_}
    </button>
    <button type="submit" class="btn btn-default" name="save">
      <i class="glyphicon glyphicon-floppy-disk"></i>
      {"save"|_}
    </button>

    {if $r->id}
      <a href="round.php?id={$r->id}">{"cancel"|_}</a>
    {else}
      <a href="rounds.php">{"cancel"|_}</a>
    {/if}
  </form>
{/block}
