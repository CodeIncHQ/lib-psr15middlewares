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
// Date:     27/04/2018
// Time:     11:01
// Project:  Psr15Middlewares
//
declare(strict_types=1);
namespace CodeInc\Psr15Middlewares\Tests\HttpHeaders\Security;
use CodeInc\Psr15Middlewares\HttpHeaders\Security\ReferrerPolicyMiddleware;
use CodeInc\Psr15Middlewares\Tests\Assets\FakeRequestHandler;
use CodeInc\Psr15Middlewares\Tests\Assets\FakeServerRequest;
use CodeInc\Psr15Middlewares\Tests\HttpHeaders\AbstractHttpHeaderMiddlewareTestCase;


/**
 * Class ReferrerPolicyMiddlewareTest
 *
 * @uses ReferrerPolicyMiddleware
 * @package CodeInc\Psr15Middlewares\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class ReferrerPolicyMiddlewareTest extends AbstractHttpHeaderMiddlewareTestCase
{
    private const MIDDLEWARE_VALUES = ReferrerPolicyMiddleware::ALL_VALUES;
    private const MIDDLEWARE_METHODS = [
        'noReferer' => ReferrerPolicyMiddleware::VALUE_NO_REFERRER,
        'noRefererWhenDowngrade' => ReferrerPolicyMiddleware::VALUE_NO_REFERRER_WHEN_DOWNGRADE,
        'origin' => ReferrerPolicyMiddleware::VALUE_ORIGIN,
        'originWhenCrossOrigin' => ReferrerPolicyMiddleware::VALUE_ORIGIN_WHEN_CROSS_ORIGIN,
        'sameOrigin' => ReferrerPolicyMiddleware::VALUE_SAME_ORIGIN,
        'strictOrigin' => ReferrerPolicyMiddleware::VALUE_STRICT_ORIGIN,
        'strictOriginWhenCrossOrigin' => ReferrerPolicyMiddleware::VALUE_STRICT_ORIGIN_WHEN_CROSS_ORIGIN,
        'unsafeUrl' => ReferrerPolicyMiddleware::VALUE_UNSAFE_URL,
    ];


    public function testMiddlewareConsructor():void
    {
        foreach (self::MIDDLEWARE_VALUES as $value) {
            $middleware = new ReferrerPolicyMiddleware($value);
            self::assertResponseHasHeaderValue(
                $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler()),
                'Referrer-Policy',
                [$value]
            );
        }
    }


    public function testMiddlewareMethods():void
    {
        foreach (self::MIDDLEWARE_METHODS as $method => $value) {
            /** @var ReferrerPolicyMiddleware $middleware */
            $middleware = call_user_func([ReferrerPolicyMiddleware::class, $method]);
            self::assertResponseHasHeaderValue(
                $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler()),
                'Referrer-Policy',
                [$value]
            );
        }
    }
}