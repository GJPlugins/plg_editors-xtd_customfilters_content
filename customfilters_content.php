<?php
/**
 * @package    customfilters_content
 *
 * @author     oleg <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Customfilters_content plugin.
 *
 * @package   customfilters_content
 * @since     1.0.0
 */
class PlgButtonCustomfilters_content extends CMSPlugin
{
	/**
	 * Application object
	 *
	 * @var    CMSApplication
	 * @since  1.0.0
	 */
	protected $app;

	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  1.0.0
	 */
	protected $db;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

    public function __construct(& $subject, $config)
    {
        $this->_type = $config['type'] ;

        parent::__construct($subject, $config);
        $this->app = \Joomla\CMS\Factory::getApplication();
    }

    /**
     * Display the button
     *
     * @param string $name The name of the button to add
     *
     * @return false|JObject|void The button options as JObject
     *
     * @throws Exception
     * @since   1.5
     */
    public function onDisplay($name)
    {
        $view = $this->app->input->get('view' , false );
        $layout = $this->app->input->get('layout' , false );
        if ( $view != 'article' && $layout != 'edit' ) return  false ;#END IF





        $doc = \Joomla\CMS\Factory::getDocument();

        try
        {
            JLoader::registerNamespace( 'GNZ11' , JPATH_LIBRARIES . '/GNZ11' , $reset = false , $prepend = false , $type = 'psr4' );
            $GNZ11_js =  \GNZ11\Core\Js::instance();
        }
        catch( Exception $e )
        {
            if( !\Joomla\CMS\Filesystem\Folder::exists( $this->patchGnz11 ) && $this->app->isClient('administrator') )
            {
                $this->app->enqueueMessage('Должна быть установлена бибиотека GNZ11' , 'error');
            }#END IF
            throw new \Exception('Должна быть установлена бибиотека GNZ11' , 400 ) ;
        }



        
        
        $url = \Joomla\CMS\Uri\Uri::root().'plugins/editors-xtd/customfilters_content/assets/js/customfilters_content.js'
            . '?' . $this->params->get('version_plugin' , '1.0' ) ;
        $Jpro = $doc->getScriptOptions('Jpro') ;
        $Jpro['load'][] = [
            'u' => $url , // Путь к файлу
            't' => 'js' ,                                       // Тип загружаемого ресурса
            'c' => 'testCallback' ,                             // метод после завершения загрузки
        ];
        $doc->addScriptOptions('Jpro' , $Jpro ) ;





        $params = [
            'plugin' => $this->_name ,
            'group' => $this->_type ,
            'id' => $this->app->input->get('id' , false , 'INT' ) ,
        ];
        $doc->addScriptOptions('customfilters_content' , $params , true ) ;



        $input = JFactory::getApplication()->input;
        $user  = JFactory::getUser();

        // Can create in any category (component permission) or at least in one category
        $canCreateRecords = $user->authorise('core.create', 'com_content')
            || count($user->getAuthorisedCategories('com_content', 'core.create')) > 0;

        // Instead of checking edit on all records, we can use **same** check as the form editing view
        $values = (array) JFactory::getApplication()->getUserState('com_content.edit.article.id');
        $isEditingRecords = count($values);

        // This ACL check is probably a double-check (form view already performed checks)
        $hasAccess = $canCreateRecords || $isEditingRecords;
        if (!$hasAccess)
        {
            return;
        }


        $link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;'
            . JSession::getFormToken() . '=1&amp;editor=' . $name;

        $button = new JObject;
        $button->modal   = false ;
        $button->class   = 'btn addCustomFilter';
//        $button->link    = $link;
        $button->text    = JText::_('PLG_CUSTOMFILTERS_CONTENT_ADD_TO_FILTER');
        $button->name    = 'file-add';
        $button->options = "CustomFilter";

        return $button;
    }

    public function onAjaxCustomfilters_content(){

        JLoader::registerNamespace( 'GNZ11' , JPATH_LIBRARIES . '/GNZ11' , $reset = false , $prepend = false , $type = 'psr4' );
        JLoader::registerNamespace( 'CustomfiltersContent', JPATH_PLUGINS . '/editors-xtd/customfilters_content', $reset = false, $prepend = false, $type = 'psr4' );
        $helper = \CustomfiltersContent\Helper::instance( $this->params );



        $task = $this->app->input->get( 'task', null, 'STRING' );
        try
        {
            $results = $helper->$task();
        } catch (Exception $e)
        {
            $results = $e;
            echo'<pre>';print_r( $results );echo'</pre>'.__FILE__.' '.__LINE__;
            die(__FILE__ .' '. __LINE__ );

        }
        return $results;




    }

}













