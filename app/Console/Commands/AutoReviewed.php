<?php

namespace App\Console\Commands;

use App\Services\PublicHealth\UnReviewProxy;
use Illuminate\Console\Command;

class AutoReviewed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviewed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto reviewed';

    protected $post;

    /**
     * Create a new command instance.
     *
     * @param UnReviewProxy $post
     */
    public function __construct(UnReviewProxy $post)
    {
        parent::__construct();
        $this->post = $post;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->post->scrape();
    }
}
