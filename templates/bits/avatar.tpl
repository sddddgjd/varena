<img class="avatar"
  {if $user->hasAvatar}
     src="{$wwwRoot}img/user/{$user->id}.jpg?cb={1000000000|rand:9999999999}"
  {else}
     src="{$wwwRoot}img/avatar_user.png"
  {/if}
     alt="imagine de profil: {$user->username|escape}"
/>