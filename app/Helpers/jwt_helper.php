<?php

use App\Models\ApiUserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @throws Exception
 */
function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
}

/**
 * @throws Exception
 */
function validateJWTFromRequest(string $encodedToken)
{
    $key = Services::getSecretKey();
    JWT::decode($encodedToken, new Key($key, 'HS256'));
}

function getSignedJWTForUser(string $username): string
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'username' => $username,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];
    return JWT::encode($payload, Services::getSecretKey(), 'HS256');
}