<?php
/**
 * Openbiz Cubi Application Platform
 *
 * LICENSE http://code.google.com/p/openbiz-cubi/wiki/CubiLicense
 *
 * @package   cubi.common.form
 * @copyright Copyright (c) 2005-2011, Openbiz Technology LLC
 * @license   http://code.google.com/p/openbiz-cubi/wiki/CubiLicense
 * @link      http://code.google.com/p/openbiz-cubi/
 * @version   $Id$
 */

class ErrorForm extends EasyForm
{
        public $m_AdminEmail = "jixian2003@qq.com";
        public $m_AdminName  = "Administrator";
        
	    function __construct(&$xmlArr)
	    {
	        parent::readMetadata($xmlArr);
	        if($_GET['ob_err_msg'])
        	{
				$this->m_Errors = array("system"=>$_GET['ob_err_msg']);
        	}	      
	    }        
/*
	    public function getSessionVars($sessionContext)
	    {
	        parent::getSessionVars($sessionContext);
	        $sessionContext->getObjVar($this->m_Name, "Errors", $this->m_Errors);	        	          
	    }    
	    
	    public function setSessionVars($sessionContext)
	    {
	    	parent::setSessionVars($sessionContext);
	        $sessionContext->setObjVar($this->m_Name, "Errors", $this->m_Errors);      
	    }
*/	    
        public function Report()
        {
        	//send an email to admin includes error messages;
        	$recipient['email'] = $this->m_AdminEmail;
			$recipient['name']  = $this->m_AdminName;
        	$emailObj 	= BizSystem::getService(USER_EMAIL_SERVICE);
       	 	$emailObj->SystemInternalErrorEmail($recipient,$this->m_Errors["system"]);       	 	
        	$this->m_Notices = array("status"=>"REPORTED");
        	$this->ReRender();
        }
}
?>