<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\CurrentTimezone\Widgets;

use Piwik\Common;
use Piwik\Site;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;
use Piwik\Date;
use Piwik\Plugins\SitesManager\API as APISitesManager;


/**
 * This class allows you to add your own widget to the Piwik platform. In case you want to remove widgets from another
 * plugin please have a look at the "configureWidgetsList()" method.
 * To configure a widget simply call the corresponding methods as described in the API-Reference:
 * http://developer.piwik.org/api-reference/Piwik/Plugin\Widget
 */
class GetCurrentlocaltimeinwebsitetimezone extends Widget
{
    public static function configure(WidgetConfig $config)
    {
        /**
         * Set the category the widget belongs to. You can reuse any existing widget category or define
         * your own category.
         */
        $config->setCategoryId('General_Visitors');

        /**
         * Set the subcategory the widget belongs to. If a subcategory is set, the widget will be shown in the UI.
         */
        $config->setSubcategoryId('General_Overview');

        /**
         * Set the name of the widget belongs to.
         */
        $config->setName('CurrentTimezone_Currentlocaltimeinwebsitetimezone');

        /**
         * Set the order of the widget. The lower the number, the earlier the widget will be listed within a category.
         */
        $config->setOrder(99);

        /**
         * Optionally set URL parameters that will be used when this widget is requested.
         * $config->setParameters(array('myparam' => 'myvalue'));
         */

        /**
         * Define whether a widget is enabled or not. For instance some widgets might not be available to every user or
         * might depend on a setting (such as Ecommerce) of a site. In such a case you can perform any checks and then
         * set `true` or `false`. If your widget is only available to users having super user access you can do the
         * following:
         *
         * $config->setIsEnabled(\Piwik\Piwik::hasUserSuperUserAccess());
         * or
         * if (!\Piwik\Piwik::hasUserSuperUserAccess())
         *     $config->disable();
         */
    }

    public function getWidgetData(): array
    {
        // retrieve siteId from URL parameter to fetch timezone for site only and not global
        $idSite = Common::getRequestVar('idSite', null, 'integer');
        $timezone = Site::getTimezoneFor($idSite);

        // check if js set the timezone for user's browser otherwise we can use server timezone
        $serverTimezone = date_default_timezone_get();
        if(isset($_COOKIE['user_timezone'])) {
            $serverTimezone = $_COOKIE['user_timezone'];
        }

        $currentDate = date('Y/m/d H:i', Date::adjustForTimezone(Date::now()->getTimestamp(), $timezone));
        $serverDate = date('Y/m/d H:i', Date::adjustForTimezone(Date::now()->getTimestamp(), $serverTimezone));

        return array(
            'timezone' => $timezone,
            'currentDate' => $currentDate,
            'serverTimezone' => $serverTimezone,
            'serverDate' => $serverDate,
        );
    }

    /**
     * This method renders the widget. It's on you how to generate the content of the widget.
     * As long as you return a string everything is fine. You can use for instance a "Piwik\View" to render a
     * twig template. In such a case don't forget to create a twig template (eg. myViewTemplate.twig) in the
     * "templates" directory of your plugin.
     *
     * @return string
     */
    public function render()
    {
        return $this->renderTemplate('viewTimingsCurrentTimeZone', $this->getWidgetData());
    }

}
