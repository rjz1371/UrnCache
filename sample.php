<?php
/******** Display errors ********/
error_reporting( -1 );
ini_set( 'display_errors', 'On' );

/******** Require GjCache class ********/
require_once 'classes/GjCache.class.php';
$cache = new GjCache();

/******** Put cache data ********/
$data = [['username' => 'rjz1371', 'age' => 25],
         ['username' => 'reza', 'age' => 20],
         ['username' => 'alex', 'age' => 56]];
$cache->put( 'my-data', $data );

/******** Put cache data with expire time ********/
$data = [['username' => 'rjz1371', 'age' => 25],
         ['username' => 'reza', 'age' => 20],
         ['username' => 'alex', 'age' => 56]];
$cache->put( 'my-data-new', $data, 120 );

/******** Put cache data with grouping ********/
$data = [['username' => 'rjz1371', 'age' => 25],
         ['username' => 'reza', 'age' => 20],
         ['username' => 'alex', 'age' => 56]];
$cache->put( 'my-data', $data, 0, 'my-group' );

/******** Get cache data ********/
$result = $cache->get( 'my-data' );
var_dump( $result );

/******** Get cache data in special group ********/
$result = $cache->get( 'my-data', 'my-group' );
var_dump( $result );

/******** Check cache exists ********/
$result = $cache->has('my-data');
var_dump($result);