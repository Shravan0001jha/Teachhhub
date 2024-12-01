<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ZoomService;
use Illuminate\Support\Facades\Session;
use App\Models\ZoomToken;

class ZoomController extends Controller
{
    protected $zoomService;
    protected $zoomToken;
    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
        $this->zoomToken = ZoomToken::latest()->first();
    }

    // Redirect user to Zoom for authorization
    public function redirectToZoom()
    {
        $redirectUrl = $this->zoomService->getZoomRedirectUrl();
        return redirect($redirectUrl);
    }

    // Handle the callback from Zoom after user authorization
    public function handleZoomCallback(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return redirect()->route('home')->with('error', 'Authorization code missing');
        }

        // Get access token
        $tokenResponse = $this->zoomService->getAccessToken($code);
        // dd(vars: $tokenResponse,'test22');
        if (isset($tokenResponse['access_token'])) {

            // Store tokens in session or database
            // Session::put('zoom_access_token', $tokenResponse['access_token']);
            // Session::put('zoom_refresh_token', $tokenResponse['refresh_token']);
            // Session::put('zoom_token_expires_in', now()->addSeconds($tokenResponse['expires_in']));

            $zoomToken = ZoomToken::create($tokenResponse);
            dd($zoomToken);
            return redirect('/dashboard')->with('success', 'Zoom authorization successful!');
        } else {
            return redirect('/dashboard')->with('error', 'Failed to retrieve Zoom access token.');
        }
    }

    // Create a Zoom meeting (example of using access token)
    public function createMeeting($topic,$start_time,$duration)
    {
        // Fetch the Zoom access token from the session or database
        $accessToken = $this->zoomToken->access_token;

        // If token is missing, redirect to authorization
        if (!$accessToken) {
            return 'error';
            // return redirect()->route('home')->with('error', 'You need to authorize Zoom first.');
        }

        // Prepare meeting data
        $meetingData = [
            'topic' => $topic,
            'type' => 2, // Scheduled meeting
            'start_time' => $start_time, // ISO 8601 format required by Zoom
            'duration' => $duration, // Meeting duration in minutes
            'timezone' => 'UTC', // Optional timezone
        ];

        // Call Zoom API to create the meeting
        try {
            $meeting = $this->zoomService->createMeeting($accessToken, $meetingData);
            return $meeting;
        } catch (\Exception $e) {
            dd($e);
            // Handle error (e.g., token expired, Zoom API failure)
            return false;
        }
    }

    public function refreshAccessToken(){
        // dd($this->zoomToken->refresh_token);
        try {
            // dd($this->zoomService->refreshAccessToken($this->zoomToken->refresh_token));
            $response = $this->zoomService->refreshAccessToken($this->zoomToken->refresh_token);
            if (isset($response['access_token'])) {
                $zoomToken = ZoomToken::create($response);
                // Return back with a success message
                return back()->with('success', 'Zoom access token refreshed successfully.');
            }
            return back()->with('error', 'Failed to refresh the Zoom access token. No token received.');

        } catch (\Exception $e) {
            // dd($e);
             // Return back with an error message
            return back()->with('error', 'An error occurred while refreshing the Zoom access token.');
        }
    }
}
