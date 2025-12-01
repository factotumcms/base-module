<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Services\Api\UserService;

class PrunePasswordHistories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password-history:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune password history table';

    /**
     * Execute the console command.
     * @var UserService $userService
     * @return void
     */
    public function handle(): void
    {
        if ($keep = config('factotum_base.auth.password_prune_keep')) {

            foreach (User::all() as $user) {
                $passwordHistories =
                    $user->password_histories()->orderByDesc('created_at')->limit($keep)->offset($keep)->get();
                $c = 0;

                foreach ($passwordHistories as $passwordHistory) {
                    $passwordHistory->delete();
                    $c++;
                }

                $this->info(sprintf('%d passwords has been pruned successfully for user with id %d',
                    $c, $user->id
                ));
            }

        } else {
            $this->warn('Number of passwords to keep not specified in configuration file.');
        }
    }
}
