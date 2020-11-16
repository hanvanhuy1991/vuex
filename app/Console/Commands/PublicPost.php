<?php

namespace App\Console\Commands;

use App\Services\PostService;
use Illuminate\Console\Command;

class PublicPost extends Command
{
    protected $postService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'public:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Public the draft posts.';

    /**
     * Create a new command instance.
     *
     * @param PostService $postService
     *
     * @return void
     */
    public function __construct(PostService $postService)
    {
        parent::__construct();

        $this->postService = $postService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->postService->publicPost();
    }
}
