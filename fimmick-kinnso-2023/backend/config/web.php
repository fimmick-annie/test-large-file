<?php

return [
	'offer_hunting' => [
		// "earning_port" => env('OFFER_HUNTING_EARNING_POINT', 5),
		"notification_emails_list" => explode(",", env('OFFER_HUNTING_NOTIFICATION_EMAILS', ''))
	]
];
