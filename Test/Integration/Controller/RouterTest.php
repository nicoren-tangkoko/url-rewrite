<?php

namespace MageSuite\UrlRewrite\Test\Integration\Controller;

class RouterTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoConfigFixture default_store web/custom_rewrites/rewrites redirect_path|/target_url|302
     * @magentoAppArea frontend
     */
    public function testItReturns404WhenNoRedirectIsConfigured()
    {
        $this->dispatch('/404_page');
        $response = $this->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @magentoConfigFixture default_store web/custom_rewrites/rewrites redirect_path|/target_url|302
     * @magentoAppArea frontend
     */
    public function testItReturnsRedirectWhenItMatchesConfiguredRewrite()
    {
        $this->dispatch('/redirect_path');
        $response = $this->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Location: /target_url', $response->getHeader('Location'));
    }
}
