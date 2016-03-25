{extends file="layout.tpl"}

{block name=title}{"source"|_|ucfirst} #{$s->id}{/block}

{block name=content}
  <h3>{"source"|_} #{$s->id}</h3>

  <table class="table table-bordered">
    <tbody>
      <tr>
        <th>{"status"|_}</th>
        <td>{$s->getStatusName()}</td>
        <th>{"date"|_}</th>
        <td>{include "bits/dateTime.tpl" ts=$s->created}</td>
      </tr>
      <tr>
        <th>{"score"|_}</th>
        <td>{$s->score}</td>
        <th>{"size"|_}</th>
        <td>{include "bits/fileSize.tpl" s=$s->sourceCode|count_characters:true}</td>
      </tr>
      <tr>
        <th>{"user"|_}</th>
        <td>{include "bits/userLink.tpl" u=$s->getUser()}</td>
        <th>{"compiler"|_}</th>
        <td>{$s->extension}</td>
      </tr>
      <tr>
        <th>{"problem"|_}</th>
        <td>{include "bits/problemLink.tpl" p=$s->getProblem()}</td>
        <th></th>
        <td></td>
      </tr>
    </tbody>
  </table>

  <div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a href="#eval" aria-controls="eval" role="tab" data-toggle="tab">
          {"evaluation report"|_}
        </a>
      </li>
      <li role="presentation">
        <a href="#compile" aria-controls="compile" role="tab" data-toggle="tab">
          {"compilation report"|_}
        </a>
      </li>
      <li role="presentation">
        <a href="#source" aria-controls="source" role="tab" data-toggle="tab">
          {"source code"|_}
        </a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="eval">
        {if $s->hasTests()}
          <h4>{"Score"|_}</h4>
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th>{"test #"|_}</th>
                <th>{"CPU time (s)"|_}</th>
                <th>{"memory used (kb)"|_}</th>
                <th>{"message"|_}</th>
                <th>{"points"|_}</th>
                {if $problem->testGroups}
                  <th>{"group points"|_}</th>
                {/if}
              </tr>
            </thead>
            <tbody>
              {foreach $data as $number => $t}
                <tr {if $problem->testGroups && $t.rowSpan}class="first-in-group"{/if}>
                  <td>{$number}</td>
                  <td>{$t.runningTime}</td>
                  <td>{$t.memoryUsed}</td>
                  <td>{$t.message}</td>
                  <td>{$t.score}</td>
                  {if $problem->testGroups && $t.rowSpan}
                    <td rowspan="{$t.rowSpan}" class="group-score">
                      {$t.groupScore}
                    </td>
                  {/if}
                </tr>
              {/foreach}
            </tbody>
            <tfoot>
              <tr>
                <th colspan="{if $problem->testGroups}5{else}4{/if}">
                  {"total score"|_}
                </th>
                <th>{$s->score}</th>
            </tfoot>
          </table>
        {else}
          <h4>{"Source status:"|_} {$s->getStatusName()}</h4>
          {if !$s->hasScore()}
            {"This source was not evaluated yet. Please try again later."|_}
          {/if}
        {/if}
      </div>

      <div role="tabpanel" class="tab-pane" id="compile">
        <h4>{"Compiler command"|_}</h4>
        <pre>{$command}</pre>

        {if $s->compileLog}
          <h4>{"Compiler output"|_}</h4>
          <pre>{$s->compileLog}</pre>
        {/if}
      </div>

      <div role="tabpanel" class="tab-pane" id="source">
        <pre>{$s->sourceCode|escape}</pre>
      </div>
    </div>

  </div>
  
{/block}
