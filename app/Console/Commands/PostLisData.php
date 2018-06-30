<?php

namespace App\Console\Commands;

use App\Services\PostLis;
use Illuminate\Console\Command;

class PostLisData extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'postlis:period';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Post Period LIS data from sql-server database';

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
		$from = $this->ask('请输入起始时间,如:170701');
		$to = $this->ask('请输入截止时间,如171230');
		$from = \DateTime::createFromFormat('ymd', $from);
		if (!$to) {
			$to = date('Y.m.d');
		}
		$to = \DateTime::createFromFormat('ymd', $to);
		$this->post->period($from->format('Y.m.d'), $to->format('Y.m.d'));
	}
}
