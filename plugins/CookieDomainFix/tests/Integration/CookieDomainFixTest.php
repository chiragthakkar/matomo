<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CookieDomainFix\tests\Integration;

use Piwik\Plugins\CookieDomainFix\CookieDomainFix;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;
use Piwik\Plugins\SitesManager\API as APISitesManager;
use Piwik\Tracker\TrackerCodeGenerator;

/**
 * @group CookieDomainFix
 * @group CookieDomainFixTest
 * @group Plugins
 */
class CookieDomainFixTest extends IntegrationTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        // Enable the CookieDomainFix plugin
        \Piwik\Plugin\Manager::getInstance()->activatePlugin('CookieDomainFix');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // Disable the CookieDomainFix plugin
        \Piwik\Plugin\Manager::getInstance()->deactivatePlugin('CookieDomainFix');
    }

    public function test_getCodeGenerator_singleDomainSite()
    {
        // Create a site
        $site = APISitesManager::getInstance()->addSite('Test Site', array('https://example.com'));

        // Generate the JavaScript tracking code for this site
        $trackerCodeGenerator = new TrackerCodeGenerator();
        $javascriptCode = $trackerCodeGenerator->generate($site, 'https://www.example.com/', true);

        // Check that the cookie domain was fixed in the JavaScript code
        $this->assertStringNotContainsString('*.www.', $javascriptCode);
    }

    public function test_getCodeGenerator_multiDomainSite()
    {
        // Create a fake site
        $site = APISitesManager::getInstance()->addSite('Test Site', array('https://www.example.com', 'https://example.com'));

        // Generate the JavaScript tracking code for the site
        $trackerCodeGenerator = new TrackerCodeGenerator();
        $javascriptCode = $trackerCodeGenerator->generate($site, 'https://example.com/', true);

        // Check that the cookie domain was fixed in the JavaScript code
        $this->assertStringNotContainsString('*.www.', $javascriptCode);
    }

    public function test_getCodeGenerator_WithWildcard()
    {
        // Create a fake site
        $site = APISitesManager::getInstance()->addSite('Test Site', array('*.example.*', 'https://www.example.com'));

        // Generate the JavaScript tracking code for the site
        $trackerCodeGenerator = new TrackerCodeGenerator();
        $javascriptCode = $trackerCodeGenerator->generate($site, 'https://www.example.com/', true);

        // Check that the cookie domain was fixed in the JavaScript code
        $this->assertStringNotContainsString('*.www.', $javascriptCode);
    }

    public function test_getCodeGenerator_WithSubdomain()
    {
        // Create a fake site
        $site = APISitesManager::getInstance()->addSite('Test Site', array('sub.example.com', 'https://www.example.com'));

        // Generate the JavaScript tracking code for the site
        $trackerCodeGenerator = new TrackerCodeGenerator();
        $javascriptCode = $trackerCodeGenerator->generate($site, 'https://www.example.com/', true);

        // Check that the cookie domain was fixed in the JavaScript code
        $this->assertStringNotContainsString('*.www.', $javascriptCode);
    }

    public function test_getCodeGenerator_WithAllOptions()
    {
        // Create a fake site
        $site = APISitesManager::getInstance()->addSite('Test Site', array('https://www.example.com'));

        // Generate the JavaScript tracking code for the site
        $trackerCodeGenerator = new TrackerCodeGenerator();
        $javascriptCode = $trackerCodeGenerator->generate(
            $site,
            'https://www.example.com/',
            true,
            true,
            true,
            null,
            null,
            null,
            null,
            true,
            true,
            true,
            true,
            true,
            []
        );

        // Check that the cookie domain was fixed in the JavaScript code
        $this->assertStringNotContainsString('*.www.', $javascriptCode);
    }

}
