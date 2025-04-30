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
    public function refreshAccessTokenWithAccountId($accountId)
    {
        try {
            // Make a POST request to Zoom's OAuth token endpoint
            // $response = $this->client->post('https://zoom.us/oauth/token', [
            //     'headers' => [
            //         'Authorization' => 'Basic ' . base64_encode($this->zoomClientId . ':' . $this->zoomClientSecret),
            //         'Content-Type' => 'application/x-www-form-urlencoded',
            //     ],
            //     'form_params' => [
            //         'grant_type' => 'account_credentials',
            //         'account_id' => $accountId, // Include the account_id here
            //     ],
            // ]);
            $response = Http::asForm()
                ->withBasicAuth($this->zoomClientId , $this->zoomClientSecret)
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => 'UH-55KnrQt6Wb1Ke9e3bYQ',
                ]);
    
            // Decode and return the response
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error refreshing Zoom access token with account ID: ' . $e->getMessage());
            throw $e; // Re-throw the exception to handle it in the controller
        }
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

