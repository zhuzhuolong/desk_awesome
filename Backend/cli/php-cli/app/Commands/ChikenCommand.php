<?php

namespace App\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use GuzzleHttp\Cookie\CookieJar;
class ChikenCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'command:chiken';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */


    public function handle()
    {
        //

        $cookieJar = CookieJar::fromArray([
            '_cas_sid' => '5de6029f5a694a328f7c2a4f97c63569',
            '_cas_uid' => '1828357561438333757',
            'LoginCookieName' => '7CB0F6C1252C783348890C759AFD67015C87A77A8DD6D2A00D1A728DF433028B84C94A67EB0E0388E77014278A51FED604AB34330ACA59B7D69920E08B4880FB7353CA863E9BB6530B1B0A2F154DBB44F40C60666CF83D56094B360BC049C05821E0FB4FAE0B31702ACE2358950A5CF59A8AC9BBA2E617D6B736EFD55B3ED26873A6C738590D712E85BB66919D85BEBA04E034975B41E2D122DBCBF9F4F30DD89652D671C032DE135FE24FA853E6862AF876E7751C475B37079A85170CFB0775',
            'sticky_SSIONID' => 'd99bcbaab2969d8e613cb2c0f6fc3fb3',
            'ASP.NET_SessionId' => 'liwthdbl4ijyjg13etio0jra',
            'EnteID'=> '1810989',
            'systems'=> '1',
            'FinanceMonthN' => '2020-11',
            'NX_LOG' => '9dfa655f936b41d6b347dec3169b59b1',
            'Hm_lvt_22a004f8a01ef2fa33b0a5a60e5de817' => '1611208064',
            'Hm_lpvt_22a004f8a01ef2fa33b0a5a60e5de817' => '1611208067',
            'nxin_stat_ss' => '225X3SFHYD_4_1611208091740',
            'nxin_stat_ss' => 'IPL92PYY1O_1_1611210098444'
        ], '.nxin.com');
        $client = new Client();
        $date = [
            ['2019-01-01' , '2019-01-31'],
            ['2019-02-01' , '2019-02-28'],
            ['2019-03-01' , '2019-03-31'],
            ['2019-04-01' , '2019-04-30'],
            ['2019-05-01' , '2019-05-31'],
            ['2019-06-01' , '2019-06-30'],
            ['2019-07-01' , '2019-07-31'],
            ['2019-08-01' , '2019-08-31'],
            ['2019-09-01' , '2019-09-30'],
            ['2019-10-01' , '2019-10-31'],
            ['2019-11-01' , '2019-11-30'],
            ['2019-12-01' , '2019-12-31'],

            ['2020-01-01' , '2020-01-31'],
            ['2020-02-01' , '2020-02-28'],
            ['2020-03-01' , '2020-03-31'],
            ['2020-04-01' , '2020-04-30'],
            ['2020-05-01' , '2020-05-31'],
            ['2020-06-01' , '2020-06-30'],
            ['2020-07-01' , '2020-07-31'],
            ['2020-08-01' , '2020-08-31'],
            ['2020-09-01' , '2020-09-30'],
            ['2020-10-01' , '2020-10-31'],
            ['2020-11-01' , '2020-11-30'],
            ['2020-12-01' , '2020-12-31'],
        ];
        foreach ($date as $v ) {
            $res = $client->post('http://qlw.nxin.com/EggChickenEntry/GetEggChickenEntryData', [
                'form_params' => [
                    'StartTime' => $v[0],
                    'EndTime' => $v[1],
                    'ChickenName' => ''
                ],
                'cookies' => $cookieJar
            ]);
            $array = json_decode((string)$res->getBody(), true);
            $fp = fopen($v[0].'-'.$v[1].'.csv','a');
            $header = ['鸡场名称','制单人名称','手机','鸡场进鸡数','鸡场存栏数','养殖日志记录数','喂养单数','产蛋单数','死淘单数'];
            fputcsv($fp, $header);
            foreach ($array as $a) {
                $s[0] = $a['ChickenFarmName'];
                $s[1] = $a['OwnerName'];
                $s[2] = $a['Phone'];
                $s[3] = $a['iCount'];
                $s[4] = $a['Quantity'];
                $s[5] = $a['DeathRecord'];
                $s[6] = $a['FeedingRecord'];
                $s[7] = $a['LayingRecord'];
                $s[8] = $a['DeathRecord'];
                fputcsv($fp, $s);
                $s = [];
            }
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
