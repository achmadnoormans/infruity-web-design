<?php

namespace App\Services;

use App\Models\UserDevice;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\DB;
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

    public function sendNotification(array $tokens, string $title, string $body, array $data = []): bool
    {
        if (empty($tokens)) {
            Log::warning('⚠️ Tidak ada token FCM yang dikirim.');
            return false;
        }

        // 🧹 Hapus token duplikat (penyebab utama notifikasi double)
        $tokens = array_unique($tokens);

        // Ambil token yang benar-benar ada di database (hindari token duplikat multi device)
        $tokens = UserDevice::whereIn('fcm_token', $tokens)
            ->distinct()
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            Log::warning('⚠️ Setelah filter, token kosong.');
            return false;
        }

        $accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $invalidTokens = [];

        // 🔥 Kirim per 30 token (batch), tetapi 1 REQUEST = 30 token, bukan 30 request
        $chunks = array_chunk($tokens, 30);

        foreach ($chunks as $batch) {

            // FCM supports sending multiple tokens using "message => tokens"
            $payload = [
                'message' => [
                    'tokens' => $batch, // 👈 kirim 1x untuk semua token
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                    'webpush' => [
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'icon' => 'https://infruity.com/icon.png',
                            'click_action' => 'https://infruity.com',
                        ],
                    ],
                ],
            ];

            try {
                $response = Http::withToken($accessToken)
                    ->timeout(10)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                $json = $response->json();

                // FCM akan merespon token mana yang invalid
                if (!empty($json['error'])) {
                    Log::error('❌ Error batch: ' . json_encode($json, JSON_UNESCAPED_SLASHES));
                }

            } catch (\Throwable $e) {
                Log::error("❌ Error kirim batch: " . $e->getMessage());
            }

            // jeda 200ms
            usleep(200000);

            // Hindari overload DB connection
            DB::disconnect();
        }

        // 🧹 Hapus token invalid (jika ada)
        if (!empty($invalidTokens)) {
            UserDevice::whereIn('fcm_token', $invalidTokens)->delete();
            Log::info("🧹 Menghapus token invalid: " . count($invalidTokens));
        }

        Log::info("🎯 Selesai kirim notifikasi ke " . count($tokens) . " device.");
        return true;
    }

    public function sendNotificationOld(array $tokens, string $title, string $body, array $data = []): bool
    {
        if (empty($tokens)) {
            Log::warning('⚠️ Tidak ada token FCM yang dikirim.');
            return false;
        }

        $accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $invalidTokens = [];

        // ✅ Kirim batch 30 token per jeda 0.2 detik untuk hindari overheat
        $chunks = array_chunk($tokens, 30);
        foreach ($chunks as $batch) {
            foreach ($batch as $token) {
                $payload = [
                    'message' => [
                        'token' => $token,
                        'data' => (object) $data,
                        'webpush' => [
                            'notification' => [
                                'title' => (string) $title,
                                'body' => (string) $body,
                                'icon' => 'https://infruity.com/icon.png',
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
                        ->timeout(10)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, $payload);

                    if ($response->failed()) {
                        $error = $response->json();
                        $code = $error['error']['details'][0]['errorCode'] ?? null;

                        if ($code === 'UNREGISTERED') {
                            $invalidTokens[] = $token;
                            Log::warning("🧹 Token invalid ditemukan: {$token}");
                        } else {
                            Log::error('❌ FCM Error: ' . json_encode($error, JSON_UNESCAPED_SLASHES));
                        }
                    } else {
                        Log::info("✅ Notifikasi terkirim ke: {$token}");
                    }
                } catch (\Throwable $e) {
                    Log::error("❌ Gagal kirim ke {$token}: " . $e->getMessage());
                }
            }

            // ✅ Delay antar batch (200ms)
            usleep(200000);

            // ✅ Tutup koneksi DB setiap batch (penting!)
            DB::disconnect();
        }

        // ✅ Hapus semua token invalid secara efisien dalam satu query
        if (!empty($invalidTokens)) {
            UserDevice::whereIn('fcm_token', $invalidTokens)->delete();
            Log::info('🧹 Token invalid dihapus dari database.', ['count' => count($invalidTokens)]);
        }

        Log::info('🎯 FCM broadcast selesai.', ['total_tokens' => count($tokens)]);
        return true;
    }
}
