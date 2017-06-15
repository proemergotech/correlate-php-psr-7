<?php

namespace ProEmergotech\Correlate\Psr7;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use ProEmergotech\Correlate\Monolog\CorrelateProcessor;
use ProEmergotech\Correlate\Correlate;

class Psr7CorrelateMiddleware
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $log = null)
    {
        $this->log = $log;
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        if (!$request->hasHeader(Correlate::getHeaderName())) {
          $request = $request->withHeader(
            Correlate::getHeaderName(), (string) Correlate::id()
          );
        }

        $request = $request->withAttribute(
          Correlate::getParamName(), $request->getHeader(Correlate::getHeaderName())
        );

        $cid = $request->getAttribute(Correlate::getParamName());

        $this->log->pushProcessor(
          new CorrelateProcessor(Correlate::getParamName(), $cid)
        );

        $response = $next($request, $response);

        return $response->withHeader(Correlate::getHeaderName(), $cid);
    }
}
