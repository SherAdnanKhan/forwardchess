<?php

namespace App\Repositories\User;

use App\Contracts\MailChimpServiceInterface;
use App\Exceptions\CommonException;
use App\Models\AbstractModel;
use App\Repositories\AbstractModelRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository extends AbstractModelRepository
{
    /**
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function destroy(AbstractModel $model): bool
    {
        try {
            DB::beginTransaction();

            $success = $model->delete();
            if (!$success) {
                $this->dataNotSavedException();
            }

            if (method_exists($this, self::CB_AFTER_DESTROY)) {
                call_user_func_array([$this, self::CB_AFTER_DESTROY], [$model]);
            }

            DB::update('UPDATE orders SET userId = ? WHERE userId = ?', [env('DELETED_USER_ID'), $model->id]);

            DB::commit();

            app(MailChimpServiceInterface::class)->unsubscribe($model, true);

            return $success;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dataNotSavedException();
        }
    }
}