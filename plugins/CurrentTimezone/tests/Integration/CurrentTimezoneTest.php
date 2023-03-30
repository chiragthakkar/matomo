<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CurrentTimezone\tests\Integration;


use Piwik\Plugins\CurrentTimezone\Widgets\GetCurrentlocaltimeinwebsitetimezone;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

use Piwik\Plugins\SitesManager\API as APISitesManager;


/**
 * @group CurrentTimezone
 * @group CurrentTimezoneTest
 * @group Plugins
 */
class CurrentTimezoneTest extends IntegrationTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        // clean up your test here if needed

        parent::tearDown();
    }

    public function test_getCurrentTimezone_sameTimezone()
    {
        // Set the site and change timezone to test
        $site = APISitesManager::getInstance()->addSite(
            "Test Site",
            array('https://example.com'),
            null,
            null,
            null,
            null,
            null,
            null,
            'Pacific/Auckland'
        );
        $_GET['idSite'] = 1;
        //APISitesManager::getInstance()->setDefaultTimezone('Pacific/Auckland');
        date_default_timezone_set('Pacific/Auckland');

        $widget = new GetCurrentlocaltimeinwebsitetimezone();

        $output = $widget->render();

        $this->assertStringContainsString('js-current-site-time-widget', $output);
        $this->assertStringNotContainsString('js-current-server-time-widget', $output);

    }

    public function test_getCurrentTimezone_differentTimezone()
    {
        // Set the site and change timezone to test
        $site = APISitesManager::getInstance()->addSite(
            "Test Site",
            array('https://example.com'),
            null,
            null,
            null,
            null,
            null,
            null,
            'Pacific/Auckland'
        );
        $_GET['idSite'] = 1;
        //APISitesManager::getInstance()->setDefaultTimezone('Pacific/Auckland');
        date_default_timezone_set('Pacific/fiji');

        $widget = new GetCurrentlocaltimeinwebsitetimezone();
        $output = $widget->render();

        $this->assertStringContainsString('js-current-site-time-widget', $output);
        $this->assertStringContainsString('js-current-server-time-widget', $output);

    }

}
