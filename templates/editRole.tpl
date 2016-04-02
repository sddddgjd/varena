{extends file="layout.tpl"}

{block name=title}
  {($role->name) ? {$role->name} : "new role"|_}
{/block}

{block name=content}
  <h3>
    {if $role->id}
      {"edit role"|_}
    {else}
      {"add a role"|_}
    {/if}
  </h3>

  <form method="post" role="form">
    {include "bits/fgf.tpl" field="name" type="text" value="{$role->name}" label={"name"|_} placeholder={"name"|_}}

    {foreach $data as $groupName => $group}
      <fieldset>
        <legend>{$groupName}</legend>

        {foreach $group as $p}
          <div class="checkbox">
            <label>
              <input type="checkbox" name="permissions[]" value="{$p.perm}" {if $p.checked}checked{/if}>
              {$p.name}
            </label>
          </div>
        {/foreach}
      </fieldset>
    {/foreach}

    <button type="submit" class="btn btn-default" name="save">
      <i class="glyphicon glyphicon-floppy-disk"></i>
      {"save"|_}
    </button>

    <a href="roles.php">{"cancel"|_}</a>
  </form>

  {if $role->id}
    <h3>{"Users having this role"|_}</h3>

    <ul>
      {foreach $users as $u}
        <li>{include "bits/userLink.tpl" u=$u}</li>
      {/foreach}
    </ul>
  {/if}
{/block}
