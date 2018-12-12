<?php
/**
 * Created by PhpStorm.
 * User: derek
 * Date: 2018/10/25
 * Time: 17:22
 */

require "vendor/autoload.php";

$parameters = ['tcp://ip:6392', 'tcp://ip:6393', 'tcp://ip:6394', 'tcp://ip:6397'];
$options    = ['cluster' => 'redis'];

$client = new Predis\Client($parameters, $options);

$res=$client->set("bbb",456);

var_dump($client->get("bbb"));