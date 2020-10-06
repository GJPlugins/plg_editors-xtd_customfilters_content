<?php

/***********************************************************************************************************************
 * ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗  ╔╗╔╗╔╗ ╔═══╗ ╔══╗   ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
 * ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝  ║║║║║║ ║╔══╝ ║╔╗║   ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
 * ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗  ║║║║║║ ║╚══╗ ║╚╝╚╗  ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
 * ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║  ║║║║║║ ║╔══╝ ║╔═╗║  ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
 * ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║  ║╚╝╚╝║ ║╚══╗ ║╚═╝║  ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
 * ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝  ╚═╝╚═╝ ╚═══╝ ╚═══╝  ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
 *----------------------------------------------------------------------------------------------------------------------
 * @author Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
 * @date 05.10.2020 18:36
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 **********************************************************************************************************************/

namespace CustomfiltersContent;
defined('_JEXEC') or die; // No direct access to this file

use Exception;
use JDatabaseDriver;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

/**
 * Class Helper
 * @package CustomfiltersContent
 * @since 3.9
 * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
 * @date 05.10.2020 18:36
 *
 */
class Helper
{

    /**
     * @var CMSApplication|null
     * @since 3.9
     */
    private $app;
    /**
     * @var JDatabaseDriver|null
     * @since 3.9
     */
    private $db;
    /**
     * Array to hold the object instances
     *
     * @var Helper
     * @since  1.6
     */
    public static $instance;

    /**
     * Helper constructor.
     * @param $params array|object
     * @throws Exception
     * @since 3.9
     */
    public function __construct($params)
    {
        $this->app = Factory::getApplication();
        $this->db = Factory::getDbo();
        return $this;
    }

    /**
     * @param array $options
     *
     * @return Helper
     * @throws Exception
     * @since 3.9
     */
    public static function instance($options = array())
    {
        if (self::$instance === null)
        {
            self::$instance = new self($options);
        }
        return self::$instance;
    }

    /**
     * Сохранить набор параметров фильтров для статьи
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 06.10.2020 01:06
     *
     */
    public function saveForm(){
        $formData = $this->app->input->get('jform' , [] , 'ARRAY' ) ;
        $id = $this->app->input->get('id' , false , 'INT' ) ;

        // Создайте объект для записи, которую мы собираемся обновить.
        $object = new \stdClass();


        $object->id = $id;
        $object->customfilters_content = json_encode( $formData['felter_params'] );
        $result = \Joomla\CMS\Factory::getDbo()->updateObject('#__content', $object, 'id');

        $mes = '<div id="success" style="color: #366b01">Сохранено ! :)</div>' ;
        if ( !$result )
        {
            $mes = '<div id="success" style="color: #b71e1e">Ошибка сохранения ! :(</div>' ;
        }#END IF


        echo new \JResponseJson([ 'html' => $mes ]);
        die();

    }

    /**
     * Загрузка формы выбора фильтров
     * @throws Exception
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 05.10.2020 22:20
     *
     */
    public function getForm(){

        if (! defined('VM_SHOP_LANG_PRFX')) {
            define('VM_SHOP_LANG_PRFX', \VmConfig::$defaultLang );
        }

        if (! defined('JLANGPRFX')) {
            define('JLANGPRFX',  VMLANG  );
        }

        require_once JPATH_SITE . '/modules/mod_cf_filtering/bootstrap.php';
        $module = \Joomla\CMS\Helper\ModuleHelper::getModule( 'mod_cf_filtering'  );
        $params = $this->getParamsExtension('mod_cf_filtering' /*, true*/ );
        $self = new \ModCfFilteringHelper($params, $module);
        $this->filtersData = $self->getFilters( true );

        $id = $this->app->input->get('id' , false , 'INT') ;
        $Query = $this->db->getQuery(true);
        $Query->select($this->db->quoteName('customfilters_content'))
            ->from( $this->db->quoteName('#__content'));
        $where = [
            $this->db->quoteName('id') . '='. $this->db->quote( $id  )
        ];
        $Query->where( $where );
        $this->db->setQuery($Query);
        $customfilters_content= $this->db->loadResult();
        $this->filterContentParam = json_decode( $customfilters_content ) ;

        $html = $this->loadTemplate('form-add');
        $result = [
            'html' => $html ,
        ];
        echo new \JResponseJson($result);
        die();
    }

    /**
     * Загрузить парамертры расшерения
     * @param $name
     * @param false $toString
     * @return Registry|mixed|null
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 05.10.2020 20:56
     *
     */
    public function getParamsExtension ($name , $toString = false ){
        $db = \Joomla\CMS\Factory::getDbo();
        $Query = $db->getQuery(true);
        $Query->select($db->quoteName('params'))->from($db->quoteName('#__extensions'));
        $where = [
            $db->quoteName('name') .'='. $db->quote($name )
        ] ;
        $Query->where($where) ;
        $db->setQuery($Query);
        $res = $db->loadResult() ;
        if ($toString )
        {
            return $res ;
        }#END IF
        $params = new Registry( $res );
        return $params ;
    }

    /**
     * Загрузите файл макета плагина. Эти файлы могут быть переопределены с помощью стандартного Joomla! Шаблон
     *
     * Переопределение :
     *                  JPATH_THEMES . /html/plg_{TYPE}_{NAME}/{$layout}.php
     *                  JPATH_PLUGINS . /{TYPE}/{NAME}/tmpl/{$layout}.php
     *                  or default : JPATH_PLUGINS . /{TYPE}/{NAME}/tmpl/default.php
     *
     *
     * переопределяет. Load a plugin layout file. These files can be overridden with standard Joomla! template
     * overrides.
     *
     * @param string $layout The layout file to load
     * @param array  $params An array passed verbatim to the layout file as the `$params` variable
     *
     * @return  string  The rendered contents of the file
     *
     * @since   5.4.1
     */
    private function loadTemplate ( $layout = 'default' )
    {

        $path = \Joomla\CMS\Plugin\PluginHelper::getLayoutPath( 'editors-xtd', 'customfilters_content', $layout );
        // Render the layout
        ob_start();
        include $path;
        return ob_get_clean();
    }






}
















