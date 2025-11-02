<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $client;
    protected $projectId;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setAuthConfig(base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $this->projectId = env('FCM_PROJECT_ID');
    }

    public function sendNotification(array $tokens, string $title, string $body, array $data = [])
    {
        $accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        foreach ($tokens as $token) {
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => !empty($data) ? $data : new \stdClass(),
                    'webpush' => [
                        'fcm_options' => [
                            'link' => 'https://infruity.com',
                        ],
                    ],
                ],
            ];

            try {
                $response = Http::withToken($accessToken)
                    ->post($url, $payload);

                if ($response->failed()) {
                    Log::error('❌ FCM Error: ' . $response->body());
                } else {
                    Log::info('✅ Notifikasi terkirim ke: ' . $token);
                }
            } catch (\Exception $e) {
                Log::error("❌ Gagal kirim notifikasi ke {$token}: " . $e->getMessage());
            }
        }

        return true;
    }
}
