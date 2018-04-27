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
// Time:     12:43
// Project:  Psr15Middlewares
//
declare(strict_types=1);
namespace CodeInc\Psr15Middlewares\Tests\HttpHeaders;
use CodeInc\Psr15Middlewares\HttpHeaders\AddHttpHeadersMiddleware;
use CodeInc\Psr15Middlewares\Tests\Assets\FakeRequestHandler;
use CodeInc\Psr15Middlewares\Tests\Assets\FakeServerRequest;


/**
 * Class AddHttpHeadersMiddlewareTest
 *
 * @uses AddHttpHeadersMiddleware
 * @package CodeInc\Psr15Middlewares\Tests\HttpHeaders
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class AddHttpHeadersMiddlewareTest extends AbstractHttpHeaderMiddlewareTestCase
{
    public function testDisabled():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        self::assertResponseHeadersCount(
            $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler()),
            1
        );
    }


    public function testSingleHeaderSingleValue():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        $middleware->addHeader('X-Test', 'foo');
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertResponseHeadersCount($response, 2);
        self::assertResponseHasHeaderValue($response, 'X-Test', ['foo']);
    }

    public function testSingleHeaderValueCasting():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        $middleware->addHeader('X-Test', 1);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertResponseHeadersCount($response, 2);
        self::assertResponseHasHeaderValue($response, 'X-Test', ['1']);
    }


    public function testSingleHeaderMultipleValue():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        $middleware->addHeader('X-Test', ['foo', 'bar']);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertResponseHeadersCount($response, 2);
        self::assertResponseHasHeaderValue($response, 'X-Test', ['foo', 'bar']);
    }


    public function testMultipleHeadersSingleValue():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        $middleware->addHeader('X-Test-1', 'foo1');
        $middleware->addHeader('X-Test-2', 'foo2');
        $middleware->addHeader('X-Test-3', 'foo3');
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertResponseHeadersCount($response, 4);
        self::assertResponseHasHeaderValue($response, 'X-Test-1', ['foo1']);
        self::assertResponseHasHeaderValue($response, 'X-Test-2', ['foo2']);
        self::assertResponseHasHeaderValue($response, 'X-Test-3', ['foo3']);
    }


    public function testMultipleHeadersMultipleValues():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        $middleware->addHeader('X-Test-1', ['foo1', 'bar']);
        $middleware->addHeader('X-Test-2', ['foo2', 'bar']);
        $middleware->addHeader('X-Test-3', ['foo3', 'bar']);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertResponseHeadersCount($response, 4);
        self::assertResponseHasHeaderValue($response, 'X-Test-1', ['foo1', 'bar']);
        self::assertResponseHasHeaderValue($response, 'X-Test-2', ['foo2', 'bar']);
        self::assertResponseHasHeaderValue($response, 'X-Test-3', ['foo3', 'bar']);
    }


    public function testSingleHeaderValueIterable():void
    {
        $middleware = new AddHttpHeadersMiddleware();
        $middleware->addHeader('X-Test', new \ArrayIterator(['foo', 'bar']));
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertResponseHeadersCount($response, 2);
        self::assertResponseHasHeaderValue($response, 'X-Test', ['foo', 'bar']);
    }
}