<?php

$structure = [
	'app' => [
		'data'   => [
			'attachments' => ['.gitkeep'],
		],
		'etc'    => ['.gitkeep'],
		'public' => ['.gitkeep'],
		'tmp'    => ['.gitkeep'],
		'var'    => [
			'log' => ['.gitkeep'],
		],
	],
];

function struct($tree, $root) {
	$root = rtrim($root, '/') . '/';
	foreach ($tree as $name => $value) {
		if (is_array($value)) {
			mkdir($root . $name, 0777, true);
			struct($value, $root . $name);
		} else {
			touch($root . $value);
		}
	}
}

struct($structure, __DIR__);

rename(__DIR__ . '/install/atomino.ini', __DIR__ . '/atomino.ini');
rename(__DIR__ . '/install/vhost', __DIR__ . '/app/etc/vhost');
unlink(__DIR__ . '/.gitignore');
rename(__DIR__ . '/install/.gitignore.dist', __DIR__ . '/.gitignore');

file_put_contents(__DIR__ . '/app/var/version', '1');

$composer = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);

$composer['authors'] = [];
unset($composer['description']);
unset($composer['repositories']);
$composer['name'] = basename(__DIR__);

file_get_contents(__DIR__ . '/composer.json', json_encode($composer, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE));

$package = json_decode(file_get_contents(__DIR__ . '/package.json'), true);
$package['author'] = '';
$package['name'] = basename(__DIR__);
file_get_contents(__DIR__ . '/package.json.json', json_encode($package, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE));

unlink(__DIR__.'/install');
unlink(__DIR__.'/post-install-script.php');