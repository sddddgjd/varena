{extends file="layout.tpl"}

{block name="title"}{"User profile"|_}{/block}

{block name=content}
<div>
    <div class="card hovercard">
        <div class="useravatar">
            {include file="bits/avatar.tpl"}
        </div>
        <div class="card-info"> <span class="card-title">{$user->name}</span>

        </div>
    </div>
    <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                <div class="hidden-xs">{"About me"|_}</div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default" href="#tab2" data-toggle="tab"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                <div class="hidden-xs">{"Rating"|_}</div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                <div class="hidden-xs">{"Statistics"|_}</div>
            </button>
        </div>
    </div>

    <div class="well">
      <div class="tab-content">
        <div class="tab-pane fade in active" id="tab1">
          {if $canEdit}
            <a href="editDescription?id={$user->id}" id="editLink" style="float:right;">{"Edit description."|_}</a>
          {/if}
          {$userDesc}
        </div>
        <div class="tab-pane fade in" id="tab2">
          
        </div>
        <div class="tab-pane fade in" id="tab3">
          <h3> {"Solved problems:"|_} </h3>
          <ul class="glyphicon glyphicon-triangle-right">
            {foreach $solved as $key=>$p}
               <li style="display:inline"><a href="problem.php?id=$p->id">{$p->name}</a></li>
             {/foreach}
           </ul>
          <h3> {"Attempted problems:"|_} </h3>
          <ul class="glyphicon glyphicon-triangle-right">
            {foreach $attempts as $key=>$p}
               <li style="display:inline"><a href="problem.php?id=$p->id">{$p->name}</a></li>
             {/foreach}
           </ul>
        </div>
      </div>
    </div>
    
    </div>
            
    <style>
    .glyphicon{
        font-size:18px;
    }
    </style>
{/block}