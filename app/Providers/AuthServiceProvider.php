<?php

namespace App\Providers;

use App\Auth\AdminSessionGuard;
use App\Auth\ClientSessionGuard;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('admin_session', function ($app, $name, array $config) {
            return $this->configureSessionGuard(new AdminSessionGuard(
                $name,
                Auth::createUserProvider($config['provider']),
                $app['session.store'],
                rehashOnLogin: $app['config']->get('hashing.rehash_on_login', true),
                timeboxDuration: $app['config']->get('auth.timebox_duration', 200000),
            ), $app, $config);
        });

        Auth::extend('client_session', function ($app, $name, array $config) {
            return $this->configureSessionGuard(new ClientSessionGuard(
                $name,
                Auth::createUserProvider($config['provider']),
                $app['session.store'],
                rehashOnLogin: $app['config']->get('hashing.rehash_on_login', true),
                timeboxDuration: $app['config']->get('auth.timebox_duration', 200000),
            ), $app, $config);
        });
    }

    private function configureSessionGuard(SessionGuard $guard, $app, array $config): SessionGuard
    {
        if (method_exists($guard, 'setCookieJar')) {
            $guard->setCookieJar($app['cookie']);
        }

        if (method_exists($guard, 'setDispatcher')) {
            $guard->setDispatcher($app['events']);
        }

        if (method_exists($guard, 'setRequest')) {
            $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
        }

        if (isset($config['remember'])) {
            $guard->setRememberDuration($config['remember']);
        }

        return $guard;
    }
}
