<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\choose_handler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(\App\Services\Destiny\Client::class)
            ->needs(Client::class)
            ->give(function () {
                return new Client([
                    'base_uri' => 'https://www.bungie.net/Platform/Destiny2/',
                    'headers'  => [
                        'X-API-Key' => config('services.destiny.key'),
                    ],
                ]);
            });

        $this->app->singleton(Client::class, function () {
            $stack = new HandlerStack(choose_handler());
            $stack->push(function (callable $handler) {
                return function (
                    RequestInterface $request,
                    array $options
                ) use ($handler) {
                    $promise = $handler($request, $options);

                    return $promise->then(
                        function (ResponseInterface $response) use ($request) {
                            if (str_contains(head($response->getHeader('Content-Type')), 'json')) {
                                $data = \GuzzleHttp\json_decode((string)$response->getBody(), true);

                                if (!$data['ok']) {
                                    throw new RequestException($data['error'], $request, $response);
                                }
                            }

                            return $response;
                        }
                    );
                };
            });

            return new Client(['handler' => $stack]);
        });

        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }
}
