{* An input-group field *}
{$type=$type|default:"text"}
{$field=$field|default:null}
{$value=$value|default:""}
{$glyph=$glyph|default:null}
{$placeholder=$placeholder|default:""}
{$required=$required|default:true}
{$autofocus=$autofocus|default:false}
<div class="input-group {if $errors.$field }.has-error{/if}">
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
         {if $required}required{/if}
         {if $autofocus}autofocus{/if}>
</div>
{include 'bits/fieldErrors.tpl' errors=$errors.$field|default:null}
