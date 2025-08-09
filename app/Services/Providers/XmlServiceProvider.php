<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class XmlServiceProvider implements ProvidersInterface
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
        //

        $response = Http::get($url, [
            'q' => $query,
            'page' => $page,
            'per_page' => $perPage
        ]);
        $json_data = simplexml_load_string($response->getBody()->getContents());
        $string_data = json_encode($json_data);
        $data = json_decode($string_data, true);
        return $data;
    }
    public function normalizeData(array $data) : array
    {
        if (!isset($data['items']) || !is_array($data['items'])) {
            return [];
        }

        $normalized = [];

        foreach ($data['items']['item'] as $item) {
            $type = strtolower($item['type']);

            if( isset($item['stats']['duration'])){
                $durationArray = explode(':', $item['stats']['duration']);
                $duration = $durationArray[0] * 60 + $durationArray[1];
            }

            $tags = $item['categories'] ? $item['categories']['category'] : [];
            $data = [
                'external_id'         => $item['id'],
                'title'               => $item['headline'],
                'type'                => $type,
                'views'               => $item['stats']['views'] ?? 0,
                'likes'               => $item['stats']['likes'] ?? 0,
                'reactions'           => $item['stats']['reactions'] ?? 0,
                'duration' => $duration?? 0,
                'reading_time'        => (int)($item['stats']['reading_time'] ?? 0),
                'published_at' => Carbon::parse($item['publication_date'])->toDateTimeString(),
                'tags'     => $tags ?? [],
                
            ];


            $normalized[] = $data;
        }

        return $normalized;
    }
}
