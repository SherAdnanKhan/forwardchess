<?php

namespace App\Jobs\Mailchimp;

use App\Contracts\MailChimpServiceInterface;
use App\Jobs\JobSettings;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserRegistrationJob
 * @package App\Jobs\Mailchimp
 */
class UserRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobSettings;

    /**
     * @var User
     */
    private $user;

    /**
     * UserRegistrationJob constructor.
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
     * @param MailChimpServiceInterface $mailChimpService
     */
    public function handle(MailChimpServiceInterface $mailChimpService)
    {
        try {
            $mailChimpService->registerUser($this->user);
        } catch (\Exception $e) {
            $ignoreCodes = [409, 503];

            if (!in_array($e->getCode(), $ignoreCodes)) {
                $this->onFail($e);
            }
        }
    }
}
