<?php

namespace MageSuite\UrlRewrite\Repository;

class RewriteRepository implements RewriteRepositoryInterface
{
    const ORIGINAL_URL = 0;
    const TARGET_URL = 1;
    const STATUS_CODE = 2;

    const DEFAULT_STATUS_CODE = 301;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \MageSuite\UrlRewrite\Model\RewriteFactory
     */
    protected $rewriteFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\UrlRewrite\Model\RewriteFactory $rewriteFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->rewriteFactory = $rewriteFactory;
    }

    /**
     * @inheritdoc
     */
    public function getRewrite($requestUri) {
        $rewrites = $this->scopeConfig->getValue('web/custom_rewrites/rewrites', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $rewrites = $this->cleanRewritesList($rewrites);
        $rewrites = explode(PHP_EOL, $rewrites);

        foreach($rewrites as $rewrite) {
            $rewrite = explode('|', $rewrite);

            if(!isset($rewrite[self::ORIGINAL_URL]) or !isset($rewrite[self::TARGET_URL])) {
                continue;
            }

            $originalUrl = ltrim($rewrite[self::ORIGINAL_URL], '/');
            $originalUrl = trim($originalUrl);

            if($originalUrl != $requestUri) {
                continue;
            }

            /** @var \MageSuite\UrlRewrite\Model\Rewrite $rewrite */
            $targetRewrite = $this->rewriteFactory->create();

            $targetRewrite->setTargetUrl($rewrite[self::TARGET_URL]);
            $targetRewrite->setStatusCode($rewrite[self::STATUS_CODE] ?? self::DEFAULT_STATUS_CODE);

            return $targetRewrite;
        }

        return null;
    }

    public function cleanRewritesList($rewritesList) {
        $rewritesList = str_replace("\r\n", "\n", $rewritesList);
        $rewritesList = str_replace("\r", "\n", $rewritesList);

        return $rewritesList;
    }
}