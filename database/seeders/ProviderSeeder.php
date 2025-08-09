<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $providers = [
           'json' =>  [
                'name' => 'provider_1',
                'url' => 'https://raw.githubusercontent.com/WEG-Technology/mock/refs/heads/main/v2/provider1',
                'type' => 'json'
           ],
           'xml' => [
                'name' => 'provider_2',
                'url' => 'https://raw.githubusercontent.com/WEG-Technology/mock/refs/heads/main/v2/provider2',
                'type' => 'xml'
           ]
        ];

        foreach($providers as $provider) {
            Provider::updateOrCreate([
                'name' => $provider['name'],
                'url' => $provider['url'],
                'type' => $provider['type']
            ]);
        }

    }
}
