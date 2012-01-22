<?php
	if (!class_exists('CSRFProtection')) {
		class CSRFProtection {
			public static function tokenTag() { return ''; }
		}
	}

	require_once 'vendor/trails/trails.php';
	require_once 'app/controllers/studip_controller.php';
	require_once 'app/controllers/authenticated_controller.php';
	
	require_once 'models/Quicklink.class.php';
