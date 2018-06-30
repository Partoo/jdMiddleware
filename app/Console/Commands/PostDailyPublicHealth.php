<?php

namespace App\Console\Commands;

use App\Services\PublicHealth\AgedProxy;
use Illuminate\Console\Command;

class PostDailyPublicHealth extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'posthealth:daily';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Post daily public health data from PublicHealth System';

	protected $post;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(AgedProxy $post) {
		parent::__construct();
		$this->post = $post;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$today = date('Y-m-d');
		$this->post->period($today);
	}
}
