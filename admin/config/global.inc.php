<?php
/******************************************************

	This file is part of owsPro.

	OpenWebSoccer-Sim is free software: you can redistribute it
	and/or modify it under the terms of the
	GNU Lesser General Public License
	as published by the Free Software Foundation, either version 3 of
	the License, or any later version.

	OpenWebSoccer-Sim is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public
	License along with OpenWebSoccer-Sim.
	If not, see <http://www.gnu.org/licenses/>.

	Forked from OpenWebSoccer-Sim by @author Ingo Hofmann

	owsPro by @autor Rolf Joseph

	owsPro ( open web-(soccer) system ) Professional
	A library driven, code readable, minified programming paradigmen,
	for faster code with less code overhead.
******************************************************/
define('DEBUG',FALSE);
if(DEBUG)error_reporting(E_ALL);
else error_reporting(E_ERROR);
function classes_autoloader($class){
	$dirs=['','converters/','skins/','models/','validators/','actions/','services/','jobs/','loginmethods/','events/','plugins/'];
	foreach($dirs as$dir){
		if(file_exists(BASE_FOLDER.'/classes/'.$dir.$class.'.class.php'))require_once(BASE_FOLDER.'/classes/'.$dir.$class.'.class.php');}}
spl_autoload_register('classes_autoloader');
define('FOLDER_MODULES',BASE_FOLDER.'/modules');
define('MODULE_CONFIG_FILENAME','module.xml');
define('GLOBAL_CONFIG_FILE',BASE_FOLDER.'/generated/config.inc.php');
define('CONFIGCACHE_FILE_FRONTEND',BASE_FOLDER.'/cache/wsconfigfront.inc.php');
define('CONFIGCACHE_FILE_ADMIN',BASE_FOLDER.'/cache/wsconfigadmin.inc.php');
define('CONFIGCACHE_MESSAGES',BASE_FOLDER.'/cache/messages_%s.inc.php');
define('CONFIGCACHE_ADMINMESSAGES',BASE_FOLDER.'/cache/adminmessages_%s.inc.php');
define('CONFIGCACHE_ENTITYMESSAGES',BASE_FOLDER.'/cache/entitymessages_%s.inc.php');
define('CONFIGCACHE_SETTINGS',BASE_FOLDER.'/cache/settingsconfig.inc.php');
define('CONFIGCACHE_EVENTS',BASE_FOLDER.'/cache/eventsconfig.inc.php');
define('UPLOAD_FOLDER',BASE_FOLDER.'/uploads/');
define('IMPRINT_FILE',BASE_FOLDER.'/generated/imprint.php');
define('TEMPLATES_FOLDER',BASE_FOLDER.'/templates');
define('PROFPIC_UPLOADFOLDER',UPLOAD_FOLDER.'users');
include(GLOBAL_CONFIG_FILE);
if(!isset($conf)){
	header('location: install/index.php');
	exit;}
$page=null;
$action=null;
$block=null;
try{$website=WebSoccer::getInstance();
	if(!file_exists(CONFIGCACHE_FILE_FRONTEND))$website->resetConfigCache();}
catch(Exception $e){
	try{$log=new FileWriter('errorlog.txt');
		$log->writeLine('Website Configuration Error: '.$e->getMessage());
		$log->close();}
	catch(Exception $e){}
	header('HTTP/1.0 500 Error');
	die();}
try{$db=DbConnection::getInstance();
	$db->connect($website->getConfig('db_host'),$website->getConfig('db_user'),$website->getConfig('db_passwort'),$website->getConfig('db_name'));}
catch(Exception $e){
	try{$log=new FileWriter('dberrorlog.txt');
		$log->writeLine('DB Error: '.$e->getMessage());
		$log->close();
	} catch(Exception $e){}
	die('<h1>Sorry, our data base is currently not available</h1><p>We are working on it.</p>');}
$handler=new DbSessionManager($db,$website);
session_set_save_handler(
	[$handler,'open'],[$handler,'close'],[$handler,'read'],[$handler,'write'],[$handler,'destroy'],[$handler,'gc']);
register_shutdown_function('session_write_close');
session_start();
try{date_default_timezone_set($website->getConfig('time_zone'));}
catch(Exception $e){}
