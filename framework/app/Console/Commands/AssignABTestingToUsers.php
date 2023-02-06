<?php

namespace App\Console\Commands;

use App\Models\AbTesting;
use App\Models\User\User;
use Illuminate\Console\Command;

class AssignABTestingToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab:testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign ab testing id into 3 equal chunks in user table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $user       = new User();
        $total_user = $user->count();
        $chunk      = $total_user / 3;

        $userIstChunk           = $user->take($chunk)->get();
        $userIstChunkIstID      = $userIstChunk->first();
        $userIstChunkLastID     = $userIstChunk->sortByDesc('id')->first();
        $userSecondChunk        = $user->skip($chunk)->take($chunk)->get();
        $userSecondChunkFirstID = $userSecondChunk->first();
        $userSecondChunkLastID  = $userSecondChunk->sortByDesc('id')->first();
        $userThirdChunk         = $user->skip($chunk + $chunk)->take($chunk)->get();
        $userThirdChunkFirstID  = $userThirdChunk->first();
        $userThirdChunkLastID   = $userThirdChunk->sortByDesc('id')->first();

        $user->where('id', '>=', $userIstChunkIstID->id)->where('id', '<=', $userIstChunkLastID->id)->update(['ab_testing_id' => User::AB_TESTING_TYPE_FIRST]);
        $user->where('id', '>', $userSecondChunkFirstID->id)->where('id', '<=', $userSecondChunkLastID->id)->update(['ab_testing_id' => User::AB_TESTING_TYPE_SECOND]);
        $user->where('id', '>', $userThirdChunkFirstID->id)->where('id', '<=', $userThirdChunkLastID->id)->update(['ab_testing_id' => User::AB_TESTING_TYPE_THIRD]);
    }
}
