<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Model;

/**
 * @package Auth\Model
 */
class Character extends Base
{
    public static $_table = 'characters';

    static public function handleAuthentication($code)
    {
        $guzzle = new \GuzzleHttp\Client(['base_uri' => 'https://login.eveonline.com']);
        $response = $guzzle->post('/oauth/token',
            ['form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code
            ],
                'auth' => [EVE_SSO_ID, EVE_SSO_KEY]
            ]);
        $responseData =  json_decode($response->getBody());
        var_dump($responseData);

        $accessToken = $responseData->access_token;
        $refreshToken = $responseData->refresh_token;

        $response = $guzzle->get('/oauth/verify', [
            'headers' => ['Authorization' => 'Bearer ' . $accessToken]
        ]);
        $responseData =  json_decode($response->getBody());
        var_dump($responseData);

        $characterId = $responseData->CharacterID;
        $characterName = $responseData->CharacterName;
        $scopes = $responseData->Scopes;
        $ownerHash = $responseData->CharacterOwnerHash;

        $character = Character::factory()->where_equal('characterId', $characterId)->find_one();
        if($character === false) {
            $character = Character::factory()->create();
            $character->generateID();
        }

        $character
            ->setCharacterId($characterId)
            ->setCharacterName($characterName)
            ->setScopes($scopes)
            ->setOwnerHash($ownerHash)
            ->setAccessToken($accessToken)
            ->setRefreshToken($refreshToken)
            ->save();

        return $character;
    }
}