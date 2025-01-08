<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    { 
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(2));
        Passport::refreshTokensExpireIn(now()->addDays(2));
        Passport::personalAccessTokensExpireIn(now()->addMonths(1));
        Passport::tokensCan([
            'users.index'		                =>	'index users',
            'users.store'		                =>	'store users',
            'users.update'		                =>	'update users',
            'users.show'		                =>	'show users',
            'users.delete'		                =>	'delete users',
            'profiles.index'		            =>	'index profiles',
            'profiles.store'		            =>	'store profiles',
            'profiles.update'		            =>	'update profiless',
            'profiles.show'		                =>	'show  profiles',
            'profiles.delete'		            =>	'delete profiless',
            
        ]); 
        Passport::setDefaultScope([ 
            'users.index',
            'users.show',
            'users.update',
            'users.store',
            'users.delete',


        ]);      
      
        //
    }
}
