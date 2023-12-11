<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\userLog;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait CreateUserActivityLog
{

    protected function createLog(string $user_id, string $activity, string $resourceName, $resourceId)
    {
        $hostedUrl = env('APP_HOSTED_URL');

        $resourceUrl = ($resourceId !== null)
            ? $hostedUrl . "/" . $resourceName . "/get/" . $resourceId
            : null;

        UserLog::create([
            'user_id' => $user_id,
            'activity' => $activity,
            'resource_url' => $resourceUrl,
            'resource_id' => $resourceId,
        ]);
    }


}