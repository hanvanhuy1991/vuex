<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;

class ForgotPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @param [string] email
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $user = User::where('email', $this->email)->first();

            if ($user) {
                $newPassword = Str::random(8);

                $user->update(['password' => Hash::make($newPassword)]);

                Mail::to($this->email)->send(new ForgotPasswordEmail($newPassword));

                if (Mail::failures()) {
                    Log::error("Sent Forgot Password to $this->email FAILED!");
                } else {
                    Log::info("Sent Forgot Password to $this->email SUCCESSFULLY!");
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();
        }
    }
}
