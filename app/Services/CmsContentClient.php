<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CmsContentClient
{
    public function photoStory(): ?array
    {
        $baseUrl = rtrim((string) config('cms.api_base_url'), '/');

        if ($baseUrl === '') {
            return null;
        }

        try {
            $response = Http::timeout((int) config('cms.timeout', 5))
                ->acceptJson()
                ->get("{$baseUrl}/api/photo-story");

            if (! $response->successful()) {
                Log::warning('CMS photo-story endpoint returned a non-success response.', [
                    'status' => $response->status(),
                    'url' => "{$baseUrl}/api/photo-story",
                ]);

                return null;
            }

            $payload = $response->json();

            if (! is_array($payload)) {
                return null;
            }

            return [
                'carousel' => array_values($payload['carousel'] ?? []),
                'latest' => array_values($payload['latest'] ?? []),
                'popular' => array_values($payload['popular'] ?? []),
            ];
        } catch (\Throwable $exception) {
            Log::warning('Failed to fetch photo-story data from CMS.', [
                'message' => $exception->getMessage(),
                'url' => "{$baseUrl}/api/photo-story",
            ]);

            return null;
        }
    }
}
