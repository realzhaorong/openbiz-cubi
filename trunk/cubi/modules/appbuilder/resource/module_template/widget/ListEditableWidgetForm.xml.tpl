<?xml version="1.0" encoding="UTF-8"?>
<EasyForm Name="DocumentListEditForm" Class="EasyForm" FormType="List" jsClass="jbForm" Title="" Description="" BizDataObj="collab.document.do.DocumentDO" PageSize="10" DefaultForm="Y" TemplateEngine="Smarty" TemplateFile="element_listform_in_tab.tpl.html"  Access="collab_document.access" >
    <DataPanel>          
            <Element Name="row_selections" Class="RowCheckbox" width="20"  Label="" FieldName="Id"/>    
     
        <Element Name="fld_share" Class="ColumnShare" MyPrivateImg="{RESOURCE_URL}/collab/document/images/icon_document_private.png" MySharedImg="{RESOURCE_URL}/collab/document/images/icon_document_shared.png" MyAssignedImg="{RESOURCE_URL}/collab/document/images/icon_document_assigned.png" MyDistributedImg="{RESOURCE_URL}/collab/document/images/icon_document_distributed.png" GroupSharedImg="{RESOURCE_URL}/collab/document/images/icon_document_shared_group.png" OtherSharedImg="{RESOURCE_URL}/collab/document/images/icon_document_shared_other.png" FieldName="create_by" Label="Share" Sortable="Y" AllowURLParam="N" Translatable="N" OnEventLog="N" Link="javascript:;">
 		</Element>	
		<Element Name="fld_Id" Class="common.element.ColumnTitle" Hidden="N" FieldName="Id" Label="ID" Sortable="Y"/>

        <Element Name="fld_title" MaxLength="20" Class="ColumnText" FieldName="title" Label="Title" Link="javascript:" Sortable="Y">
         </Element>	
        <Element Name="fld_description" Class="ColumnText" MaxLength="20" FieldName="description" Label="Description" Sortable="Y" AllowURLParam="N" Translatable="N" OnEventLog="N"/>	
     
		<Element Name="fld_type" Class="ColumnText" Style="line-height:24px;" FieldName="type_name" Label="Type" Sortable="Y" AllowURLParam="N" Translatable="N" OnEventLog="N"/>						        
        
    </DataPanel>
<ActionPanel>
		<Element Name="btn_add" Class="Button" text="Add" CssClass="button_gray_add">
            <EventHandler Name="add_onclick" Event="onclick" Function="LoadDialog(collab.document.widget.DocumentNewForm)"/>
        </Element>
        <Element Name="btn_spacer" Class="Spacer" Width="10" ></Element>    
        <Element Name="btn_pick" Class="Button" text="Pick Docs" CssClass="button_gray_w">
            <EventHandler Name="pick_onclick" Event="onclick" Function="LoadDialog(collab.document.widget.DocumentPickForm)"/>
        </Element>
        <Element Name="btn_remove" Class="Button" text="Remove" CssClass="button_gray_w">
            <EventHandler Name="remove_onclick" Event="onclick" Function="RemoveRecord()"/>
        </Element>
    </ActionPanel> 
    <NavPanel>
		<Element Name="page_selector" Class="PageSelector" Text="{@:m_CurrentPage}" Label="Go to Page" CssClass="input_select" cssFocusClass="input_select_focus">
            <EventHandler Name="btn_page_selector_onchange" Event="onchange" Function="GotoSelectedPage(page_selector)"/>
        </Element>
        <Element Name="pagesize_selector" Class="PagesizeSelector" Text="{@:m_Range}" Label="Show Rows" CssClass="input_select" cssFocusClass="input_select_focus">
            <EventHandler Name="btn_pagesize_selector_onchange" Event="onchange" Function="SetPageSize(pagesize_selector)"/>
        </Element>       
        <Element Name="btn_first"  Class="Button" Enabled="{(@:m_CurrentPage == 1)?'N':'Y'}" Text="" CssClass="button_gray_navi {(@:m_CurrentPage == 1)?'first_gray':'first'}">
            <EventHandler Name="first_onclick" Event="onclick" Function="GotoPage(1)"/>
        </Element>
        <Element Name="btn_prev" Class="Button" Enabled="{(@:m_CurrentPage == 1)?'N':'Y'}" Text="" CssClass="button_gray_navi {(@:m_CurrentPage == 1)?'prev_gray':'prev'}">
            <EventHandler Name="prev_onclick" Event="onclick" Function="GotoPage({@:m_CurrentPage - 1})"/>
        </Element>
        <Element Name="txt_page" Class="LabelText" Text="{'@:m_CurrentPage of @:m_TotalPages '}">
        </Element>
        <Element Name="btn_next" Class="Button" Enabled="{(@:m_CurrentPage == @:m_TotalPages )?'N':'Y'}" Text="" CssClass="button_gray_navi {(@:m_CurrentPage == @:m_TotalPages)?'next_gray':'next'}">
            <EventHandler Name="next_onclick" Event="onclick" Function="GotoPage({@:m_CurrentPage + 1})"/>
        </Element>
        <Element Name="btn_last" Class="Button" Enabled="{(@:m_CurrentPage == @:m_TotalPages )?'N':'Y'}" Text="" CssClass="button_gray_navi {(@:m_CurrentPage == @:m_TotalPages)?'last_gray':'last'}">
            <EventHandler Name="last_onclick" Event="onclick" Function="GotoPage({@:m_TotalPages})"/>
        </Element>
    </NavPanel> 


</EasyForm>
