<?php

namespace ProEmergotech\Correlate\Psr7;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Monolog\Logger;
use ProEmergotech\Correlate\Monolog\CorrelateProcessor;
use ProEmergotech\Correlate\Correlate;
use Closure;

class Psr7CorrelateMiddleware
{
    /**
     * @var \Monolog\Logger|null
     */
    protected $log = null;

    /**
     * @var \Closure|null
     */
    protected $callback = null;

    /**
     * @param Logger $log
     * @param \Closure $callback
     */
    public function __construct(Logger $log = null, Closure $callback = null)
    {
        if ($log) {
            $this->log = $log;
        }

        $this->callback = $callback;
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function setCallback(Closure $callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @param Logger $log
     * @return $this
     */
    public function setLog(Logger $log)
    {
        $this->log = $log;
        return $this;
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if (!$request->hasHeader(Correlate::getHeaderName())) {
          $request = $request->withHeader(
            Correlate::getHeaderName(), (string) Correlate::id()
          );
        }

        $correlationIds = $request->getHeader(Correlate::getHeaderName());

        if (is_array($correlationIds) && isset($correlationIds[0])) {

            $correlationId = $correlationIds[0];

            $request = $request->withAttribute(
              Correlate::getParamName(), $correlationId
            );

            if ($this->log) {
                $this->log->pushProcessor(
                  new CorrelateProcessor(Correlate::getParamName(), $correlationId)
                );
            }

            if ($this->callback) {
                ($this->callback)($correlationId);
            }
        }

        $response = $next($request, $response);

        return $response->withHeader(Correlate::getHeaderName(), $correlationId);
    }
}
