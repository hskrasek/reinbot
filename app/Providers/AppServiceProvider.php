<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
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
        if ($this->app->environment() === 'local') {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }

        Horizon::routeSlackNotificationsTo(config('slack.development_webhooks'));

        Horizon::auth(function ($request) {
            return $request->key = config('services.horizon.key');
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
                            $data = \GuzzleHttp\json_decode((string) $response->getBody(), true);

                            if (!$data['ok']) {
                                throw new RequestException($data['error'], $request, $response);
                            }

                            return $response;
                        }
                    );
                };
            });
            $client = new Client(['handler' => $stack]);

            return $client;
        });
    }
}
