<?php

namespace wooProfit;

class Admin {

	public function __construct() {
		new Admin\Menu();
		new Admin\Assets();
	}
}