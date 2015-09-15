<?php

return [
	'/{name}' => [
		'filters' => [
			'name' => '[a-zA-Z]'
		],
		'methods' => [
			'get' => 'application\controller\mIndexController',
			'post' => 'application\controller\WhateverController'
		]
	]
];