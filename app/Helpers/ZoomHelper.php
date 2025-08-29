<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

class ZoomHelper
{
    public static function generateZoomSignature($meetingNumber, $role)
    {
        $sdkKey = env('ZOOM_MEETING_CLIENT_ID'); // Zoom SDK Key
        $sdkSecret = env('ZOOM_MEETING_CLIENT_SECRET'); // Zoom SDK Secret

        if (!$sdkKey || !$sdkSecret) {
            throw new \Exception("Zoom SDK Key/Secret is missing.");
        }

        $iat = time() - 30; // Issued at (30 seconds in the past)
        $exp = $iat + (2 * 60 * 60); // Expiry (2 hours)

        $payload = [
            'sdkKey' => $sdkKey,
            'mn' => (int) $meetingNumber, // Meeting number as string
            'role' => (int) $role, // Role: 1 = Host, 0 = Attendee
            'iat' => $iat, // Issued At
            'exp' => $exp, // Expiry
            'appKey' => $sdkKey, // Additional appKey field
            'tokenExp' => $exp, // Token Expiry
        ];

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        

        return JWT::encode($payload, $sdkSecret, 'HS256');

    }


}
