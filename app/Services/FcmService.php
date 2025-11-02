<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\UserDevice;

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

        $successCount = 0;

        foreach ($tokens as $token) {
            $payload = [
                'message' => [
                    'token' => $token,
                    'data' => (object) $data,
                    'webpush' => [
                        'notification' => [
                            'title' => (string) $title,
                            'body'  => (string) $body,
                            'icon'  => 'https://infruity.com/icon.png',
                            'click_action' => 'https://infruity.com',
                        ],
                        'fcm_options' => [
                            'link' => 'https://infruity.com',
                        ],
                    ],
                ],
            ];

            try {
                $response = Http::withToken($accessToken)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->send('POST', $url, ['body' => json_encode($payload)]);

                if ($response->failed()) {
                    $body = $response->json();
                    if (isset($body['error']['details'][0]['errorCode']) && $body['error']['details'][0]['errorCode'] === 'UNREGISTERED') {
                        Log::warning("🧹 Menghapus token invalid: $token");
                        UserDevice::where('fcm_token', $token)->delete();
                    } else {
                        Log::error("❌ FCM Error: " . $response->body());
                    }
                } else {
                    Log::info("✅ Notifikasi terkirim ke: $token");
                }
            } catch (\Exception $e) {
                Log::error("❌ Gagal kirim notifikasi ke {$token}: " . $e->getMessage());
            }
        }

        Log::info("✅ Notifikasi FCM berhasil dikirim ke semua token.", ['count' => $successCount]);
        return true;
    }
}
