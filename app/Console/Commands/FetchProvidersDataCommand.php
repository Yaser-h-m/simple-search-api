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
        $providers = \App\Models\Provider::get();
        foreach ($providers as $provider) {

            try{

                $serviceProvider = $provider->type == 'json' ? new JsonServiceProvider() : new XmlServiceProvider();
                
                $response = $serviceProvider->fetchData($provider->url);
                
            $this->info("Provider: {$provider->name} data fetched");
            $data_array = $serviceProvider->normalizeData($response);

            foreach ($data_array as $dataitem) {
                $content = Content::updateOrCreate(
                    [
                        'external_id' => $dataitem['external_id'],
                        'provider_id' => $provider->id
                    ],
                    $dataitem
                );

                $score =  $content->calculateScore();
                $content->score = $score;
                $content->save();
            }
            $this->info("Provider: {$provider->name} data is added to database");
            }
            catch(\Exception $e){
                $this->error("Provider: {$provider->name} data fetching failed");
            }
        }

        $this->info("All providers data fetched");
    }
}
