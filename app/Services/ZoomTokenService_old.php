<?php

namespace App\Services;

use App\Models\ZoomToken;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Exception;

class ZoomTokenService
{
    private $clientId;
    private $clientSecret;
    private $accountId;
    private $client;

    public function __construct()
    {
        $this->clientId = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
        $this->accountId = env('ZOOM_ACCOUNT_ID');
        $this->client = new Client(['base_uri' => 'https://zoom.us/oauth/']);
    }

    public function getAccessToken()
    {
        // Retrieve the latest token from the database
        $token = ZoomToken::latest()->first();

        // Check if the token is available and valid
        if ($token && $token->access_token_expires_at->gt(Carbon::now())) {
            return $token->access_token;
        }

        // If no valid token exists, generate a new access token
        return $this->generateAccessToken();
    }

    public function generateAccessToken()
    {
        try {
            $response = $this->client->post('token', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Store the new token
            return $this->storeToken($data);
        } catch (Exception $e) {
            throw new Exception("Failed to generate access token: " . $e->getMessage());
        }
    }

    public function storeToken($data)
    {
        dd($data);
        // Save token data
        $token = ZoomToken::create([
            'access_token' => $data['access_token'],
            'access_token_expires_at' => Carbon::now()->addSeconds($data['expires_in']),
        ]);

        return $token->access_token;
    }
}
