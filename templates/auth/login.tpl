{extends file="layout.tpl"}

{block name="title"}{"login"|_|ucfirst}{/block}

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
  <div class="container">

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

            {input field="email" value=$email glyph="envelope" placeholder={"email"|_} required=true}
            
            <div class="voffset4"></div>

            {input field="password" type="password" glyph="lock" placeholder={"password"|_} required=true}

            <p class="pull-right">
              <a href="recoverPassword.php">
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

            {input field="email" value=$email glyph="envelope" placeholder={"email"|_} required=true}
            
            <div class="voffset4"></div>

            {input field="name" value=$name glyph="user" placeholder={"name"|_} required=true}
            
            <div class="voffset4"></div>

            {input field="password" type="password" glyph="lock" placeholder={"password"|_} required=true}

            <div class="voffset4"></div>

            {input field="password2" type="password" glyph="lock" placeholder={"password (again)"|_} required=true}

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
  </div>
{/block}
