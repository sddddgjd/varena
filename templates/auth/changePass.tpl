{extends file="layout.tpl"}

{block name="title"}{"password change"|_|ucfirst}{/block}

{block name=content}
  <div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{"password change"|_|ucfirst}</div>
      </div>

      <div class="panel-body">
        <p>
          {"Type in your email and we'll send you an email to change your password."|_}
        </p>

        <form method="post" role="form">
          {include "bits/igf.tpl" type="email" field="email" glyph="envelope" placeholder={"email"|_} autofocus=true}
          
          <div class="voffset4"></div>

          <div class="form-group">
            <div class="controls">
              <input type="submit" class="btn btn-primary" value="{"send"|_}">
            </div>
          </div>
        </form>
      </div>

      <div class="panel-footer">
        <p>
          {"Know your password?"|_}
          <a href="login">
            {"Log in here."|_}
          </a>
      </div>
    </div>
  </div>
{/block}
