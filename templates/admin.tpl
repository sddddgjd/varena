{extends file="layout.tpl"}

{block name=title}{"admin tasks"|_|ucfirst}{/block}

{block name=content}
  <h3>{"admin tasks"|_}</h3>

  {if $roles}
    <div>
      <a href="roles.php">
        <i class="glyphicon glyphicon-eye-open"></i>
        {"edit roles"|_}
      </a>
    </div>
    <div>
      <a href="userRoles.php">
        <i class="glyphicon glyphicon-cog"></i>
        {"edit user roles"|_}
      </a>
    </div>
  {/if}

  {if $tags}
    <div>
      <a href="tags.php">
        <i class="glyphicon glyphicon-tags"></i>
        {"edit tags"|_}
      </a>
    </div>
  {/if}
{/block}
