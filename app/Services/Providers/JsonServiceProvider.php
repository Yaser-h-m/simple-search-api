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

        return $response;
    }

    public function normalizeData(array $data): array
    {
        if (!isset($data['contents']) || !is_array($data['contents'])) {
            return [];
        }

        $normalized = [];

        foreach ($data['contents'] as $item) {
            $type = strtolower($item['type']);

            if( $item['metrics']['duration']){
                $durationArray = explode(':', $item['metrics']['duration']);
                $duration = $durationArray[0] * 60 + $durationArray[1];
            }
            $data = [
                'external_id'         => $item['id'],
                'title'               => $item['title'],
                'type'                => $type === 'article' ? 'text' : $type,
                'views'               => $item['metrics']['views'] ?? null,
                'likes'               => $item['metrics']['likes'] ?? null,
                'reactions'           => $item['metrics']['reactions'] ?? null,
                'duration' => $duration?? null,
                'reading_time'        => (int)($item['metrics']['reactions'] ?? 0),
                'published_at' => Carbon::parse($item['published_at'])->toDateTimeString(),
                'tags'     => $item['tags'] ?? [],
                
            ];


            $normalized[] = $data;
        }

        return $normalized;
    }
}
