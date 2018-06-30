<?php
namespace App\Http\Controllers;

use App\Services\PublicHealth\AgedProxy;

/**
 * 公共卫生系统抓取患者数据
 */
class PublicHealthPatientController extends Controller {

	protected $proxy;
	public function __construct(AgedProxy $proxy) {
		$this->proxy = $proxy;
	}

	public function show() {
		return $this->proxy->show();
	}
	public function index() {
		return $this->proxy->period('2017-01-01', '');
	}
}