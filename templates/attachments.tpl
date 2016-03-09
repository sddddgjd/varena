{extends file="layout.tpl"}

{block name=title}
  {$problem->name} - {"attachments"|_}
{/block}

{block name=content}
  <h3>{$problem->name}</h3>

  <div>
    <a href="problem.php?id={$problem->id}">{"back"|_}</a>
  </div>

  <div class="voffset3"></div>

  <div class="panel panel-default">
    <div class="panel-heading">{"attachments"|_} ({$attachments|count})</div>

    <table class="table">
      <thead>
        <tr>
          <th>{"name"|_}</th>
          <th>{"size"|_}</th>
          <th>{"uploader"|_}</th>
          <th>{"date"|_}</th>
        </tr>
        <tbody>
          {foreach from=$attachments item=a}
            <tr>
              <td>{$a->name}</td>
              <td>{include "bits/fileSize.tpl" s=$a->size}</td>
              <td>{include "bits/userLink.tpl" u=$a->getUser()}</td>
              <td>{include "bits/dateTime.tpl" ts=$a->created}</td>
            </tr>
          {/foreach}
        </tbody>
    </table>
  </div>

  <h3>{"Add attachments"|_}</h3>

  <form method="post" role="form" enctype="multipart/form-data">
    <div class="col-lg-6 col-sm-6 col-12">

      <div class="input-group">
        <span class="input-group-btn">
          <span class="btn btn-default btn-file">
            <i class="glyphicon glyphicon-folder-open"></i>
            &nbsp; {"browse..."|_}
            <input type="file" name="files[]" multiple data-max-size="10000000">
          </span>
        </span>
        <input type="text" class="form-control" readonly>
      </div>

    </div>

    <button type="submit" class="btn btn-default">
      <i class="glyphicon glyphicon-refresh"></i>
      {"upload"|_}
    </button>
  </form>
{/block}
