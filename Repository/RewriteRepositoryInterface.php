<?php

namespace MageSuite\UrlRewrite\Repository;

interface RewriteRepositoryInterface
{
    /**
     * Returns Rewrite object matching requestUri
     *
     * @param $requestUri
     * @return \MageSuite\UrlRewrite\Model\Rewrite|null
     */
    public function getRewrite($requestUri);
}