{extends file="layout.tpl"}

{block name=title}{"source"|_|ucfirst} #{$s->id}{/block}

{block name=content}
  <h3>{"source"|_} #{$s->id}</h3>

  <table class="table table-bordered">
    <tbody>
      <tr>
        <th>{"user"|_}</th>
        <td>{include "bits/userLink.tpl" u=$s->getUser()}</td>
        <th>{"problem"|_}</th>
        <td>{include "bits/problemLink.tpl" p=$s->getProblem()}</td>
      </tr>
      <tr>
        <th>{"size"|_}</th>
        <td>{include "bits/fileSize.tpl" s=$s->sourceCode|count_characters:true}</td>
        <th>{"date"|_}</th>
        <td>{include "bits/dateTime.tpl" ts=$s->created}</td>
      </tr>
      <tr>
        <th>{"compiler"|_}</th>
        <td>{$s->extension}</td>
        <th>{"score"|_}</th>
        <td>{$s->score}</td>
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
        <a href="#source" aria-controls="source" role="tab" data-toggle="tab">
          {"source code"|_}
        </a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="eval">
        va urla
      </div>
      <div role="tabpanel" class="tab-pane" id="source">
        <pre>{$s->sourceCode}</pre>
      </div>
    </div>

  </div>
  
{/block}
