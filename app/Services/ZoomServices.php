<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ZoomServices
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $accountId;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.zoom.base_url', 'https://api.zoom.us/v2/');
        $this->clientId = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
        $this->accountId = env('ZOOM_ACCOUNT_ID');
        $this->accessToken = $this->getAccessToken();
    }

    private function getAccessToken()
    {
        try {
            $response = $this->client->request('POST', 'https://zoom.us/oauth/token', [
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
            return $data['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching Zoom Access Token: ' . $e->getMessage());
            return null;
        }
    }

    public function createMeeting($startTime, $doctorEmail, $clientEmail)
{
    if (!$this->accessToken) {
        return ['error' => 'Missing Zoom access token'];
    }

    try {
        $response = $this->client->post($this->baseUrl . 'users/me/meetings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'topic' => 'Medical Consultation',
                'type' => 2,
                'start_time' => $startTime,
                'duration' => 40,
                'timezone' => 'UTC',
                'agenda' => 'Consultation Call',
                'settings' => [
                    "host_video" => true,
                    "participant_video" => true,
                    "waiting_room" => true,
                    "auto_recording" => "cloud",
                    "join_before_host" => false,
                    'mute_upon_entry' => true,
                    'approval_type' => 0,
                    'audio' => 'voip',
                ]
            ],
        ]);

        // dd($response);

        return json_decode($response->getBody(), true);
    } catch (\Exception $e) {
        Log::error('Error creating Zoom meeting: ' . $e->getMessage());
        return ['error' => 'Failed to create Zoom meeting'];
    }
}


    /**
     * Enable Auto-Recording for a Scheduled Zoom Meeting
     */
    public function startZoomRecording($meetingId)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get Zoom access token'], 500);
        }

        // Check if the meeting exists
        $meetingDetails = $this->getMeetingDetails($meetingId);
        if ($meetingDetails instanceof \Illuminate\Http\JsonResponse && $meetingDetails->getStatusCode() === 404) {
            return response()->json(['error' => 'Meeting not found'], 404);
        }

        // Update meeting settings to enable auto-recording
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->patch("https://api.zoom.us/v2/meetings/{$meetingId}", [
            "settings" => [
                "auto_recording" => "cloud"
            ]
        ]);

        return $response->json();
    }

    /**
     * Get Zoom Meeting Details (Check Meeting Exists)
     */
    public function getMeetingDetails($meetingId)
{
    if (!$meetingId) {
        return response()->json(['error' => 'Meeting ID is required'], 400);
    }

    $accessToken = $this->getAccessToken();

    if (!$accessToken) {
        return response()->json(['error' => 'Failed to get Zoom access token'], 500);
    }

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->get("https://api.zoom.us/v2/meetings/{$meetingId}");

    $responseData = $response->json();

    // Handle error cases
    if ($response->failed()) {
        if (isset($responseData['code']) && $responseData['code'] == 3001) {
            return response()->json([
                'error' => 'Meeting does not exist or has expired.',
                'code' => 3001
            ], 404);
        }

        return response()->json([
            'error' => $responseData['message'] ?? 'Unknown error',
            'code' => $responseData['code'] ?? 500
        ], $response->status());
    }

    return response()->json($responseData);
}

    

    /**
     * End a Zoom Meeting (Force Logout for Participants)
     */
    public function endMeeting($meetingId)
    {
        // Get the access token
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get Zoom access token'], 500);
        }
    
        // Make the PUT request to end the meeting
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->put("https://api.zoom.us/v2/meetings/{$meetingId}/status", [
            'action' => 'end'
        ]);
    
        // Debug the response
        // dd($response->json());
    
        // Return the response
        return $response->json();
    }

    /**
     * Get Zoom Meeting Cloud Recordings
     */
    public function getMeetingRecordings($meetingId)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get Zoom access token'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get("https://api.zoom.us/v2/meetings/{$meetingId}/recordings");

        return $response->json();
    }


}
