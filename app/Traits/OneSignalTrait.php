<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait OneSignalTrait {

    public function registerOUser($deviceId, $deviceType) {
        $client = new Client([
            'base_uri' => 'https://onesignal.com/api/v1/',
            'verify' => false,
            'headers' => [
                'Authorization' => 'Basic ' . env('PUSHER_APP_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        $response = $client->post('players', [
            'json' => [
                'app_id' => env('PUSHER_APP_ID'),
                'identifier' => $deviceId,
                'language' => 'en',
                'device_type' => ($deviceType == 'ios') ? 0 : 1,
                'external_user_id' => auth('sanctum')->user()->id,
                'tags' => ['language' => auth('sanctum')->user()->language], // Segment based on language
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $response = json_decode($response->getBody(), true);
            if (isset($response['id'])) {
                //$oldSid = auth('sanctum')->user()->os_sid;
                auth('sanctum')->user()->device_id = $deviceId;
                auth('sanctum')->user()->device_type = $deviceType;
                auth('sanctum')->user()->os_subscribed = true;
                auth('sanctum')->user()->os_sid = $response['id'];
                auth('sanctum')->user()->save();
                /* //Delete old device.If need to keep old device, comment this and uncomment update user device ids and update segment commented sections
                  if ($oldSid != $response['id']) {
                  if (!empty($oldSid)) {
                  $this->deleteOldODevice($oldSid);
                  }
                  } */
                \App\Models\UserOdevice::updateOrCreate(
                        ['user_id' => auth('sanctum')->user()->id, 'os_sid' => $response['id']]
                );
            }
        }
        return 1;
    }

    public function deleteOldODevice($oldSid) {
        try {
            $client = new Client(['verify' => false]);
            $response = $client->request('DELETE', 'https://onesignal.com/api/v1/players/' . $oldSid . '?app_id=' . env('PUSHER_APP_ID'), [
                'headers' => [
                    'Authorization' => 'Basic ' . env('PUSHER_APP_KEY'),
                    'accept' => 'application/json',
                ],
            ]);
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
        return 1;
    }

    public function updateSegment($language) {
        $client = new Client([
            'base_uri' => 'https://onesignal.com/api/v1/',
            'verify' => false,
            'headers' => [
                'Authorization' => 'Basic ' . env('PUSHER_APP_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        $response = $client->post('players/' . auth('sanctum')->user()->os_sid . '/on_session', [
            'json' => [
                'app_id' => env('PUSHER_APP_ID'),
                'language' => $language,
                'tags' => ['language' => $language],
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            //Update all old devices start
            $dids = auth('sanctum')->user()->osIds()
                    ->where('os_sid', '<>', auth('sanctum')->user()->os_sid)
                    ->take(10)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            foreach ($dids as $did) {
                try {
                    $response = $client->post('players/' . $did->os_sid . '/on_session', [
                        'json' => [
                            'app_id' => env('PUSHER_APP_ID'),
                            'language' => $language,
                            'tags' => ['language' => $language],
                        ],
                    ]);
                } catch (\Exception $e) {
                    
                }
            }
            //Update all old devices end 
            return 1;
        } else {
            return 2;
            //dd($response->getBody());
        }
    }

    public function updateODeviceId($deviceId, $deviceType) {
        $client = new Client([
            'base_uri' => 'https://onesignal.com/api/v1/',
            'verify' => false,
            'headers' => [
                'Authorization' => 'Basic ' . env('PUSHER_APP_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        $response = $client->put('players/' . auth('sanctum')->user()->os_sid, [
            'json' => [
                'app_id' => env('PUSHER_APP_ID'),
                'device_type' => ($deviceType == 'ios') ? 0 : 1,
                'identifier' => $deviceId,
            ],
        ]);
        return 1;
    }

    function sendONotification($body) {
        try {
            //Save to db
            $notification = new \App\Models\PushNotification();
            $notification->user_id = $body['include_external_user_ids'];
            $notification->title = $body['headings']['en'];
            $notification->message = $body['contents']['en'];
            $notification->module = $body['data']['module'];
            $notification->module_id = $body['data']['module_id'];
            $notification->extras = $body['data'];
            $notification->save();
            
            $http = new Client(['verify' => false]);
            $body['app_id'] = env('PUSHER_APP_ID');
            $response = $http->post('https://onesignal.com/api/v1/notifications', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('PUSHER_APP_KEY'),
                ],
                'json' => $body,
            ]);

            if ($response->getStatusCode() === 200) {
                return ['status' => true, 'message' => 'Push notification sent successfully!'];
            } else {
                return ['status' => false, 'message' => 'Failed to send push notification: ' . $response->getBody()];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Failed to send push notification: ' . $e->getMessage()];
        }
    }
}
