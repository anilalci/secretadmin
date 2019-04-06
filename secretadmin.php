<?php
 /**
  * @package Plugin Secretadmin for Joomla!
  * @version 1.3
  * @author ANIL ALCI
  * @copyright (C) 2019- ANIL ALCI
  * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die;

class plgSystemSecretadmin extends JPlugin {

	/**
 * Application object.
 *
 * @var    JApplicationCms
 * @since  3.2
 */
	protected $app;

	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		JLog::addLogger(array('text_file'=>'secretadmin.php'),JLog::ALL,array('secretadmin'));
	}

	public function onAfterInitialise()
	{
		$folder = $this->params->get('folder','secretadmin');
		if($this->app->isAdmin())
		{
			if(JFactory::getUser()->guest)
			{
				if($this->app->getUserStateFromRequest('secretadmin','secretadmin')!=$folder)
				{
					JLog::add('blocked', JLog::WARNING, 'secretadmin');
					http_response_code($this->params->get('httpcode',404));
					include JPluginHelper::getLayoutPath('system', 'secretadmin');
					jexit();
				}
				else{
					JLog::add(sprintf('opened with folder : %s',$folder), JLog::INFO, 'secretadmin');
				}
			}
		}
		else if(trim(JUri::getInstance()->getPath(),'/')==$folder)
		{
			$this->app->redirect(JRoute::_('administrator/?secretadmin='.urlencode($folder)));
		}
	}
}
