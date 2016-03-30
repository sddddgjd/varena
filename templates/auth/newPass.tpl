{extends file="layout.tpl"}

{block name="title"}{"new password"|_|ucfirst}{/block}

{block name=content}
  <div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{"Choose a new password"|_}</div>
      </div>

      <div class="panel-body">
        <form method="post" role="form">
          <input type="hidden" name="token" value="{$token}">

          {include "bits/igf.tpl" field="password" type="password" glyph="lock" placeholder={"password"|_} autofocus=true}

          <div class="voffset4"></div>

          {include "bits/igf.tpl" field="password2" type="password" glyph="lock" placeholder={"password (again)"|_}}

          <div class="voffset4"></div>

          <div class="form-group">
            <div class="controls">
              <input type="submit" class="btn btn-primary" value="{"change password"|_}">
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
{/block}
