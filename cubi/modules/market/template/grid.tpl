<form id='{$form.name}' name='{$form.name}'>
<div style="padding-left:25px;padding-right:40px;">
	<div>
{include file="system_appbuilder_btn.tpl.html"}
	<table><tr><td>
		{if $form.icon !='' }
		<div class="form_icon"><img  src="{$form.icon}" border="0" /></div>
		{/if}
		<div style="float:left; width:600px;">
		<h2>
		{$form.title}
		</h2> 
		<p class="form_desc">{$form.description}</p>
		</div>
	</td></tr></table>
	</div>
{if $actionPanel or $searchPanel }	
	<div class="form_header_panel">	

		<div class="search_panel" style="float:left;padding-left:10px;">		
			{foreach item=elem from=$searchPanel}
				{if $elem.type!='InputDateRangePicker'}
					{if $elem.label}<span style="float:left;padding-right:5px;line-height:20px">{$elem.label}</span> {/if} {$elem.element}
				{/if}
			{/foreach}
		</div>
		
		<div class="action_panel" style="float:right;width:120px;">
			{foreach item=elem from=$searchPanel}
				{if $elem.type=='InputDateRangePicker'} {$elem.element}{/if} 
			{/foreach}
		
			{foreach item=elem from=$actionPanel}
			    	{$elem.element}
			{/foreach}
		</div>		
	</div>	
{/if}	

<div class="from_table_container">
<!-- table start -->
<table border="0" cellpadding="0" cellspacing="0" class="form_table" id="{$form.name}_data_table">
	<thead>		
     {foreach item=cell key=elems_name from=$dataPanel.elems}	
     	{if $cell.type=='ColumnStyle'}
     		{assign var=row_style_name value=$elems_name}     	
		{else}
			{if $cell.type=='RowCheckbox'}
				{assign var=th_style value="text-align:left;padding-left:10px;"}
			{else}
				{assign var=th_style value=""}
			{/if}
         <th onmouseover="this.className='hover'" 
			onmouseout="this.className=''"
				nowrap="nowrap" style="{$th_style}"
			>{$cell.label}</th>	 
		{/if}
     {/foreach}
	</thead>
     {assign var=row_counter value=0}            
     {foreach item=row from=$dataPanel.data}
     	
     	 {if $row.$row_style_name != ''}
     	 	{assign var=row_style value=$dataPanel.data.$row_counter.$row_style_name}
     	 {else}
     	 	{assign var=row_style value=''}
     	 {/if}
     	 
         {if $row_style != ''}
		 	<tr id="{$form.name}-{$dataPanel.ids[$row_counter]}" 
					style="{$row_style}"										
					onclick="Openbiz.CallFunction('{$form.name}.SelectRecord({$dataPanel.ids[$row_counter]})');">
		 {elseif $form.currentRecordId == $dataPanel.ids[$row_counter]}  
         {assign var=default_selected_id value=$dataPanel.ids[$row_counter]}       	
			<tr id="{$form.name}-{$dataPanel.ids[$row_counter]}" 
					style="{$row_style}"
					class="selected"  normal="even" select="selected"
					onmouseover="if(this.className!='selected')this.className='hover'" 
					onmouseout="if(this.className!='selected')this.className='even'" 
					onclick="Openbiz.CallFunction('{$form.name}.SelectRecord({$dataPanel.ids[$row_counter]})');">
         {elseif $row_counter == 0 and $form.currentRecordId == ""}
         {assign var=default_selected_id value=$dataPanel.ids[$row_counter]}    
			<tr id="{$form.name}-{$dataPanel.ids[$row_counter]}" 
					style="{$row_style}"
					class="selected"  normal="even" select="selected"
					onmouseover="if(this.className!='selected')this.className='hover'" 
					onmouseout="if(this.className!='selected')this.className='even'" 
					onclick="Openbiz.CallFunction('{$form.name}.SelectRecord({$dataPanel.ids[$row_counter]})');">
          {elseif $row_counter is odd}
		   <tr id="{$form.name}-{$dataPanel.ids[$row_counter]}" 
		   			style="{$row_style}"
		   			class="odd"  normal="odd" select="selected"
					onmouseover="if(this.className!='selected')this.className='hover'" 
					onmouseout="if(this.className!='selected')this.className='odd'"  
					onclick="Openbiz.CallFunction('{$form.name}.SelectRecord({$dataPanel.ids[$row_counter]})');">
         {else}
			<tr id="{$form.name}-{$dataPanel.ids[$row_counter]}" 
					style="{$row_style}"
					class="even"  normal="even" select="selected"
					onmouseover="if(this.className!='selected')this.className='hover'" 
					onmouseout="if(this.className!='selected')this.className='even'" 
					onclick="Openbiz.CallFunction('{$form.name}.SelectRecord({$dataPanel.ids[$row_counter]})');">
         {/if}
         
         {assign var=col_counter value=0}    
         {foreach key=name item=cell key=cell_name from=$row}
         	{if $col_counter eq 0}
         		{assign var=col_class value=' class="row_header" '}    
         	{else}
         		{assign var=col_class value=' '}
         	{/if}
         	{if $cell_name != $row_style_name}
	            {if $cell != ''}            	
	              <td {$col_class} style="{$row_style}" nowrap="nowrap" >{$cell}</td>
	            {else}
	              <td {$col_class} style="{$row_style}" nowrap="nowrap" >&nbsp;</td>
	            {/if}
            {/if}
            {assign var=col_counter value=$col_counter+1}
         {/foreach}
                  
		{assign var=row_counter value=$row_counter+1}
		</tr>
     {/foreach}
  
							
</table>
</div>
<!-- status switch  -->
<script>
{if $form.status eq 'Enabled'}
{elseif $form.status eq 'Disabled'}
$('{$form.name}_data_table').fade({literal}{ duration: 0.5, from: 1, to: 0.35 }{/literal});
{/if}
</script>
<span id='{$form.name}_selected_id' style="display:none">{$default_selected_id}</span>
<!-- table end -->	

	<div class="form_footer_panel">
		<div class="ajax_indicator">
			<div id='{$form.name}.load_disp' style="display:none" >
				<img src="{$image_url}/form_ajax_loader.gif"/>
			</div>
		</div>
		<div class="navi_panel">
{if $navPanel}
   {foreach item=elem from=$navPanel}
   		{if $elem.label} <label style="width:68px;">{$elem.label}</label>{/if}
    	{$elem.element}
   {/foreach}
{/if}			
		
		</div>		
	</div>
	<div class="v_spacer"></div>
	{if $errors}
	    <div id='errorsDiv' class='innerError errorBox'>
	    {foreach item=errMsg from=$errors}
	        <div>{$errMsg}</div>
	    {/foreach}
	    {literal}<script>try{setTimeout("$('errorsDiv').fade( {from: 1, to: 0});",3000);}catch(e){}</script>{/literal}
	    </div>
	    <div class="v_spacer"></div>
	{/if}
	
	{if $notices}
	    <div id='noticeDiv' class='noticeBox' >
	    {foreach item=noticeMsg from=$notices}
	        <div>{$noticeMsg}</div>
	    {/foreach}
	    </div>
	    {literal}<script>try{setTimeout("$('noticeDiv').fade( {from: 1, to: 0});",3000);}catch(e){};</script>{/literal}
	    <div class="v_spacer"></div>
	{/if}
	
</div>
</form>