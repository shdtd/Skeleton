<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Controllers
 * @package  CommandController
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Controllers;

use Libraries\Config;
use Libraries\Registry;
use Libraries\Requests\Request;
use Libraries\View;

abstract class CommandController
{

    /**
     * Description
     *
     * @var Registry $reg
     */
    protected Registry $reg;

    /**
     * Description
     *
     * @var string $action
     */
    protected string $action;

    /**
     * Request parameters as /<VAR>/ or /?one=foo&two=bar and so on.
     *
     * @var Config $params
     */
    protected Config $params;

    /**
     * Constructor is final
     */
    final public function __construct()
    {
        $this->reg    = Registry::instance();
        $this->params = $this->reg->getRequest()->getParameters();
        $this->init();
    }

    /**
     * Description
     *
     * @return void
     */
    abstract protected function init(): void;

    /**
     * Description
     *
     * @param Request $request Description.
     *
     * @return void
     */
    public function execute(Request $request): void
    {
        $controller   = $this->reg->getAppController();
        $this->action = $controller->getAction();
        $this->action = $request->getType() . ucfirst($this->action);

        if (method_exists($this, $this->action) === true) {
            $refMethod = new \ReflectionMethod(
                $this::class,
                $this->action,
            );
            $refMethod->invoke($this);
        } else {
            $this->notFound($request);
        }
    }

    /**
     * Description
     *
     * @param Request $request Description.
     *
     * @return void
     */
    public function notFound(Request $request): void
    {
        if ($request->getType() === 'api') {
            $data = [
                'success' => false,
                'message' => 'Method not found',
            ];
            $this->apiResponce($data);
            return;
        }

        $this->webResponce([], '404.tpl');
    }

    /**
     * Description
     *
     * @param array<string,mixed> $data Description.
     *
     * @return void
     */
    public function apiResponce(array $data): void
    {
        http_response_code(200);
        header('Content-type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        $accessControl  = 'Access-Control-Allow-Methods: ';
        $accessControl .= 'GET, HEAD, POST, PUT, PATCH, DELETE, OPTIONS';
        header($accessControl);
        $accessControl  = 'Access-Control-Allow-Headers: ';
        $accessControl .= 'Content-Type, Access-Control-Allow-Headers, ';
        $accessControl .= 'Authorization, X-Requested-With';
        header($accessControl);
        header(
            'Expires: ' .
            date('D, d M Y H:i:s e', strtotime('-1 day'))
        );

        echo json_encode($data);
    }

    /**
     * Description
     *
     * @param array<string,mixed> $data     Description.
     * @param string              $template Description.
     *
     * @return void
     */
    public function webResponce(array $data, string $template): void
    {
        $request = $this->reg->getRequest();
        $view    = new View();
        $view->assign('feedback', '');

        if ($this->reg->getConfig()->get('debug') === 'on') {
            $view->setCaching(View::CACHING_OFF);
            $view->assign('feedback', $request->getFeedbackString());
        }

        foreach ($data as $key => $value) {
            $view->assign($key, $value);
        }

        $view->display($template);
    }
}
