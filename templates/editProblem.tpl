{extends file="layout.tpl"}

{block name=title}
  {($p->name) ? {$p->name} : "new problem"|_}
{/block}

{block name=content}
  <h3>
    {if $p->id}
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
        <div class="panel-title">{$p->name}</div>
      </div>

      <div class="panel-body" id="statement">
        {$p->getHtml()}
      </div>
    </div>
  {/if}

  <form method="post" role="form">
    <div>
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
          <a href="#statementTab" aria-controls="statementTab" role="tab" data-toggle="tab">
            {"statement"|_}
          </a>
        </li>
        <li role="presentation">
          <a href="#parametersTab" aria-controls="parametersTab" role="tab" data-toggle="tab">
            {"parameters"|_}
          </a>
        </li>
        <li role="presentation">
          <a href="#metadataTab" aria-controls="metadataTab" role="tab" data-toggle="tab">
            {"metadata"|_}
          </a>
        </li>
      </ul>

      <div class="voffset3"></div>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="statementTab">

          {* Cannot reuse code here - label + input + button *}
          <label for="name">{"name"|_}</label>
          <div class="input-group {if isset($errors.name)}has-error{/if}">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="{$p->name}"
                   placeholder="{"problem name"|_}">
            <span class="input-group-btn">
              <button type="submit"
                      class="btn btn-default"
                      name="generate"
                      title="{"generate a statement template based on the problems name"|_}">
                <i class="glyphicon glyphicon-pencil"></i>
                {"generate template"|_}
              </button>
            </span>
          </div>
          {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}

          <div class="form-group {if isset($errors.statement)}has-error{/if}">
            <label for="statement">{"statement"|_}</label>
            <textarea class="form-control"
                      rows="20"
                      id="statement"
                      name="statement"
                      placeholder="{"problem statement"|_}"
                      autofocus
                      >{$p->statement}</textarea>
            {include "bits/fieldErrors.tpl" errors=$errors.statement|default:null}
          </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="parametersTab">
          {include "bits/fgf.tpl" field="timeLimit" value=$p->timeLimit step="0.01" label={"time limit (seconds)"|_}}
          {include "bits/fgf.tpl" field="memoryLimit" value=$p->memoryLimit label={"memory limit (kibibytes)"|_}}
          {include "bits/fgf.tpl" field="numTests" value=$p->numTests label={"number of tests"|_}}
          {include "bits/fgf.tpl" field="testGroups" type="text" value=$p->testGroups label={"test grouping"|_} placeholder={"e.g. 1-5; 6; 7; 8-10"|_}}
          {include "bits/fgf.tpl" field="grader" type="text" value=$p->grader label={"grader"|_} placeholder={"leave empty for diff evaluation"|_}}
          {include "bits/fgf.tpl" field="publicSources" value=$p->publicSources label={"public sources"|_}}
          {include "bits/fgf.tpl" field="publicTests" value=$p->publicTests label={"public tests"|_}}
          {include "bits/fgf.tpl" field="feedbackTests" value=$p->feedbackTests type="text" label={"feedback tests"|_}
          placeholder={"e.g. 1,2,3,4"|_}}
          {include "bits/fgf.tpl" field="year" value=$p->year label={"year"|_}}
          
          <div class="form-group">
            <label for="contest">{"contest"|_}</label>
            <br>
            <input list="contests" id="contest" name="contest" value="{$p->contest}">
            <datalist id="contests">
              {foreach $contests as $contest}
                <option value={$contest}></option>
              {/foreach}
            </datalist>
          </div>
          {include "bits/fieldErrors.tpl" errors=$errors.grade|default:null}
          <div class="form-group">
            <label for="grade">{"grade"|_}</label>
            <br>
            <input list="grades" id="grade" name="grade" value="{$p->grade}">
            <datalist id="grades">
              {foreach $grades as $grade}
                <option value={$grade}></option>
              {/foreach}
            </datalist>
          </div>

          <div class="checkbox">
            <label for="hasWitness">
              <input type="checkbox"
                     id="hasWitness"
                     name="hasWitness"
                     value="1"
                     {if $p->hasWitness}checked{/if}>
              {"uses .ok files"|_}
            </label>
          </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="metadataTab">

          <div class="form-group">
            <label for="tagIds">{"tags"|_}</label>
            <div class="select2-container form-group select2" style="width:100%">
              <select id="tagIds" name="tagIds[]" multiple="multiple" style="width: 100%">
                {foreach $tags as $t}
                  <option value="{$t->id}" selected>{$t->value}</option>
                {/foreach}
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="authorName">author</label>
            <input list="authors" id="authorName" name="authorName">
            <datalist id="authors">
              {foreach $authors as $author}
                <option value={$author}></option>
              {/foreach}
            </datalist>
          </div>

          <div class="form-group">
            <label for="visibility">{"visibility"|_}</label>
            <select id="visibility" name="visibility" class="form-control">
              {foreach Problem::getVisibilities() as $v => $name}
                <option value="{$v}" {if $p->visibility == $v}selected{/if}>
                  {$name}
                </option>
              {/foreach}
            </select>
          </div>
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

    {if $p->id}
      <a href="problem.php?id={$p->id}">{"cancel"|_}</a>
    {else}
      <a href="problems.php">{"cancel"|_}</a>
    {/if}
  </form>
{/block}
