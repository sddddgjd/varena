{extends file="layout.tpl"}

{block name=title}{"problems"|_|ucfirst}{/block}

{block name=content}
  {if $user}
  <ul class="nav nav-tabs">
    <li>
      <a  href="problems?tab=1">{"All problems"|_}</a>
    </li>
    <li><a href="problems?tab=2">{"Unsolved"|_}</a>
    </li>
    <li><a href="problems?tab=3">{"Attempted"|_}</a>
    </li>
    <li><a href="problems?tab=4" >{"Solved"|_}</a>
    </li>
  </ul>
  {/if}
  <div class="tab-content ">

    <div class="tab-pane" id="1" value="1">
      <table class="table table-striped table-hover">
        <thead>
          <th>#</th>
          <th> {"title"|_|ucfirst} </th>
          <th> {"author"|_|ucfirst} </th>
          <th> {"contest"|_|ucfirst} </th>
          <th> {"grade"|_|ucfirst} </th>
          {if $user}
            <th> {"score"|_|ucfirst} </th>
          {/if}
        </thead>

        <tbody>
          {foreach from=$problems key=key item=p}
             <tr class="{if $score[$key]->score==100}success{elseif $score[$key]}warning{/if}">
              <th scope="row">{$p->id}</th>
              <td>
                <a href="problem.php?id={$p->id}">{$p->name}</a>
                {if $p->publicSources}
                <span class="glyphicon glyphicon-eye-open" title="{"Public sources"|_}" style="float:right;"></span>
                {/if}
                {if $p->publicTests}
                <span class="glyphicon glyphicon-folder-open" title="{"Public tests"|_}" style="float:right;"></span>
                {/if}
              </td>     
              </td>
              <td>{$p->author}</td>
              <td>{$p->contest}</td>
              <td>{$p->grade}</td>
              {if $user}
                 {if $score[$key]}
                   <td>{$score[$key]->score}</td>
                 {else}
                   <td> N/A </td>
                 {/if}
               {/if}
             </tr>
          {/foreach}
        </tbody>
      </table>
    </div>

    <div class="tab-pane" id="2">
      <table class="table table-striped table-hover">
        <thead>
          <th>#</th>
          <th> {"title"|_|ucfirst} </th>
          <th> {"author"|_|ucfirst} </th>
          <th> {"contest"|_|ucfirst} </th>
          <th> {"grade"|_|ucfirst} </th>
          {if $user}
            <th> {"score"|_|ucfirst} </th>
          {/if}
        </thead>

        <tbody>
          {foreach from=$unsolved key=key item=p}
             <tr>
              <th scope="row">{$p->id}</th>
              <td>
                <a href="problem.php?id={$p->id}">{$p->name}</a>
                {if $p->publicSources}
                <span class="glyphicon glyphicon-eye-open" title="{"Public sources"|_}" style="float:right;"></span>
                {/if}
                {if $p->publicTests}
                <span class="glyphicon glyphicon-folder-open" title="{"Public tests"|_}" style="float:right;"></span>
                {/if}
              </td> 
              <td>{$p->author}</td>
              <td>{$p->contest}</td>
              <td>{$p->grade}</td>
              {if $user}
                 {if $score[$key]}
                   <td>{$score[$key]->score}</td>
                 {else}
                   <td> N/A </td>
                 {/if}
               {/if}
             </tr>
          {/foreach}
        </tbody>
      </table>
    </div>

    <div class="tab-pane" id="3">
      <table class="table table-striped table-hover">
        <thead>
          <th>#</th>
          <th> {"title"|_|ucfirst} </th>
          <th> {"author"|_|ucfirst} </th>
          <th> {"contest"|_|ucfirst} </th>
          <th> {"grade"|_|ucfirst} </th>
          {if $user}
            <th> {"score"|_|ucfirst} </th>
          {/if}
        </thead>

        <tbody>
          {foreach from=$attempted key=key item=p}
             <tr class="warning">
              <th scope="row">{$p->id}</th>
              <td>
                <a href="problem.php?id={$p->id}">{$p->name}</a>
                {if $p->publicSources}
                <span class="glyphicon glyphicon-eye-open" title="{"Public sources"|_}" style="float:right;"></span>
                {/if}
                {if $p->publicTests}
                <span class="glyphicon glyphicon-folder-open" title="{"Public tests"|_}" style="float:right;"></span>
                {/if}
              </td> 
              <td>{$p->author}</td>
              <td>{$p->contest}</td>
              <td>{$p->grade}</td>
              {if $user}
                 {if $score[$key]}
                   <td>{$score[$key]->score}</td>
                 {else}
                   <td> N/A </td>
                 {/if}
               {/if}
             </tr>
          {/foreach}
        </tbody>
      </table>
    </div>

    <div class="tab-pane" id="4">
      <table class="table table-striped table-hover">
        <thead>
          <th>#</th>
          <th> {"title"|_|ucfirst} </th>
          <th> {"author"|_|ucfirst} </th>
          <th> {"contest"|_|ucfirst} </th>
          <th> {"grade"|_|ucfirst} </th>
          {if $user}
            <th> {"score"|_|ucfirst} </th>
          {/if}
        </thead>

        <tbody>
          {foreach from=$solved key=key item=p}
             <tr class="success">
              <th scope="row">{$p->id}</th>
              <td>
                <a href="problem.php?id={$p->id}">{$p->name}</a>
                {if $p->publicSources}
                <span class="glyphicon glyphicon-eye-open" title="{"Public sources"|_}" style="float:right;"></span>
                {/if}
                {if $p->publicTests}
                <span class="glyphicon glyphicon-folder-open" title="{"Public tests"|_}" style="float:right;"></span>
                {/if}
              </td> 
              <td>{$p->author}</td>
              <td>{$p->contest}</td>
              <td>{$p->grade}</td>
              {if $user}
                 {if $score[$key]}
                   <td>{$score[$key]->score}</td>
                 {else}
                   <td> N/A </td>
                 {/if}
               {/if}
             </tr>
          {/foreach}
        </tbody>
      </table>
    </div>
  </div>
  <div>
    <ul style="display:inline">
      {foreach $first as $i}
        {if $i==$page}
          <strong> <li style="display:inline"><a href="problems.php?tab={$tab}&page={$i}">{$i}</a></li> </strong>
        {else}
        <li style="display:inline"><a href="problems.php?tab={$tab}&page={$i}">{$i}</a></li>
      {/if}
      {/foreach}
    </ul>
    {if $middle}
       <p>...</p>
       {foreach $middle as $i}
         {if $i==$page}
            <strong> <li style="display:inline"><a href="problems.php?tab={$tab}&page={$i}">{$i}</a></li> </strong>
          {else}
          <li style="display:inline"><a href="problems.php?tab={$tab}&page={$i}">{$i}</a></li>
          {/if}
       {/foreach}
    {/if}
    {if $last}
      <p> ... </p>
    	{foreach $last as $i}
      	  {if $i==$page}
      	    <strong> <li style="display:inline"><a href="problems.php?tab={$tab}&page={$i}">{$i}</a></li> </strong>
      	  {else}
          <li style="display:inline"><a href="problems.php?tab={$tab}&page={$i}">{$i}</a></li>
        {/if}
      {/foreach}
    {/if}
  </div>
  {if $canAdd}
    <a href="editProblem.php">{"add a problem"|_}</a>
  {/if}
  <style>
    .table{
      font-size:17px;
    }
    .glyphicon:before{
    	margin-right:15px;
    }
    p{
    	display:inline-block;
    }
    thead{
    	background-color:#373A3C;
    	color:white;
    }
  </style>
  <script>
    $(document).ready(function() {
    	var url_parts = location.href.split('/');
      var last_segment = url_parts[url_parts.length-1];

      if (last_segment == "problems.php")
      	  last_segment = "problems?tab=1";

      $('.nav-tabs a[href="'+last_segment+'"]').parents('li').addClass('active');
      var tab ={$tab};
      $('#'+tab).addClass('active');
});
  </script>
{/block}
