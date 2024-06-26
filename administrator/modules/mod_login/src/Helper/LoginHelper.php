<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  mod_login
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\Login\Administrator\Helper;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Helper for mod_login
 *
 * @since  1.6
 */
class LoginHelper
{
    /**
     * Get an HTML select list of the available languages.
     *
     * @param   CMSApplicationInterface  $app  The application
     *
     * @return  string
     *
     * @since   5.1.0
     */
    public function getLanguages(CMSApplicationInterface $app)
    {
        $languages = LanguageHelper::createLanguageList(null, JPATH_ADMINISTRATOR, false, true);

        if (\count($languages) <= 1) {
            return '';
        }

        usort(
            $languages,
            function ($a, $b) {
                return strcmp($a['value'], $b['value']);
            }
        );

        // Fix wrongly set parentheses in RTL languages
        if ($app->getLanguage()->isRtl()) {
            foreach ($languages as &$language) {
                $language['text'] .= '&#x200E;';
            }
        }

        array_unshift($languages, HTMLHelper::_('select.option', '', Text::_('JDEFAULTLANGUAGE')));

        return HTMLHelper::_('select.genericlist', $languages, 'lang', 'class="form-select"', 'value', 'text', null);
    }

    /**
     * Get the redirect URI after login.
     *
     * @return  string
     *
     * @since   5.1.0
     */
    public function getReturnUriString()
    {
        $uri    = Uri::getInstance();
        $return = 'index.php' . $uri->toString(['query']);

        if ($return !== 'index.php?option=com_login') {
            return base64_encode($return);
        }

        return base64_encode('index.php');
    }

    /**
     * Get an HTML select list of the available languages.
     *
     * @return  string
     *
     * @deprecated 5.1.0 will be removed in 7.0
     *             Use the non-static method getLanguages
     *             Example: Factory::getApplication()->bootModule('mod_login', 'administrator')
     *                            ->getHelper('LoginHelper')
     *                            ->getLanguages(Factory::getApplication())
     */
    public static function getLanguageList()
    {
        return (new self())->getLanguages(Factory::getApplication());
    }

    /**
     * Get the redirect URI after login.
     *
     * @return  string
     *
     * @deprecated 5.1.0 will be removed in 7.0
     *             Use the non-static method getReturnUriString
     *             Example: Factory::getApplication()->bootModule('mod_login', 'administrator')
     *                            ->getHelper('LoginHelper')
     *                            ->getReturnUriString()
     */
    public static function getReturnUri()
    {
        return (new self())->getReturnUriString();
    }
}
