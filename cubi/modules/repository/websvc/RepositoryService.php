<?php
include_once MODULE_PATH.'/websvc/lib/WebsvcService.php';
class RepositoryService extends WebsvcService
{
	protected $m_CategoryDO = "repository.category.do.CategoryDO";
	protected $m_ApplicationDO = "repository.application.do.ApplicationDO";
	protected $m_RepositorySettingDO = "myaccount.do.PreferenceDO";
	
    public function fetchRepoInfo()
    {
    	$searchRule = "[user_id]='0' AND [name] LIKE 'repo_%'";   
    	$dataObj = BizSystem::getObject($this->m_RepositorySettingDO);    	
        $resultRecords = $dataObj->directfetch($searchRule);
        $prefRecord = array();
        foreach($resultRecords as $record){
        	$prefRecord["_".$record['name']] = $record["value"];
        }
        return $prefRecord;
    }

    public function fetchFeaturedApps()
    {
    	$searchRule = "[status]=1 AND [release_time] < NOW() AND [featured]=1";
    	$dataObj = BizSystem::getObject($this->m_ApplicationDO,1);    	   
        $resultRecords = $dataObj->directfetch($searchRule);  
        $resultSet = array();
       	foreach($resultRecords as $record)
       	{
       		$resultSet[] = $record;
       	}
        return $resultSet;
    }    

    public function fetchApplications($cat_id = null)
    {
    	$searchRule = "[status]=1 AND [release_time] < NOW() ";
    	if($cat_id){
    		$searchRule .= " AND [cat_id]=$cat_id";
    	}
    	$dataObj = BizSystem::getObject($this->m_ApplicationDO,1);    	   
        $resultRecords = $dataObj->directfetch($searchRule);  
        $resultSet = array();
       	foreach($resultRecords as $record)
       	{
       		$resultSet[] = $record;
       	}
        return $resultSet;
    }        

    public function fetchCategories()
    {
    	$searchRule = "[publish]=1";
    	$dataObj = BizSystem::getObject($this->m_CategoryDO,1);    	   
        $resultRecords = $dataObj->directfetch($searchRule);  
        $resultSet = array();        
       	foreach($resultRecords as $record)
       	{
       		$resultSet[] = $record;
       	}
        return $resultSet;
    }        
    
}
?>