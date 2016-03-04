{extends file="layout.tpl"}

{block name="title"}{"login"|_|ucfirst}{/block}

{block name=content}
  <div class="container">

    {***************************** Login box *****************************}
    <div id="login-box" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="panel-title">{"login"|_|ucfirst}</div>
        </div>

        <div class="panel-body">

          <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

          <form method="post" role="form">
            <input type="hidden" name="data" value="login">

            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
              <input type="text" class="form-control" name="email" value="" placeholder="{"email"|_}" required>
            </div>

            <div class="voffset4"></div>

            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="password" class="form-control" name="password" placeholder="{"password"|_}" required>
            </div>
            <p class="pull-right">
              <a href="recoverPassword.php">
                {"Forgot password?"|_}
              </a>
            </p>

            <div class="voffset4"></div>

            <div class="input-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="remember" value="1"> {"Remember me"|_}
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
    <div id="signup-box" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="panel-title">{"Sign Up"|_}</div>
        </div>

        <div class="panel-body">

          <form method="post" role="form">
            <input type="hidden" name="data" value="signup">

            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
              <input type="text" class="form-control" name="email" value="" placeholder="{"email"|_}" required>
            </div>
            
            <div class="voffset4"></div>

            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input type="text" class="form-control" name="name" value="" placeholder="{"name (optional)"|_}">
            </div>
            
            <div class="voffset4"></div>

            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="password" class="form-control" name="password" placeholder="{"password"|_}" required>
            </div>

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
