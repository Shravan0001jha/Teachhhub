<?php

namespace App\Services;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ZoomService
{
    protected $client;
    protected $zoomClientId;
    protected $zoomClientSecret;
    protected $zoomRedirectUri;

    public function __construct()
    {
        $this->client = new Client();
        $this->zoomClientId = config('services.zoom.client_id');
        $this->zoomClientSecret = config('services.zoom.client_secret');
        $this->zoomRedirectUri = config('services.zoom.redirect');
    }

    // Redirect to Zoom's OAuth URL
    public function getZoomRedirectUrl()
    {
        // dd($this->zoomClientId, $this->zoomClientSecret, $this->zoomRedirectUri);
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->zoomClientId,
            'redirect_uri' => $this->zoomRedirectUri,
            // 'scope' => 'meeting:read meeting:write', // Define scopes based on your requirement
        ]);
        return 'https://zoom.us/oauth/authorize?' . $query;
    }

    // Handle the callback to get the access token
    public function getAccessToken($code)
    {
        // dd($code);
        $response = $this->client->post('https://zoom.us/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->zoomRedirectUri,
            ],
            'auth' => [$this->zoomClientId, $this->zoomClientSecret],
        ]);
        // dd(json_decode($response->getBody()->getContents(), true));
        return json_decode($response->getBody()->getContents(), true);
    }

    // Refresh the access token (if needed)
    public function refreshAccessToken($refreshToken)
    {
        $response = $this->client->post('https://zoom.us/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ],
            'auth' => [$this->zoomClientId, $this->zoomClientSecret],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Function to create a meeting using the access token
    public function createMeeting($accessToken, $data)
    {
        $response = $this->client->post('https://api.zoom.us/v2/users/me/meetings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}

