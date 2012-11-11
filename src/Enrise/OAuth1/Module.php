<?php
/**
 * Enrise OAuth1Base  (http://enrise.com/)
 *
 * @link      https://github.com/Enrise/EnriseOAuth1Base for the canonical source repository
 * @copyright Copyright (c) 2012 Dolf Schimmel - Freeaqingme (dolfschimmel@gmail.com)
 * @copyright Copyright (c) 2012 Enrise (www.enrise.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace  Enrise\OAuth1;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ =>  __DIR__ . '/',
                ),
            ),
        );
    }
}
