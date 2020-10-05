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

    /**
     * Display the button
     *
     * @param   string  $name  The name of the button to add
     *
     * @return  JObject  The button options as JObject
     *
     * @since   1.5
     */
    public function onDisplay($name)
    {
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
        $button->modal   = true;
        $button->class   = 'btn';
        $button->link    = $link;
        $button->text    = JText::_('PLG_ARTICLE_BUTTON_ARTICLE');
        $button->name    = 'file-add';
        $button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

        return $button;
    }
}
