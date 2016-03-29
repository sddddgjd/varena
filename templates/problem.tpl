{extends file="layout.tpl"}

{block name=title}{$problem->name}{/block}

{block name=content}
  <h1>{$problem->name}</h1>

  <table class="table table-bordered">
    <tbody>
      <tr>
        <th>{"time limit"|_}</th>
        <td>{$problem->timeLimit} s</td>
        <th>{"added by"|_}</th>
        <td>{include "bits/userLink.tpl" u=$problem->getUser()}</td>
      </tr>
      <tr>
        <th>{"memory limit"|_}</th>
        <td>{$problem->memoryLimit} KiB</td>
        <th>{"your score"|_}</th>
        <td>{if $score === null}{"N/A"|_}{else}{$score} {"points"|_}{/if}</td>
      </tr>
    </tbody>
  </table>

  <div id="statement">
    {$problem->getHtml()}
  </div>

  {if $problem->editableBy($user)}
    <a href="editProblem.php?id={$problem->id}">{"edit"|_}</a> |
  {/if}
  <a href="attachments.php?id={$problem->id}">{"attachments"|_}</a> |
  <a href="evaluator.php?problemId={$problem->id}">{"submitted sources"|_}</a>

  {if $user}
    <h3>{"submit a source file"|_}</h3>

    <form method="post" role="form" enctype="multipart/form-data">
      <div class="col-lg-6 col-sm-6 col-12">

        <div class="input-group">
          <span class="input-group-btn">
            <span class="btn btn-default btn-file">
              <i class="glyphicon glyphicon-folder-open"></i>
              &nbsp; {"browse..."|_}
              <input type="file" name="source" data-max-size="1000000">
            </span>
          </span>
          <input type="text" class="form-control" readonly>
        </div>

      </div>

      <button type="submit" class="btn btn-default">
        <i class="glyphicon glyphicon-upload"></i>
        {"upload"|_}
      </button>
    </form>
  {/if}
{/block}
