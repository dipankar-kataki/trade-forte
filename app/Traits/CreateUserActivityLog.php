<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\userLog;

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

    protected function createLog(string $name, string $activity, string $resourceName, $resourceId, int $userId)
    {
        UserLog::create([
            'user_id' => $userId,
            'activity' => $activity,
            'resource_name' => $resourceName,
            'resource_id' => $resourceId,
        ]);
    }

}