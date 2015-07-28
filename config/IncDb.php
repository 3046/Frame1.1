<?php
/**
 * 数据库配置
 * @todo: 该配置不得直接上传外网
 * @todo: 新加配置后请在最后reset这个配置变量
 * ModXxx类中用进行调用相应的配置
 * $this->dbLocation -> location 配置
 * $this->db -> defaut配置
 */
$user['db'] = 'qq163_user'; // 兼容数据后台,须写db

$user['master']['host'] = 'localhost:3306';
$user['master']['user']  = 'root'; 
$user['master']['passwd']  = ''; //'ftAZtX2CFMh7NxPt';


$user['slave']['host'][] = 'localhost:3306';
$user['slave']['user']  = 'root'; 
$user['slave']['passwd']  = ''; //'ftAZtX2CFMh7NxPt';


$card['db'] = 'qq163_card'; // 兼容数据后台,须写db

$card['master']['host'] = 'localhost:3306';
$card['master']['user']  = 'root'; 
$card['master']['passwd']  = 'ftAZtX2CFMh7NxPt'; //'ftAZtX2CFMh7NxPt';

$card['slave']['host'][] = 'localhost:3306';
$card['slave']['user']  = 'root'; 
$card['slave']['passwd']  = 'ftAZtX2CFMh7NxPt'; //'ftAZtX2CFMh7NxPt';


$location['db'] = 'test'; // 兼容数据后台,须写db

$location['master']['host'] = 'localhost:3306';
$location['master']['user']  = 'root'; 
$location['master']['passwd']  = ''; //'ftAZtX2CFMh7NxPt';

$location['slave']['host'][] = 'localhost:3306';
$location['slave']['user']  = 'root';
$location['slave']['passwd']  = ''; //'ftAZtX2CFMh7NxPt';


$db133['db'] = 'test'; //  如133,默认连接test库,可以在 sql语句中加db,如无主从,可以直接写host user,passwd
$db133['host'] = '192.168.1.133:3306';
$db133['user']  = 'root'; 
$db133['passwd']  = 'ftAZtX2CFMh7NxPt'; //'ftAZtX2CFMh7NxPt';

// dbUser
$GLOBALS['config']['db'] = array(
    'default'=> $location,
    'location'=> $location,
    'user'=>$user,
    'card'=>$card,
    'db133'=>$db133,
    //
    );

unset($location);
unset($user);
unset($card);
unset($gameData);