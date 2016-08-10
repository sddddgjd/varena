
{extends file="layout.tpl"}

{block name="title"}{"My account"|_|ucfirst}{/block}

{block name=content}
<div class="container">
  <h1 class="page-header">{"My account"|_}</h1>
  <div class="row">
    <!-- TO DO: add avator support-->
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="text-center">
        <img src="https://thesandtrap.com/uploads/static_huddler/7/75/755de8b8_basic_avatar.png" class="avatar img-circle img-thumbnail" alt="avatar">
        <h6>Upload a different photo...</h6>
        <input type="file" class="text-center center-block well well-sm">
      </div>
    </div>
    <div class="col-md-8 col-sm-6 col-xs-12 personal-info">
      <form class="form-horizontal" role="form">
        <div class="form-group">
          <label class="col-lg-3 control-label">{"Name"|_}:</label>
          <div class="col-lg-8">
            <input class="form-control" name="name" value={$editUser->name} type="text">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">{"Email"|_}:</label>
          <div class="col-lg-8">
            <input class="form-control" name="email" value={$editUser->email} type="text">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">{"New password"|_}:</label>
          <div class="col-md-8">
            <input class="form-control" name="newpassword" type="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">{"Confirm password"|_}:</label>
          <div class="col-md-8">
            <input class="form-control" name="newpassword2" type="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">{"Enter current password"|_}:</label>
          <div class="col-md-8">
            <input class="form-control" name="password" type="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label"></label>
          <div class="col-md-8">
            <input class="btn btn-primary" name="submitButton" value="Save Changes" type="submit">
            <span></span>
            <input class="btn btn-default" value="Cancel" type="reset">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

{/block}
