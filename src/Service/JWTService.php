<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    //on génère le token

    /**
     * Génération du JWT
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string 
     */

    public function generate(array $header, array $payload, string $secret, int $validity = 10800):string
    {
        if ($validity>0){
        $now = new DateTimeImmutable();
        $exp = $now->getTimestamp() + $validity;
        
        $payload['iat'] = $now->getTimestamp();
        $payload['exp'] = $exp; 
        }

        

        //on encode en base64
        $base64Header =  base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //on nettoie les valeurs encodées (retrait des +, / et =)
        $base64Header =str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // On génère la signature, il nous faut un secret
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        //on crée le token
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }

    //On verifie que le token est valide (correctement formé)

    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
            
            ) === 1;

    }

    // On récupère le payload
    public function getPayload(string $token): array
    {
        //On démonte le token
        $array = explode('.', $token);

        //On décode le Payload
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    // On récupère le Header
    public function getHeader(string $token): array
    {
        //On démonte le token
        $array = explode('.', $token);

        //On décode le Header
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    //on verifie si le token a expiré
    public function isExpired(string $token): bool{
        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    // on verifie la signature du token
    public function check(string $token, string $secret)
    {
        //on récupère le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        //on régénère un token
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}