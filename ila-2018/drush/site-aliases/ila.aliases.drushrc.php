<?php

$aliases['dev'] = [
  'uri' => 'http://test-gl.ila-berlin.de',
  'root' => '/var/www/development/web',
  'remote-host' => '172.104.132.131',
  'remote-user' => 'admin',
];

$aliases['stage'] = [
  'uri' => 'http://172.104.132.131',
  'root' => '/var/www/stage/web',
  'remote-host' => '172.104.132.131',
  'remote-user' => 'admin',
];

$aliases['prod'] = [
  'uri' => 'http://172.104.132.131',
  'root' => '/var/www/production/current/web',
  'remote-host' => '172.104.132.131',
  'remote-user' => 'admin',
];
