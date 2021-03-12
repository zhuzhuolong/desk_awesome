<?php

namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;
use Mockery\Exception;

class WalletCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'wallet {name=Artisan}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Simplicity is the ultimate sophistication.');
        $list = DB::table('pool_user')
            ->get();
        //module=account&action=tokenbalance&contractaddress=0x57d90b64a1a57749b0f932f1a3395792e12e7055&address=0xe04f27eb70e025b78871a2ad7eabe85e61212761&tag=latest&apikey=YourApiKeyToken
        $client = new Client([
            'base_uri' => 'https://api-cn.etherscan.com/',
            // You can set any number of default request options.
            'timeout'  => 20,
        ]);
        foreach ($list as $v) {
            if (empty($v['wallet_address'])) {
                continue;
            }
            try {
                $res = $client->request('GET','/api', [
                    'query' => [
                        'module' => 'account',
                        'action' => 'tokenbalance',
                        'contractaddress' => '0xdac17f958d2ee523a2206206994597c13d831ec7',
                        'address' => $v['wallet_address'],
                        'tag' => 'latest',
                        'apikey' => '74PJ4TKEAN7X74T9TIE9NGQGUIQQ8SCEFC'
                    ]
                ]);
                $body = $res->getBody();
                $remainingBytes = $body->getContents();
                $remain = json_decode($remainingBytes , 1);
                if ($remain['result'] > 0) {
                    try {
                        DB::table('pool_has')
                            ->insert([
                                'wallet' => $v['wallet_address'],
                                'amount' => $remain['result']
                            ]);
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                        continue;
                    }

                }
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                continue;
            }

        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
