{extends file="layout.tpl"}
{block name=title}{"User Roles"|_}{/block}
{block name="content"}
<h2> {"Users with roles:"|_} </h2>
<ul>
  {foreach from=$userRoles item=uRole}
    <li>{User::get_by_id($uRole->userId)->username}: {Role::get_by_id($uRole->roleId)->name} 
      <a href="userRoles.php?delete={$uRole->id}" class="btn btn-danger btn-sm">
        <i class="glyphicon glyphicon-trash"></i>
        {"delete"|_}
      </a>
    </li>
  {/foreach}
</ul>
<h2> {"Assign a role to a user:"|_} </h2>
<form class="form-inline">
  <div class="form-group">
    <label for="username">{"username"|_|ucfirst}</label>
    <input type="text" class="form-control" name="username" id="username" placeholder="{"username"|_}">
  </div>
  <div class="form-group">
    <label for="role">{"role"|_|ucfirst}</label>
    <select class="form-control" name="role">
	{foreach $roles as $role}
  		<option value="{$role->id}">{$role->name}</option>
  	{/foreach}
	</select>
  </div>
  <button type="submit" class="btn btn-primary" name="save">Add role</button>
</form>
{/block}