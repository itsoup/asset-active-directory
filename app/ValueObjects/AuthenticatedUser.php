<?php

namespace App\ValueObjects;

use Illuminate\Auth\GenericUser;

class AuthenticatedUser extends GenericUser implements \JsonSerializable
{
    public function getAuthPassword(): ?string
    {
        throw new \Exception('Invalid method');
    }

    public function getRememberToken()
    {
        throw new \Exception('Invalid method');
    }

    public function setRememberToken($value)
    {
        throw new \Exception('Invalid method');
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function jsonSerialize()
    {
        return $this->attributes;
    }

    private function toJson($options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \JsonException(json_last_error_msg());
        }

        return $json;
    }
}
