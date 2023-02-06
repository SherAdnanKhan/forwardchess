<?php

namespace App\Observers;

use App\Contracts\MailChimpServiceInterface;
use App\Exceptions\CommonException;
use App\Jobs\Mobile\RegisterMobileUserJob;
use App\Jobs\Mobile\UpdateMobileUserJob;
use App\Models\AbstractModel;
use App\Models\AbTesting;
use App\Models\User\User;
use Exception;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver extends MainObserver
{
    /**
     * Listen to the models created event.
     *
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function created(AbstractModel $model): bool
    {
        if (isProduction()) {
            RegisterMobileUserJob::dispatch($model);
        }

        $abTesting = array(User::AB_TESTING_TYPE_FIRST, User::AB_TESTING_TYPE_SECOND, User::AB_TESTING_TYPE_THIRD);
        $abTesting = $abTesting[array_rand($abTesting)];;

        $model->update(['ab_testing_id' => $abTesting]);

        return parent::created($model);
    }

    /**
     * Listen to the models updating event.
     *
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function updating(AbstractModel $model): bool
    {
        if (isProduction()) {
            UpdateMobileUserJob::dispatch($model);
        }

        /** @var MailChimpServiceInterface $mailChimpService */
        /** @var User $model */

        $mailChimpService = app(MailChimpServiceInterface::class);

        try {
            $model->subscribed
                ? $mailChimpService->subscribe($model)
                : $mailChimpService->unsubscribe($model);
        } catch (Exception $exception) {
            //            dd($exception);
        }

        return parent::updating($model);
    }
}
