<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lcobucci\JWT\Token;
use Tests\TestCase;

class JwtAuthenticationTest extends TestCase
{
    private Token $jwt;

    protected function setUp(): void
    {
        parent::setUp();

        Route::group(['middleware' => 'auth'], function () {
            Route::get('_test/jwt', function (Request $request) {
                return $request->user();
            });
        });

        $this->jwt = $this->createJwt();
    }

    /** @test */
    public function authenticated_requests_contains_valid_jwt(): void
    {
        $response = $this->get('_test/jwt', ['Authorization' => 'Bearer ' . $this->jwt])
            ->response
            ->getContent();

        $user = json_decode($response, true);

        self::assertEquals($this->jwt->getClaim('sub'), $user['id']);
        self::assertEquals($this->jwt->getClaim('name'), $user['name']);
        self::assertEquals($this->jwt->getClaim('email'), $user['email']);
        self::assertEquals($this->jwt->getClaim('account_type'), $user['account_type']);
        self::assertEquals($this->jwt->getClaim('customer_id'), $user['customer_id']);
        self::assertEquals($this->jwt->getClaim('vat_number'), $user['vat_number']);
        self::assertEquals($this->jwt->getClaim('scopes'), $user['scopes']);
    }
}
