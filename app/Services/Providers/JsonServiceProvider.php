<?php

namespace App\Services\Providers;

use App\Services\Providers\ProvidersInterface;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class JsonServiceProvider implements ProvidersInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function fetchData(string $url, string $query = '', int $page = 1, int $perPage = 10): array
    {
        $response = Http::get($url, [
            'q' => $query,
            'page' => $page,
            'per_page' => $perPage
        ])->throw()->json();

        return $response->getBody()->getContents();
    }

    public function normalizeData( $data): array
    {
        $decoded = json_decode($data, true);

        if (!isset($decoded['contents']) || !is_array($decoded['contents'])) {
            return [];
        }

        $normalized = [];

        foreach ($decoded['contents'] as $item) {
            $type = strtolower($item['type']);

            $data = [
                'external_id'         => $item['id'],
                'title'               => $item['title'],
                'type'                => $type === 'article' ? 'text' : $type,
                'views'               => $item['metrics']['views'] ?? null,
                'likes'               => $item['metrics']['likes'] ?? null,
                'reactions'           => $item['metrics']['reactions'] ?? null,
                'duration' => $item['metrics']['duration'] ?? null,
                'reading_time'        => null,
                'source_published_at' => Carbon::parse($item['published_at'])->toDateTimeString(),
                'tags'     => $item['tags'] ?? [],
                
            ];

            if ($type === 'video') {
                $data['views'] = (int)($item['metrics']['views'] ?? 0);
                $data['likes'] = (int)($item['metrics']['likes'] ?? 0);
            } elseif ($type === 'article') {
                // If JSON provider ever sends articles
                $data['reading_time'] = (int)($item['metrics']['reading_time'] ?? 0);
                $data['reactions']    = (int)($item['metrics']['reactions'] ?? 0);
            }

            $normalized[] = $data;
        }

        return $normalized;
    }
}
