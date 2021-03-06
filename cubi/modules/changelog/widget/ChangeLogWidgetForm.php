<?php
/**
 * Openbiz Cubi Application Platform
 *
 * LICENSE http://code.google.com/p/openbiz-cubi/wiki/CubiLicense
 *
 * @package   cubi.changelog.widget
 * @copyright Copyright (c) 2005-2011, Openbiz Technology LLC
 * @license   http://code.google.com/p/openbiz-cubi/wiki/CubiLicense
 * @link      http://code.google.com/p/openbiz-cubi/
 * @version   $Id$
 */

class ChangeLogWidgetForm extends EasyForm
{

	public function fetchDataSet()
	{
		$result = parent::fetchDataSet();
		$resultSet = array();
		$messageFile_loaded = false;
		foreach ($result as $record)
		{
			$form = unserialize($record['form']);
			$data = unserialize($record['data']);
			
			if(!$messageFile_loaded)
			{
				$this->m_Messages = Resource::loadMessage($form['message_file'] , $form['package']);
				$formObj = BizSystem::getObject($this->m_ParentFormName);				
				I18n::AddLangData("common",substr($formObj->m_Package,0,intval(strpos($formObj->m_Package,'.'))));
				$messageFile_loaded = true;
			}
			if(is_array($data)){
			foreach ($data as $key=>$value)
			{
				$elemObjMeta = $data[$key]['element'];
				$elemObjMeta = $this->replaceElementClass($elemObjMeta);
				if($elemObjMeta["ATTRIBUTES"]['FIELDTYPE']=='ExtendField')
				{					
					$extendSettingId = (int)str_replace("extend_field_", $replace, $elemObjMeta["ATTRIBUTES"]['NAME']);
					$elemObjMeta["ATTRIBUTES"] = BizSystem::getService("extend.lib.ExtendFieldService")->translateElemArr($elemObjMeta["ATTRIBUTES"],$extendSettingId);					
				}
				
				$objName = $elemObjMeta["ATTRIBUTES"]['CLASS'];
				
				$formObj = BizSystem::getObject($this->m_ParentFormName);
				$elemObj = new $objName($elemObjMeta,$formObj);

				$data[$key]['label'] = $elemObj->renderLabel(); 
				
				$elemObj->setValue($data[$key]['old']);
				$elemObj->m_Text = str_replace("{@:Elem[".$elemObj->m_Name."].Value}",$data[$key]['old'],$elemObj->m_Text);
				$data[$key]['old'] = $elemObj->render();
				
				$elemObj = new $objName($elemObjMeta,$formObj);
				$elemObj->setValue($data[$key]['new']);
				$elemObj->m_Text = str_replace("{@:Elem[".$elemObj->m_Name."].Value}",$data[$key]['new'],$elemObj->m_Text);
				$data[$key]['new'] = $elemObj->render();
				
				unset($data[$key]['element']);
			}
			}
			$record['data'] = $data;
			unset($record['form']);
			$resultSet[] = $record;
		}
		return $resultSet;
	}
	
	protected function replaceElementClass($metaArr)
	{
		
		
		$className = $metaArr["ATTRIBUTES"]['CLASS'];
		
		switch($className)
		{
			case "Listbox":
			case "DropDownList":
			case "LabelList":
			case "ColumnList":
				$newClass = "LabelList";
				break;
			
			case "ColumnBool":
			case "LabelBool":
			case "Checkbox":
				$newClass = "LabelBool";
				break;
				
			case "InputText":
			case "Textarea":
			case "InputDate":
			case "InputDatetime":							
				$newClass = "LabelText";				
				break;
			case "LabelImage":
			case "ColumnImage":								
				$newClass = "LabelImage";				
				break;
			default:				
				$newClass = "LabelText";				
				if(preg_match('/Selector/si',$className)){					
					$newClass = "LabelList";					
				}elseif(preg_match('/List/si',$className)){
					$newClass = "LabelList";
				}
				break;
		}		
		
		
		$selectFrom = $metaArr["ATTRIBUTES"]['SELECTFROM'];
		if(strpos($selectFrom,'@')){
			$selectFrom = substr($selectFrom,0,strpos($selectFrom,','));
		}		
		$metaArr["ATTRIBUTES"]['SELECTFROM']=$selectFrom;		
		$metaArr["ATTRIBUTES"]['CLASS'] = $newClass;
		$metaArr["ATTRIBUTES"]['CSSCLASS']="";
		return $metaArr;
	}
}
?>