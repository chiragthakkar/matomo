<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CookieDomainFix\tests\Unit;

use Piwik\Plugins\CookieDomainFix\CookieDomainFix;
use Piwik\Plugins\UsersManager\SystemSettings;
use Piwik\Tests\Framework\Fixture;

/**
 * @group CookieDomainFix
 * @group TestWWWReplaceTest
 * @group Plugins
 */
class CookieDomainFixTest extends \PHPUnit\Framework\TestCase
{

    public function test_getCodeGenerator_WithNoOptions()
    {
        $plugin = new CookieDomainFix();

        $codeImpl = [
            'options' => ''
        ];

        $parameters = [];

        $plugin->getCodeGenerator($codeImpl, $parameters);

        $this->assertSame('', $codeImpl['options']);
        $this->assertStringNotContainsString('*.www', $codeImpl['options']);
    }

    public function test_getCodeGenerator_singleDomainSite()
    {
        $plugin = new CookieDomainFix();

        $codeImpl = [
            'options' => '*.www.example.com'
        ];

        $parameters = [];

        $plugin->getCodeGenerator($codeImpl, $parameters);

        $this->assertSame('*.example.com', $codeImpl['options']);
        $this->assertStringNotContainsString('*.www', $codeImpl['options']);
    }

    public function test_getCodeGenerator_WithWildcard()
    {
        $plugin = new CookieDomainFix();

        $codeImpl = [
            'options' => '*.example.*'
        ];

        $parameters = [];

        $plugin->getCodeGenerator($codeImpl, $parameters);

        $this->assertSame('*.example.*', $codeImpl['options']);
        $this->assertStringNotContainsString('*.www', $codeImpl['options']);
    }

    public function test_getCodeGenerator_WithMultipleDomains()
    {
        $plugin = new CookieDomainFix();

        $codeImpl = [
            'options' => '*.www.example.com, *.www.example.org'
        ];

        $parameters = [];

        $plugin->getCodeGenerator($codeImpl, $parameters);

        $this->assertSame('*.example.com, *.example.org', $codeImpl['options']);
        $this->assertStringNotContainsString('*.www', $codeImpl['options']);
    }

    public function test_getCodeGenerator_WithSubdomain()
    {
        $plugin = new CookieDomainFix();

        $codeImpl = [
            'options' => '*.sub.example.com'
        ];

        $parameters = [];

        $plugin->getCodeGenerator($codeImpl, $parameters);

        $this->assertSame('*.sub.example.com', $codeImpl['options']);
        $this->assertStringNotContainsString('*.www', $codeImpl['options']);
    }

    public function test_getCodeGenerator_singleDomainWithoutWWW()
    {
        $plugin = new CookieDomainFix();

        $codeImpl = [
            'options' => '*.example.com'
        ];

        $parameters = [];

        $plugin->getCodeGenerator($codeImpl, $parameters);

        $this->assertSame('*.example.com', $codeImpl['options']);
        $this->assertStringNotContainsString('*.www', $codeImpl['options']);
    }

}
