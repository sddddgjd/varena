{extends file="layout.tpl"}

{block name="title"}{"login"|_|ucfirst}{/block}

{block name=content}
  {***************************** Login box *****************************}
  <div id="login-box"
       {if $method == "signup"}style="display: none"{/if}
       class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{"login"|_|ucfirst}</div>
      </div>

      <div class="panel-body">

        <form method="post" role="form">
          <input type="hidden" name="method" value="login">

          {include "bits/igf.tpl" type="email" field="email" value=$email glyph="envelope" placeholder={"email"|_} autofocus=true}
          
          <div class="voffset4"></div>

          {include "bits/igf.tpl" field="password" type="password" glyph="lock" placeholder={"password"|_}}

          <p class="pull-right">
            <a href="changePass">
              {"Forgot password?"|_}
            </a>
          </p>

          <div class="voffset4"></div>

          <div class="input-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="remember" value="1" {if $remember}checked{/if}>
                {"Remember me"|_}
              </label>
            </div>
          </div>

          <div class="form-group">
            <div class="controls">
              <input type="submit" class="btn btn-primary" value="{"login"|_}">
            </div>
          </div>
        </form>
      </div>

      <div class="panel-footer">
        <p>
          {"Don't have an account?"|_}
          <a id="signup-link" href="#">
            {"Sign up here."|_}
          </a>
        </p>
      </div>
    </div>
  </div>

  {***************************** Signup box *****************************}
  <div id="signup-box"
       {if $method != "signup"}style="display: none"{/if}
       class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">{"Sign Up"|_}</div>
      </div>

      <div class="panel-body">

        <form method="post" role="form">
          <input type="hidden" name="method" value="signup">

          {include "bits/igf.tpl" type="email" field="email" value=$email glyph="envelope" placeholder={"email"|_}}
          
          <div class="voffset4"></div>

          {include "bits/igf.tpl" field="username" value=$username glyph="user" placeholder={"username"|_}}
          
          <div class="voffset4"></div>

          {include "bits/igf.tpl" field="name" value=$name glyph="pencil" placeholder={"name (optional)"|_} required=false}
          
          <div class="voffset4"></div>

          {include "bits/igf.tpl" field="password" type="password" glyph="lock" placeholder={"password"|_}}

          <div class="voffset4"></div>

          {include "bits/igf.tpl" field="password2" type="password" glyph="lock" placeholder={"password (again)"|_}}

          <div class="voffset4"></div>

          <div class="form-group">
            <div class="controls">
              <input type="submit" class="btn btn-primary" value="{"sign up"|_}">
            </div>
          </div>

        </form>
      </div>

      <div class="panel-footer">
        <p>
          {"Already have an account?"|_}
          <a id="login-link" href="#">
            {"Log in here."|_}
          </a>
      </div>
    </div>
  </div>
{/block}
