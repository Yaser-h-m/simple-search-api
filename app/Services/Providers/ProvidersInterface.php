<?php

namespace App\Services\Providers;

interface ProvidersInterface
{
    //

    public function fetchData(string $url, string $query, int $page = 1, int $perPage = 10): array;
    public function normalizeData(array $data): array;

}


