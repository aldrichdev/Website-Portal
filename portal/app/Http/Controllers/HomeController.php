<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * @function index()
     * @return Renderable
     * Shows the main home page and associated database queries
     */
    public function index()
    {
        $game_hostname = config('app.game_hostname');
        $game_port = config('app.game_port');
        $online = DB::table('players')
            ->where('online', 1)
            ->count();
        $status = @fsockopen($game_hostname, $game_port, $num, $error, 2);
        if ($status) {
            $status = '<span style="color: lime">Online</span>';
        } else {
            $status = '<span style="color: red">Offline</span>';
        }

        $registrations = DB::table('players')
                ->whereRaw('creation_date >= unix_timestamp(current_date - interval 1 day)')
                ->count() ?? '0';

        $logins = DB::table('players')
                ->whereRaw('login_date >= unix_timestamp(current_date - interval 48 hour)')
                ->count() ?? '0';

        $totalPlayers = DB::table('players')
                ->count() ?? '0';

        $uniquePlayers = DB::table('players')
            ->distinct('creation_ip')
            ->count('creation_ip');

        $milliseconds = DB::table('player_cache')
            ->where('key', 'total_played')
            ->sum('value');

        $totalTime = HomeController::secondsToTime(round($milliseconds / 1000));

        $activityfeed = DB::table('live_feeds as B')
            ->join('players AS A', 'A.username', '=', 'B.username')
            ->where([
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
                ['B.time', '>=', 'unix_timestamp(current_date - interval 10 day)'],
            ])
            ->orderBy('time', 'desc')
            ->limit(20)
            ->get();

        $sumgold_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $sumgold_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $sumgold = $sumgold_B + $sumgold_I;

        return view(
            'home',
            [
                'online' => $online,
                'status' => $status,
                'registrations' => $registrations,
                'logins' => $logins,
                'totalPlayers' => $totalPlayers,
                'uniquePlayers' => $uniquePlayers,
                'totalTime' => $totalTime,
                'activityfeed' => $activityfeed,
                'sumgold' => $sumgold,
            ]
        );
    }

    /**
     * @function secondsToTime()
     * @param $inputSeconds
     * @return int
     * Used to calculate the total input of seconds into years, days, hours, minutes, and seconds
     */
    public function secondsToTime($inputSeconds)
    {
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;
        $secondsInAYear = 365 * $secondsInADay;

        // Extract years
        $years = floor($inputSeconds / $secondsInAYear);

        // Extract days
        $daySeconds = $inputSeconds % $secondsInAYear;
        $days = floor($daySeconds / $secondsInADay);

        // Extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // Extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // Extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        // Format and return
        $timeParts = [];
        $sections = [
            'yr' => (int)$years,
            'day' => (int)$days,
            'hr' => (int)$hours,
            'min' => (int)$minutes,
            'sec' => (int)$seconds,
        ];
        foreach ($sections as $name => $value) {
            if ($value > 0) {
                $timeParts[] = $value . ' ' . $name . ($value == 1 ? '' : 's');
            }
        }
        return implode(', ', $timeParts);
    }

    public function wilderness()
    {
        return view('wilderness');
    }

    public function faq()
    {
        return view('faq');
    }

    public function rules()
    {
        return view('rules');
    }

    public function online()
    {
        $players = DB::table('players as B')
            ->join('player_cache AS A', 'A.playerID', '=', 'B.id')
            ->where([
                ['B.online', '=', '1'],
                ['A.key', '=', 'total_played']
            ])
            ->orderBy('B.login_date')
            ->get();

        return view(
            'online',
            [
                'players' => $players,
            ]
        );
    }

    public function createdtoday()
    {
        $players = DB::table('players AS B')
            ->whereRaw('B.creation_date >= unix_timestamp(current_date - interval 1 day)')
            ->join('player_cache AS A', 'A.playerID', '=', 'B.id')
            ->where([
                ['A.key', '=', 'total_played']
            ])
            ->orderBy('B.login_date', 'desc')
            ->orderBy('B.creation_date', 'desc')
            ->get();

        return view(
            'createdtoday',
            [
                'players' => $players,
            ]
        );
    }

    public function logins48()
    {
        $players = DB::table('players AS B')
            ->whereRaw('B.login_date >= unix_timestamp(current_date - interval 48 hour)')
            ->join('player_cache AS A', 'A.playerID', '=', 'B.id')
            ->where([
                ['A.key', '=', 'total_played']
            ])
            ->orderBy('B.login_date', 'desc')
            ->get();

        return view(
            'logins48',
            [
                'players' => $players,
            ]
        );
    }

    public function stats()
    {
        $online = DB::table('players')
            ->where('online', '=', '1')
            ->count('online');

        $registrations = DB::table('players')
                ->whereRaw('creation_date >= unix_timestamp(current_date - interval 1 day)')
                ->count() ?? '0';

        $logins48 = DB::table('players')
            ->whereRaw('login_date >= unix_timestamp(current_date - interval 48 hour)')
            ->orderBy('login_date', 'desc')
            ->count();

        $totalPlayers = DB::table('players')
                ->count() ?? '0';

        $uniquePlayers = DB::table('players')
            ->distinct('creation_ip')
            ->count('creation_ip');

        $createdToday = DB::table('players')
            ->whereRaw('creation_date >= unix_timestamp(current_date - interval 1 day)')
            ->orderBy('login_date', 'desc')
            ->orderBy('creation_date', 'desc')
            ->count();

        $milliseconds = DB::table('player_cache')
            ->where('key', 'total_played')
            ->sum('value');

        $totalTime = HomeController::secondsToTime(round($milliseconds / 1000));

        $current_timestamp = now()->timestamp;

        $sumgold_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $sumgold_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $sumgold = $sumgold_B + $sumgold_I;

        $combat30 = DB::table('players')
            ->where([
                ['combat', '>=', '30'],
                ['group_id', '=', '10'],
                ['banned', '=', 0],
            ])
            ->count();

        $combat50 = DB::table('players')
            ->where([
                ['combat', '>=', '50'],
                ['group_id', '=', '10'],
                ['banned', '=', 0],
            ])
            ->count();

        $combat80 = DB::table('players')
            ->where([
                ['combat', '>=', '50'],
                ['group_id', '=', '10'],
                ['banned', '=', 0],
            ])
            ->count();

        $combat90 = DB::table('players')
            ->where([
                ['combat', '>=', '90'],
                ['group_id', '=', '10'],
                ['banned', '=', 0],
            ])
            ->count();

        $combat100 = DB::table('players')
            ->where([
                ['combat', '>=', '100'],
                ['group_id', '=', '10'],
                ['banned', '=', 0],
            ])
            ->count();

        $combat123 = DB::table('players')
            ->where([
                ['combat', '>=', '123'],
                ['group_id', '=', '10'],
                ['banned', '=', 0],
            ])
            ->count();

        $startedQuest = DB::table('quests as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['A.group_id', '=', '10'],
                ['A.banned', '=', 0],
            ])
            ->distinct('B.playerID')
            ->count();

        $gold30_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '30000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold30_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '30000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold30 = $gold30_B + $gold30_I;

        $gold50_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '50000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold50_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '50000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold50 = $gold50_B + $gold50_I;

        $gold80_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '80000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold80_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '80000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold80 = $gold80_B + $gold80_I;

        $gold120_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '120000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold120_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '120000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold120 = $gold120_B + $gold120_I;

        $gold400_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '400000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold400_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '400000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold400 = $gold400_B + $gold400_I;

        $gold1m_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '1000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold1m_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '1000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold1m = $gold1m_B + $gold1m_I;

        $gold12m_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '12000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold12m_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '12000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold12m = $gold12m_B + $gold12m_I;

        $gold15m_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '15000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold15m_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '15000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold15m = $gold15m_B + $gold15m_I;

        $gold2m_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '2000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold2m_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '2000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold2m = $gold2m_B + $gold2m_I;

        $gold4m_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '4000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold4m_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '4000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold4m = $gold4m_B + $gold4m_I;

        $gold10m_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '10000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $gold10m_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '10'],
                ['S.amount', '>=', '10000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $gold10m = $gold10m_B + $gold10m_I;

        $pumpkin_B = DB::table('bank as B') // bank
        ->join('players AS A', 'B.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'B.itemID')
            //->join('invitems as I', 'I.playerID', '=', 'A.id')
            ->where([
                ['S.catalogID', '=', '422'],
                ['S.amount', '>=', '1000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('S.amount');

        $pumpkin_I = DB::table('invitems as I') // inventory
        ->join('players AS A', 'I.playerID', '=', 'A.id')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'I.itemID')
            ->where([
                ['S.catalogID', '=', '422'],
                ['S.amount', '>=', '1000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->count();

        $pumpkin_A = DB::table('auctions as U') // auction
        ->join('players AS A', 'U.seller_username', '=', 'A.username')
            ->join('itemstatuses AS S', 'S.itemID', '=', 'U.itemID')
            ->where([
                ['S.catalogID', '=', '422'],
                ['S.amount', '>=', '1000000'],
                //['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
                ['U.was_cancel', '=', '0'],
                ['U.sold-out', '=', '0'],
                ['U.amount_left', '>', '0'],
            ])
            ->count();

        $pumpkin = $pumpkin_B + $pumpkin_I + $pumpkin_A;

        /*$cracker = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '575'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $yellowphat = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '577'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $whitephat = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '581'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $purplephat = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '580'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $redphat = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '576'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $bluephat = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '578'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $greenphat = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->join('itemstatuses AS C', 'C.itemID', '=', 'B.itemID')
            ->where([
                ['B.itemID', '=', '579'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('C.amount');

        $greenphatBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '579'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $greenphat = $greenphatInvitems + $greenphatBank;

        $eastereggInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '677'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $eastereggBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '677'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $easteregg = $eastereggInvitems + $eastereggBank;

        $redmaskInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '831'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $redmaskBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '831'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $redmask = $redmaskInvitems + $redmaskBank;

        $bluemaskInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '832'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $bluemaskBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '832'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $bluemask = $bluemaskInvitems + $bluemaskBank;

        $greenmaskInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '828'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $greenmaskBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '828'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $greenmask = $greenmaskInvitems + $greenmaskBank;

        $santahatInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '971'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $santahatBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '971'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $santahat = $santahatInvitems + $santahatBank;

        $bunnyearsInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '1156'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $bunnyearsBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '1156'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $bunnyears = $bunnyearsInvitems + $bunnyearsBank;

        $scytheInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '1289'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $scytheBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '1289'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $scythe = $scytheInvitems + $scytheBank;

        $dsqInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '1278'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dsqBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '1278'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dsq = $dsqInvitems + $dsqBank;

        $dmedInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '795'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dmedBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '795'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dmed = $dmedInvitems + $dmedBank;

        $dammyInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '522'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->orWhere([
                ['B.id', '=', '597'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dammyBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '522'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->orWhere([
                ['B.id', '=', '597'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dammy = $dammyInvitems + $dammyBank;

        $dbattleInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '594'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dbattleBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '594'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dbattle = $dbattleInvitems + $dbattleBank;

        $dlongInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '593'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dlongBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '593'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $dlong = $dlongInvitems + $dlongBank;

        $cabbageInvitems = DB::table('bank as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '18'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->orWhere([
                ['B.id', '=', '228'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $cabbageBank = DB::table('invitems as B')
            ->join('players AS A', 'A.id', '=', 'B.playerID')
            ->where([
                ['B.id', '=', '18'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->orWhere([
                ['B.id', '=', '228'],
                ['A.group_id', '=', '10'],
                ['A.banned', '=', '0'],
            ])
            ->sum('B.amount');

        $cabbage = $cabbageInvitems + $cabbageBank;*/

        return view(
            'stats',
            [
                'online' => $online,
                'registrations' => $registrations,
                'logins48' => $logins48,
                'totalPlayers' => $totalPlayers,
                'uniquePlayers' => $uniquePlayers,
                'totalTime' => $totalTime,
                'createdToday' => $createdToday,
                'combat30' => $combat30,
                'combat50' => $combat50,
                'combat80' => $combat80,
                'combat90' => $combat90,
                'combat100' => $combat100,
                'combat123' => $combat123,
                'startedQuest' => $startedQuest,
                'sumgold' => $sumgold,
                'gold30' => $gold30,
                'gold50' => $gold50,
                'gold80' => $gold80,
                'gold120' => $gold120,
                'gold400' => $gold400,
                'gold1m' => $gold1m,
                'gold12m' => $gold12m,
                'gold15m' => $gold15m,
                'gold2m' => $gold2m,
                'gold4m' => $gold4m,
                'gold10m' => $gold10m,
                'pumpkin' => $pumpkin,
                /*'cracker' => $cracker,
                'yellowphat' => $yellowphat,
                'whitephat' => $whitephat,
                'purplephat' => $purplephat,
                'redphat' => $redphat,
                'bluephat' => $bluephat,
                'greenphat' => $greenphat,
                'easteregg' => $easteregg,
                'redmask' => $redmask,
                'bluemask' => $bluemask,
                'greenmask' => $greenmask,
                'santahat' => $santahat,
                'bunnyears' => $bunnyears,
                'scythe' => $scythe,
                'dsq' => $dsq,
                'dmed' => $dmed,
                'dammy' => $dammy,
                'dbattle' => $dbattle,
                'dlong' => $dlong,
                'cabbage' => $cabbage,*/
            ]
        );
    }

    public function worldmap()
    {
        $playerPositions = DB::connection()
            ->table('players')
            ->where([
                ['banned', '=', '0'],
                ['group_id', '=', '10'],
                ['online', '=', '1'],
            ])
            ->get();

        $playerPositions = $playerPositions->toArray();

        return view('worldmap', [
            'playerPositions' => $playerPositions,
        ]);
    }
}