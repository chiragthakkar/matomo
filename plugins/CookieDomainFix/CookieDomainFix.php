<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CookieDomainFix;

use Piwik\Plugins\SitesManager\API as APISitesManager;

class CookieDomainFix extends \Piwik\Plugin
{

    /**
     * we have this event triggered when admin generates the script code
     * @return string[]
     */
    public function registerEvents()
    {
        return [
            'Tracker.getJavascriptCode' => 'getCodeGenerator'
        ];
    }


    /**
     * @param $codeImpl
     * @param $parameters
     * @return void
     * We can replace all *.www to *. in these options as Tracker.getJavascriptCode event is being triggered after getJavascriptTagOptions function
     */
    public function getCodeGenerator(&$codeImpl, $parameters)
    {
        $codeImpl['options'] = str_replace('*.www.', '*.', $codeImpl['options']);
    }


}
