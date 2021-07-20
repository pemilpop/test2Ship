<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Exchange;

use App\Jobs\Test;

class Test implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exchange;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Exchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $post = json_encode($this->exchange);
        
        $authorization = "Authorization: Bearer ".base64_encode('Emil_Escu');;

        $ch = curl_init('http://tools.test-me.xyz/site/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            $authorization)
        );

        curl_exec($ch);
        curl_close($ch);

    }
}
