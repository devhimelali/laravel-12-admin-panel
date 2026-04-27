<?php

namespace App\Providers;

use App\Utility\LoginHistoryUtility;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use DebugPHP\Debug;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request)
            {
                LoginHistoryUtility::logLoginHistory($request);
                // $user = $request->user();

                // if ($user && $user->hasRole('admin')) {
                //     return redirect()->route('admin.dashboard');
                // }

                return redirect()->route('dashboard');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('debugphp.session_token') && config('debugphp.host')) {
            Debug::init(config('debugphp.session_token'), [
                'host' => config('debugphp.host'),
            ]);
        }
    }
}
