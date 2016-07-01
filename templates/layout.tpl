<!DOCTYPE HTML>
<html>
  <head>
    <title>
      {block name=title}{/block}
      | Varena
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    {foreach from=$cssFiles item=cssFile}
      <link type="text/css" href="{$wwwRoot}css/{$cssFile}" rel="stylesheet"/>
    {/foreach}
    {foreach from=$jsFiles item=jsFile}
      <script src="{$wwwRoot}js/{$jsFile}"></script>
    {/foreach}
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">varena</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="{$wwwRoot}">{"home"|_}</a></li>
            <li><a href="{$wwwRoot}problems.php">{"problems"|_}</a></li>
            <li><a href="{$wwwRoot}rounds.php">{"rounds"|_}</a></li>
            <li>
              <a href="{$wwwRoot}evaluator.php{if $user}?userId={$user->id}{/if}">
                {"evaluator"|_}
              </a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <form role="form" name="languageForm">
                <select class="form-control" name="languageSelector" id="languageSelector">
                  {foreach $availableLocales as $key=>$locale}
                    <option value={$locale}>{$availableLang[$key]}</option>
                   {/foreach}
                  }
                </select>
              </form>
            </li>
            {if $user}
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="glyphicon glyphicon-user"></i>
                  {$user->username} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="{$wwwRoot}auth/account.php">{"my account"|_}</a></li>
                  {if $user->hasRoles()}
                    <li><a href="{$wwwRoot}admin.php">{"admin"|_}</a></li>
                  {/if}
                  <li><a href="{$wwwRoot}auth/logout.php">{"logout"|_}</a></li>
                </ul>
              </li>
            {else}
              <li><a href="{$wwwRoot}auth/login.php">{"login"|_}</a></li>
            {/if}
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    {if count($flashMessages)}
      <div class="container">
        {foreach from=$flashMessages item=m}
          <div class="alert alert-{$m.type} col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div>{$m.text}</div>
          </div>
        {/foreach}
      </div>
    {/if}

    <div class="container">
      {block name=content}{/block}
    </div>

    <footer class="footer">
      <div class="container">
        <p class="text-muted">
          © 2016 Varena |
          <a href="https://github.com/varena/varena">GitHub</a>
        </p>
      </div>
    </footer>
  </body>

</html>
