<!DOCTYPE HTML>
<html>
  <head>
    <title>
      {block name=title}{/block}
      | Varena2
    </title>
    <meta charset="utf-8">
    {foreach from=$cssFiles item=cssFile}
      <link type="text/css" href="{$wwwRoot}css/{$cssFile}" rel="stylesheet"/>
    {/foreach}
    {foreach from=$jsFiles item=jsFile}
      <script src="{$wwwRoot}js/{$jsFile}"></script>
    {/foreach}
  </head>

  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">varena2</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="{$wwwRoot}">{"home"|_}</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            {if $user}
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  {$user->getDisplayName()} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="{$wwwRoot}auth/account">{"my account"|_}</a></li>
                  <li><a href="{$wwwRoot}auth/logout">{"logout"|_}</a></li>
                </ul>
              </li>
            {else}
              <li><a href="{$wwwRoot}auth/login">{"login"|_}</a></li>
            {/if}
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      {block name=content}{/block}
    </div>

    <footer class="footer">
      <div id="license">
        licență aici
      </div>
    </footer>
  </body>

</html>
