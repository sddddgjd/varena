{extends file="layout.tpl"}

{block name="title"}{"new password"|_|ucfirst}{/block}

{function input type="text" field=null value="" glyph=null placeholder="" required=false}
  <div class="input-group {if $errors.$field}has-error{/if}">
    {if $glyph}
      <span class="input-group-addon">
        <i class="glyphicon glyphicon-{$glyph}"></i>
      </span>
    {/if}
    <input type="{$type}"
           class="form-control"
           name="{$field}"
           value="{$value}"
           placeholder="{$placeholder}"
           {if $required}required{/if}>
  </div>
  {include 'bits/fieldErrors.tpl' errors=$errors.$field|default:null}
{/function}

{block name=content}
  <div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{"Choose a new password"|_}</div>
      </div>

      <div class="panel-body">
        <form method="post" role="form">
          <input type="hidden" name="token" value="{$token}">

          {input field="password" type="password" glyph="lock" placeholder={"password"|_} required=true}

          <div class="voffset4"></div>

          {input field="password2" type="password" glyph="lock" placeholder={"password (again)"|_} required=true}

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
