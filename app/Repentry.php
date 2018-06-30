<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repentry extends Model {
	protected $table = 'as_repentry';
	protected $primaryKey = 'rep_no';

	public function report() {
		return $this->belongsTo('App\Report', 'rep_no', 'rep_no');
	}
}
