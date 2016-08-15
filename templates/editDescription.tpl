{extends file="layout.tpl"}

{block name="title"}{"Edit description"|_}{/block}
{block name=content}
<form action='editDescription.php?id=1'>
  <div class="form-group">
    <label for="description">{"Enter your description here"|_}:</label>
    <textarea class="form-control" name="description" id="description" rows="15">{$userDesc}</textarea>
  </div>
  <input type="hidden" name="id" value={$id}>
  <input class="btn btn-primary" name="submitButton" value="Save Changes" type="submit">
  <span></span>
  <a href="user.php?id={$id}" class="btn btn-default"> Cancel</a>
</form>
{/block}