<?php

class ChartForm extends EasyForm
{
	public $chartCategory;
	public $chartDataset;
	public $chartDataAttrset;
    public $m_Attrs;
    public $m_SubType;
    
    protected function readMetadata(&$xmlArr)
    {
        parent::readMetaData($xmlArr);
        $this->m_SubType = isset($xmlArr["EASYFORM"]["ATTRIBUTES"]["CHARTTYPE"]) ? $xmlArr["EASYFORM"]["ATTRIBUTES"]["CHARTTYPE"] : null;
        $this->m_Attrs = isset($xmlArr["EASYFORM"]["ATTRIBUTES"]["CHARTATTRS"]) ? $xmlArr["EASYFORM"]["ATTRIBUTES"]["CHARTATTRS"] : null;
    }
	
	public function outputAttrs()
    {
        $output = parent::outputAttrs();
    	$output['name'] = $this->m_Name;
        $output['title'] = $this->m_Title;
        $output['description'] = str_replace('\n', "<br />", $this->m_Description);
        $output['data'] = $this->draw();
        return $output;
    }
    
    public function validateRequest($methodName)
    {
    	$methodName = strtolower($methodName);
        if ($methodName == "draw" || $methodName == "invoke") 
            return true;
        return parent::validateRequest($methodName);
    }
    
    public function fetchDataSet()
    {
    }
    
    protected function fetchDatasetByColumn()
    {
    	// query recordset first
		$dataObj = $this->getDataObj();

        QueryStringParam::setBindValues($this->m_SearchRuleBindValues);

        if ($this->m_RefreshData)
            $dataObj->resetRules();
        else
            $dataObj->clearSearchRule();
         		
		//echo "search rule is $this->m_SearchRule"; exit;
		if ($this->m_FixSearchRule)
        {
            if ($this->m_SearchRule)
                $searchRule = $this->m_SearchRule . " AND " . $this->m_FixSearchRule;
            else
                $searchRule = $this->m_FixSearchRule;
        }
        else
            $searchRule = $this->m_SearchRule;
        $dataObj->setSearchRule($searchRule);
        if($this->m_StartItem>1)
        {
            $dataObj->setLimit($this->m_Range, $this->m_StartItem);
        }
        else
        {
            $dataObj->setLimit($this->m_Range, ($this->m_CurrentPage-1)*$this->m_Range);
        }
        QueryStringParam::setBindValues($this->m_SearchRuleBindValues);
        $resultRecords = $dataObj->fetch();
        $this->m_TotalRecords = $dataObj->count();
        if ($this->m_Range && $this->m_Range > 0)
            $this->m_TotalPages = ceil($this->m_TotalRecords/$this->m_Range);
		//$resultRecords = $dataObj->directFetch($searchRule);
		$counter = 0;
        while (true)
        {
            $arr = $resultRecords[$counter];
            if (!$arr) break;
            foreach ($this->m_DataPanel as $element)
            {
            	if ($element->fieldName && isset($arr[$element->fieldName]))
            	{
            		//echo "class is $element->m_Class";
            		if ($element->m_Class == "report.lib.ChartData")
            		{
            		    $this->chartDataset[$element->key][] 	 = $arr[$element->fieldName];
            		    $this->chartDataAttrset[$element->key] = $element->attrs;
            		}
            		if ($element->m_Class == "report.lib.ChartCategory")
            		    $this->chartCategory[] = $arr[$element->fieldName];
            	}
            }
            $counter++;
        }      
    }
    
    public function draw()
    {
    	$path = MODULE_PATH.'/report/lib';
    	set_include_path(get_include_path() . PATH_SEPARATOR . $path);    	
        if(strtolower(FusionChartVersion)=="pro"){
    		require_once('fusionpro/FusionCharts_Gen.php'); 		
    	}
    	else
    	{
        	require_once('fusion/FusionCharts_Gen.php');
    	} 
    	return $this->drawChart();
    }
    
    public function redrawChart(){
    	return $this->updateForm();
    }
    
    //TODO: for different type of chart, use template? or render class?
    protected function drawChart()
    {
        $this->fetchDatasetByColumn();
        
        if ($this->checkChartType($this->m_SubType) == false) {
            $errmsg = "Unsupported chart type $this->m_SubType.";
            trigger_error($errmsg, E_USER_ERROR);
            return;
        }
                
        if (count($this->chartDataset) > 1)
            return $this->drawMultiSeries();
        else if (count($this->chartDataset) == 1)
            return $this->drawSingleSeries();
        else {
            $errmsg = "Cannot draw chart due to empty data set.";
            //trigger_error($errmsg, E_USER_ERROR);
            return;
        }
        return "";
    }
    
    protected function drawSingleSeries()
    {
    	//load color styles
    	$colorObj = BizSystem::getObject("report.do.ReportColorDO");
    	$colorList = $colorObj->directFetch("");
    	
        $FC = new FusionCharts($this->m_SubType, $this->m_Width, $this->m_Height); 
        $this->seChartParams($FC);
        if(is_array($this->chartDataset)){
	        foreach ($this->chartDataset as $key=>$ds) {
	            for ($i=0; $i<count($ds); $i++){
	                $FC->addChartData($ds[$i], "name=".$this->chartCategory[$i].";color=".$colorList[$i]["color_code"].";".$this->chartDataAttrset[$key]);
	            }
	        }
        }
        
        return $FC->renderChart(false, false);
    }
    
    protected function drawMultiSeries()
    {
    	if (empty($this->chartCategory)) {
            $errmsg = "Please set category for multi series chart.";
            trigger_error($errmsg, E_USER_ERROR);
            return;
        }
        //load color styles
    	$colorObj = BizSystem::getObject("report.do.ReportColorDO");
    	$colorList = $colorObj->directFetch("");
    	    	
        $FC = new FusionCharts($this->m_SubType, $this->m_Width, $this->m_Height); 
        $this->seChartParams($FC);
        # category names
        foreach ($this->chartCategory as $cat) {
            $FC->addCategory($cat);
        }
        $colorI=0;
        foreach ($this->chartDataset as $key=>$ds) {
            if(preg_match("/color=/si",$this->chartDataAttrset[$key])){
            	$color = "";
            }else{
        		$color = "color=".$colorList[$colorI]["color_code"].";";
            }
        	
        	$FC->addDataset($key,$color.$this->chartDataAttrset[$key]);
            for ($i=0; $i<count($ds); $i++){
                $FC->addChartData($ds[$i]);
            }
            $colorI++;
        }
        return $FC->renderChart(false, false);
    }
    
    protected function seChartParams($FC)
    {
        if(strtolower(FusionChartVersion)=="pro"){
    		$FC->setSWFPath(RESOURCE_URL."/report/js/FusionChartsPro/");    		
    	}
    	else
    	{
        	$FC->setSWFPath(RESOURCE_URL."/report/js/FusionCharts/");
    	}
        
        # Set chart attributes
        //$FC->setChartParam('caption',$this->m_Title);
        $FC->setChartParam('formatNumberScale',1);
        $FC->setChartParam('decimalPrecision',0);
        
        if(strtolower(FusionChartVersion)=="pro"){
	        $FC->setChartParam('exportEnabled',1);
	        $FC->setChartParam('exportAtClient',0);
	        $FC->setChartParam('exportShowMenuItem',1);
	        $FC->setChartParam('exportAction',"save");
	        $FC->setChartParam('exportHandler',RESOURCE_URL."/report/js/FusionChartsPro/FCExporter.php");
	        $FC->setChartParam('exportFileName',$this->m_Name);
        }
        //$strParam = "caption=".$this->m_Title.";canvasBorderColor=CECECE;baseFontSize=12;".$this->m_Attrs;
        $strParam = "canvasBorderColor=CECECE;baseFontSize=10;".$this->m_Attrs;
        $FC->setChartParams($strParam);
    }
    
    protected function checkChartType($type)
    {
        switch ($this->m_SubType) {
            case "Column2D" : 
            case "Column3D" : 
            case "Bar2D" : 
            case "Area2D" :
            case "Line" : 
            case "Pie2D" : 
            case "Pie3D" :
            case "MSColumn2D" :
            case "MSColumn2DLineDY" :
            case "MSColumn3D" :
            case "MSColumn3DLineDY" :
            case "MSBar2D" :
            case "MSArea2D" :
            case "MSLine" :
            case "StackedBar2D" : 
            case "StackedColumn2D" : 
            case "StackedColumn3D" : 
            case "StackedArea2D" :
                return true;
        }
        return false;
    }
}
?>