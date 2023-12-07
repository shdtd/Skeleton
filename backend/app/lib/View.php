<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Libraries
 * @package  View
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries;

use Smarty;

class View extends Smarty
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $reg     = Registry::instance();
        $config  = $reg->getConfig();
        $appPath = $reg->getAppPath();
        $appName = $config->get('application');

        parent::__construct();

        $this->setTemplateDir($appPath . '/backend/templates/');
        $this->setCompileDir($appPath . '/backend/view/templates_c/');
        $this->setConfigDir($appPath . '/backend/view/configs/');
        $this->setCacheDir($appPath . '/backend/view/cache/');

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', $appName);
    }
}
