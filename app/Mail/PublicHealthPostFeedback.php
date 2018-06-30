<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicHealthPostFeedback extends Mailable {
	use Queueable, SerializesModels;

	protected $data;

	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->view('mail.feedback')
			->subject('公卫系统数据录入报告')
			->with([
				'time' => $this->data['time'],
				'content' => $this->data['content'],
			]);
	}
}
