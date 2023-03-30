<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CurrentTimezone;

class CurrentTimezone extends \Piwik\Plugin
{
    public function registerEvents()
    {
        return [
            'CronArchive.getArchivingAPIMethodForPlugin' => 'getArchivingAPIMethodForPlugin',
            'AssetManager.getJavaScriptFiles' => 'getJavaScriptFiles',
        ];
    }

    // support archiving just this plugin via core:archive
    public function getArchivingAPIMethodForPlugin(&$method, $plugin)
    {
        if ($plugin == 'CurrentTimezone') {
            $method = 'CurrentTimezone.getExampleArchivedMetric';
        }
    }


    /***
     * Loading a js to update time every minute
     * @param $files
     * @return void
     */
    public function getJavaScriptFiles(&$files)
    {
        $files[] = "plugins/CurrentTimezone/javascripts/updateTime.js";
    }
}
