<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use DB;

class DailyExchange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It add daily the currency exchange';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->setExchangeDailyBNR('EUR');
        $this->setExchangeDailyBNR('USD');
        $this->info('Successfully setted daily currency exchange.');
    }

    function getExchangeBNR($currency)
	{
		$url = "http://www.bnro.ro/nbrfxrates.xml";
		$xmlDocument = file_get_contents($url);
       
		$xml = new \SimpleXMLElement($xmlDocument);
				 
		foreach($xml->Body->Cube->Rate as $line)    
		{ 
			if($line["currency"] == $currency) {
				return $line;
			}
		}
		
		return "error";
	}

    function setExchangeDailyBNR($currency)
	{
		$data = array();

        $data['currency'] = $currency;
        $data['date'] = date('Y-m-d');
        $data['value'] = $this->getExchangeBNR($currency);
        $data['source'] = 'API BNR';

        if($data['value'] && $data['value'] !== 'error') {
            DB::table('exchanges')->insert( $data );
        }
        \Log::info($data['date'].' : '.$data['currency']. ' : '.$data['value']);
	}
}
