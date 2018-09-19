<?php

namespace MageSuite\UrlRewrite\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \MageSuite\UrlRewrite\Repository\RewriteRepositoryInterface
     */
    protected $rewriteRepository;

    public function __construct(
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\ActionFactory $actionFactory,
        \MageSuite\UrlRewrite\Repository\RewriteRepositoryInterface $rewriteRepository
    )
    {
        $this->response = $response;
        $this->actionFactory = $actionFactory;
        $this->rewriteRepository = $rewriteRepository;
    }

    /**
     * Match application action by request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $server = $request->getServer();

        $requestUri = $server->get('ORIGINAL_URI') ? $server->get('ORIGINAL_URI') : $request->getRequestUri();
        $requestUri = ltrim($requestUri, '/');
        $requestUri = trim($requestUri);
        $requestUri = parse_url($requestUri, PHP_URL_PATH);

        $rewrite = $this->rewriteRepository->getRewrite($requestUri);

        if($rewrite == null) {
            return null;
        }

        $this->response->setRedirect($rewrite->getTargetUrl());
        $this->response->setStatusCode($rewrite->getStatusCode());

        $request->setDispatched(true);

        return $this->actionFactory->create(\Magento\Framework\App\Action\Redirect::class);
    }
}