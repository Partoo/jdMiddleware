<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {
	protected $table = 'as_report';
	protected $primaryKey = 'rep_no';

	public function repentry() {
		return $this->hasMany('App\Repentry', 'rep_no', 'rep_no');
	}
}
