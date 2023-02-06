<?php

namespace App\Jobs\Mobile;

use App\Contracts\MobileGatewayInterface;
use App\Jobs\JobSettings;
use App\Models\User\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateMobileUserJob
 * @package App\Jobs
 */
class UpdateMobileUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobSettings;

    /**
     * @var User
     */
    private User $user;

    /**
     * UpdateMobileUser constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param MobileGatewayInterface $mobileGateway
     * @throws Exception
     */
    public function handle(MobileGatewayInterface $mobileGateway)
    {
        try {
            $mobileGateway->updateAccount($this->user);
        } catch (Exception $e) {
            $this->onFail($e);
        }
    }
}
