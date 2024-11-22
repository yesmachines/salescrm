<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use \App\Traits\OneSignalTrait;

class SendMeetingPush extends Command {

    use OneSignalTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting:push {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification for upcoming meetings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $type = $this->argument('type');
        $todayDubai = Carbon::now('Asia/Dubai')->toDateString();

        switch ($type) {
            case 'day':
                $startOfDayDubai = Carbon::parse($todayDubai . ' 07:00:00')->setTimezone('Asia/Dubai'); // 7:00 AM in Dubai
                $endOfDayDubai = Carbon::parse($todayDubai . ' 19:00:00')->setTimezone('Asia/Dubai'); // 07:00 PM in Dubai
                $startOfDayUtc = $startOfDayDubai->setTimezone('UTC');
                $endOfDayUtc = $endOfDayDubai->setTimezone('UTC');
                break;
            case 'night':
                $startOfDayDubai = Carbon::parse($todayDubai . ' 19:00:00')->setTimezone('Asia/Dubai'); // 07:00 PM in Dubai
                $endOfDayDubai = Carbon::parse($todayDubai . ' 07:00:00')->addDay()->setTimezone('Asia/Dubai'); //next day 7 AM dubai
                $startOfDayUtc = $startOfDayDubai->setTimezone('UTC');
                $endOfDayUtc = $endOfDayDubai->setTimezone('UTC');
                break;
        }

        $meetings = \DB::table('meetings')
                        ->selectRaw('user_id, COUNT(*) as meetings_count')
                        ->whereBetween('scheduled_at', [
                            $startOfDayUtc->toDateTimeString(),
                            $endOfDayUtc->toDateTimeString(),
                        ])
                        ->groupBy('user_id')
                        ->get()->each(function ($meeting) {
            $body = [
                'headings' => ['en' => trans('api.notification.title.cron')],
                'contents' => ['en' => trans('api.notification.message.cron', ['count' => $meeting->meetings_count])],
                'data' => [
                    'module' => 'meeting-list',
                    'module_id' => null,
                    'meetings_count' => $meeting->meetings_count,
                ]
            ];
            $body ['include_external_user_ids'] = [$meeting->user_id];
            $body ['channel_for_external_user_ids'] = 'push';
            $this->sendONotification($body);
        });
    }
}
