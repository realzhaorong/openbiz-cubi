<?php
/**
 * Openbiz Cubi Application Platform
 *
 * LICENSE http://code.google.com/p/openbiz-cubi/wiki/CubiLicense
 *
 * @package   cubi.system.form
 * @copyright Copyright (c) 2005-2011, Openbiz Technology LLC
 * @license   http://code.google.com/p/openbiz-cubi/wiki/CubiLicense
 * @link      http://code.google.com/p/openbiz-cubi/
 * @version   $Id$
 */

class RoleListForm extends  EasyForm
{
	public function SetPermission()
	{		
        $id = BizSystem::clientProxy()->getFormInputs('_selectedId');
		$redirectPage = APP_INDEX."/system/role_detail/".$id;
		BizSystem::clientProxy()->ReDirectPage($redirectPage);
	}
	
}
?>