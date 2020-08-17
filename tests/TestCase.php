<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Support\Carbon;
use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function createJwt(array $userData = []): Token
    {
        $faker = Factory::create();

        $data = array_merge(
            [
                'id' => $faker->randomNumber(),
                'account_type' => $faker->randomElement(['user', 'system-operator']),
                'customer_id' => $faker->randomNumber(),
                'vat_number' => $faker->countryCode.$faker->randomNumber(9),
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'scopes' => [
                    'assets-active-directory:locations:view',
                    'assets-active-directory:locations:manage',
                    'assets-active-directory:assets:view',
                    'assets-active-directory:assets:manage',
                    'assets-active-directory:properties:view',
                    'assets-active-directory:properties:manage',
                ],
            ],
            $userData
        );

        return (new Builder())
            ->expiresAt(Carbon::now()->addYear()->getTimestamp())
            ->relatedTo($data['id'])
            ->withClaim('scopes', $data['scopes'])
            ->withClaim('account_type', $data['account_type'])
            ->withClaim('customer_id', $data['customer_id'])
            ->withClaim('vat_number', $data['vat_number'])
            ->withClaim('name', $data['name'])
            ->withClaim('email', $data['email'])
            ->getToken(new Sha256());
    }
}
