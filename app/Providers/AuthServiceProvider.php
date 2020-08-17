<?php

namespace App\Providers;

use App\ValueObjects\AuthenticatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Parser;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app['auth']->viaRequest('api', static function (Request $request) {
            if ($request->bearerToken() === null
                || (! $jwt = (new Parser())->parse($request->bearerToken()))
                || $jwt->isExpired()
            ) {
                return null;
            }

            return new AuthenticatedUser([
                'id' => $jwt->getClaim('sub'),
                'name' => $jwt->getClaim('name'),
                'email' => $jwt->getClaim('email'),
                'vat_number' => $jwt->getClaim('vat_number'),
                'customer_id' => $jwt->getClaim('customer_id'),
                'account_type' => $jwt->getClaim('account_type'),
                'scopes' => $jwt->getClaim('scopes'),
            ]);
        });
    }
}
