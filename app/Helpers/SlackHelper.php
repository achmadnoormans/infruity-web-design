<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackHelper
{
    public static function sendOrderNotification($order, $creatorName)
    {
        $webhookUrl = config('services.slack.order_webhook');
        if (!$webhookUrl) {
            return; // Jika tidak ada webhook, skip
        }

        $payload = [
            'username' => 'Order System',
            'icon_emoji' => ':package:',
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => '📦 Order Book Baru!',
                        'emoji' => true
                    ]
                ],
                [
                    'type' => 'section',
                    'fields' => [
                        [
                            'type' => 'mrkdwn',
                            'text' => "*ID Order:*\n#" . $order->id
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Dibuat Oleh:*\n" . e($creatorName)
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Total:*\nRp " . number_format($order->total, 0, ',', '.')
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Tanggal:*\n" . now()->format('d M Y H:i')
                        ]
                    ]
                ],
                [
                    'type' => 'actions',
                    'elements' => [
                        [
                            'type' => 'button',
                            'text' => ['type' => 'plain_text', 'text' => 'Lihat di Aplikasi'],
                            'url' => url('/pos/' . $order->id . '/show'),
                            'value' => 'view_order'
                        ]
                    ]
                ]
            ]
        ];
        try {
            // Http::post($webhookUrl, $payload);
            Http::withOptions([
                'verify' => false, // ⚠️ Nonaktifkan SSL verification (hanya untuk local!)
            ])->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('Gagal kirim notifikasi Slack: ' . $e->getMessage());
        }
    }
}
