<?php

namespace App\Console\Commands;

use App\Services\PostLis;
use Illuminate\Console\Command;

class PostDailyLisData extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'postlis:daily';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Post daily LIS data from sql-server database';

	protected $post;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(PostLis $post) {
		parent::__construct();
		$this->post = $post;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$this->post->daily();
	}
}
