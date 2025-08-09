<?php

namespace App\Console\Commands;

use App\Services\Providers\JsonServiceProvider;
use App\Services\Providers\XmlServiceProvider;
use Illuminate\Console\Command;
use App\Models\Content;

class FetchProvidersDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:providers:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $providers = \App\Models\Provider::all();
        foreach ($providers as $provider) {
            $serviceProvider = $provider->type == 'json'? new JsonServiceProvider() : new XmlServiceProvider();

            $response = $serviceProvider->fetchData($provider->url);

            $data_array = $serviceProvider->normalizeData($response);

            foreach($data_array as $dataitem)
            {
                Content::updateOrCreate(
                    [
                        
                    ]
                    );
            }
        }
    }
}
