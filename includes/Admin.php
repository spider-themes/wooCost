<?php

namespace wooCost;

class Admin {

	public function __construct() {
		new Admin\Menu();
		new Admin\Assets();
	}
}