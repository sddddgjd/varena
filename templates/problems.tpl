{extends file="layout.tpl"}

{block name=title}{"problems"|_|ucfirst}{/block}

{block name=content}
  {if $user}
  <ul class="nav nav-tabs">
    <li class="active">
      <a  href="#1" data-toggle="tab">{"All problems"|_}</a>
    </li>
    <li><a href="#2" data-toggle="tab">{"Unsolved"|_}</a>
    </li>
    <li><a href="#3" data-toggle="tab">{"Attempted"|_}</a>
    </li>
    <li><a href="#4" data-toggle="tab">{"Solved"|_}</a>
    </li>
  </ul>
  {/if}
  <div class="tab-content ">

    <div class="tab-pane active" id="1">
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
              <td><a href="problem.php?id={$p->id}">{$p->name}</a></td>
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
              <td><a href="problem.php?id={$p->id}">{$p->name}</a></td>
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
              <td><a href="problem.php?id={$p->id}">{$p->name}</a></td>
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
              <td><a href="problem.php?id={$p->id}">{$p->name}</a></td>
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
  {if $canAdd}
    <a href="editProblem.php">{"add a problem"|_}</a>
  {/if}
  <style>
    .table{
      font-size:17px;
    }
    thead{
    	background-color:#373A3C;
    	color:white;
    }
  </style>
{/block}
