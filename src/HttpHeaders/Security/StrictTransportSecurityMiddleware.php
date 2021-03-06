<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     07/03/2018
// Time:     01:58
// Project:  Psr15Middlewares
//
declare(strict_types = 1);
namespace CodeInc\Psr15Middlewares\HttpHeaders\Security;
use CodeInc\Psr15Middlewares\HttpHeaders\AbstractSecureHttpHeaderMiddleware;
use CodeInc\Psr15Middlewares\Tests\HttpHeaders\Security\StrictTransportSecurityMiddlewareTest;


/**
 * Class StrictTransportSecurityMiddleware
 *
 * @link https://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security
 * @link https://developer.mozilla.org/docs/Web/HTTP/Headers/Strict-Transport-Security
 * @see StrictTransportSecurityMiddlewareTest
 * @package CodeInc\Psr15Middlewares\HttpHeaders\Security
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class StrictTransportSecurityMiddleware extends AbstractSecureHttpHeaderMiddleware
{
    /**
     * @var int|null
     */
	private $maxAge;

    /**
     * @var bool
     */
	private $includeSubDomains = null;

    /**
     * @var bool
     */
	private $preload = null;


    /**
     * StrictTransportSecurityMiddleware constructor.
     *
     * @param int $maxAge
     * @param bool|null $includeSubDomains
     * @param bool|null $preload
     */
	public function __construct(?int $maxAge = null, ?bool $includeSubDomains = null, ?bool $preload = null)
	{
	    $this->maxAge = $maxAge;
	    $this->includeSubDomains = $includeSubDomains ?? false;
	    $this->preload = $preload ?? false;

        parent::__construct('Strict-Transport-Security');
	}


    /**
     * @param int|null $maxAge
     */
    public function setMaxAge(?int $maxAge):void
    {
        $this->maxAge = $maxAge;
    }


    /**
     * @return int|null
     */
    public function getMaxAge():?int
    {
        return $this->maxAge;
    }


    /**
     * Includes the sub domaines.
     */
	public function includeSubDomains():void
    {
        $this->includeSubDomains = true;
    }


    /**
     * Enabled the preload.
     */
    public function enablePreload():void
    {
        $this->preload = true;
    }


    /**
     * @inheritdoc
     * @return string|null
     */
	public function getHeaderValue():?string
    {
        if ($this->maxAge !== null) {
            $value = 'max-age=' . $this->maxAge;
            if ($this->includeSubDomains) {
                $value .= '; includeSubDomains';
            }
            if ($this->preload) {
                $value .= '; preload';
            }
            return $value;
        }
        return null;
    }
}