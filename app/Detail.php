<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
	protected $table = 'as_repentry';
	public function report() {
		return $this->belongsTo('App\Report', 'rep_no', 'rep_no');
	}
}
