<?php

namespace MageSuite\UrlRewrite\Test\Unit\Repository;

class RewriteRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfigStub;

    /**
     * @var \MageSuite\UrlRewrite\Repository\RewriteRepository
     */
    protected $rewriteRepository;

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->scopeConfigStub = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->rewriteRepository = $this->objectManager->create(\MageSuite\UrlRewrite\Repository\RewriteRepository::class,
            ['scopeConfig' => $this->scopeConfigStub]
        );
    }

    public function testItReturnsNullWhenNoRewriteWasFound() {
        $paths = [
            "/path|/target_path|302",
            "/second_path|http://example.com|301"
        ];

        $this->scopeConfigStub->method('getValue')->willReturn(implode(PHP_EOL, $paths));

        $this->assertNull($this->rewriteRepository->getRewrite('non_existing_rewrite'));
    }

    public function testItReturnsRewriteObjectWhenItMatchesRequestUri() {
        $paths = [
            "/path|/target_path|302",
            "/second_path|http://example.com|301"
        ];

        $this->scopeConfigStub->method('getValue')->willReturn(implode(PHP_EOL, $paths));

        $rewrite = $this->rewriteRepository->getRewrite('path');

        $this->assertInstanceOf(\MageSuite\UrlRewrite\Model\Rewrite::class, $rewrite);

        $this->assertEquals('/target_path', $rewrite->getTargetUrl());
        $this->assertEquals(302, $rewrite->getStatusCode());
    }
}
