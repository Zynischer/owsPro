<?php
/******************************************************

  This file is part of OpenWebSoccer-Sim.

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

******************************************************/
owsPro();						// Funktion owsPro als als oberste Definition zur Verfügung stellen.
WebSoccer();					// Gekapselte Klasse WebSoccer				  zur Verfügung stellen.
WebSoccer_functions();			// Gekapseelte Websoccer native Funktionen    zur Verfügung stellen.
WebSoccer_class();				// gekapselte original WebSocccer Klasse	  zur Verfügung stellne.
DbConnection();					// Gekapselte Klasse DbConnection			  zur Verfügung stellen.
DbConnection_functions();		// Gekapseelte DbConnection native Funktionen zur Verfügung stellen.
DbConnection_class();			// gekapselte original DbConnection Klasse	  zur Verfügung stellne.
I18n();							// Gekapselte Klasse I18n			  		  zur Verfügung stellen.
I18n_functions();				// Gekapseelte I18n native Funktionen 		  zur Verfügung stellen.
I18n_class();					// gekapselte original I18nn Klasse	  		  zur Verfügung stellne.
/**
 * Die Codeausführung schneller machen, indem vorab die wichtigsten
 * Dateien im Opcache kompiliert vorliegen.
 * Es muss mindestens PHP 8.1 vorhanden sein!
 *
 * @author Rolf Joseph
 */
if(!function_exists('owsPro'))opcache_compile_file('WebSoccer.class.php');
/**
 * Funktions-Kapselung der original Klasse, nativer PHP-Funktionen.
 * Dies wird durch den Konstrukt Funktion in einer Funktion realisiert.
 *
 * Wer Änderungen am Original vornimmt, muss z.B. die Kapselung der original
 * Vorlage kopieren und der einen neuen Namen z.B. MyWebSoccer_class vergeben
 * und durch einen Funktionsaufruf zur Verfügung stellen.
 * So können Variationen einfach implementiert bzw. Fehler nachgegangen werden.
 *
 * @author Rolf Joseph
 */
// Funktion owsPro als als oberste Definition
function owsPro(){
// Gekapselte Klasse WebSoccer
function WebSoccer(){
// Gekapseelte Websoccer native Funktionen
function WebSoccer_functions(){
/**
 * Core variables for working as native PHP functions for all classes.
 *
 * @author Rolf Joseph
 */
class val{
	static $instance,$websoccer,$db,$i18n,$user,$skin,$pageId,$templateEngine,$_frontMessages,$_isAjaxRequest,$_contextParameters,$_absence,$_leagueId,$_type,$connection,$_queryCache;}
/**
 * Core functions and application context state of the current request,
 * as native PHP functions, without to use a class of websoccer instance.
 *
 * @author Rolf Joseph
 */
function getUser(){
	if(val::$user==NULL)val::$user=new User();
	return val::$user;}
function getConfig($name){
	global$conf;
	if(!isset($conf[$name]))throw new Exception('Konfigurationseintrag wurde nicht gefunden: '.$name);
	return(string)$conf[$name];}
function getAction($id){
	global$action;
	if(!isset($action[$id]))throw new Exception('Action not found: '.$id);
	return $action[$id];}
function getSkin(){
	if(val::$skin==NULL){
		$skinName=getConfig('skin');
		if(class_exists($skinName))val::$skin=new $skinName(val::$skin);
		else throw new Exception('Configured skin \''.$skinName.'\' does not exist. Check the system settings.');}
	return val::$skin;}
function getPageId(){return val::$pageId;}
function setPageId($pageId){val::$pageId=$pageId;}
function getTemplateEngine($i18n,ViewHandler $viewHandler=NULL){
	if(val::$templateEngine==NULL)val::$templateEngine=new TemplateEngine(WebSoccer::getInstance(),$i18n,$viewHandler);
	return val::$templateEngine;}
function getRequestParameter($name){
	if(isset($_REQUEST[$name])){
		$value=trim($_REQUEST[$name]);
		if(strlen((string)$value)){return$value;}}
	return NULL;}
function getInternalUrl($pageId=NULL,$queryString='',$fullUrl=FALSE){
	if($pageId==NULL)$pageId=PageId();
	if(strlen((string)$queryString))$queryString='&'.$queryString;
	if($fullUrl){
		$url=getConfig('homepage').getConfig('context_root');
		if($pageId!='home'||strlen((string)$queryString))$url.='/?page='.$pageId.$queryString;}
	else$url=getConfig('context_root').'/?page='.$pageId.$queryString;
	return$url;}
function getInternalActionUrl($actionId,$queryString='',$pageId=NULL,$fullUrl=FALSE){
	if($pageId==NULL)$pageId=Request('page');
	if(strlen((string)$queryString)){$queryString='&'.$queryString;}
	$url=getConfig('context_root').'/?page='.$pageId.$queryString.'&action='.$actionId;
	if($fullUrl)$url=getConfig('homepage').$url;
	return$url;}
function getFormattedDate($timestamp=NULL){
	if($timestamp==NULL)$timestamp=getNowAsTimestamp();
	return date(getConfig('date_format'),$timestamp);}
function getFormattedDatetime($timestamp,I18n $i18n=NULL){
	if($timestamp==NULL)$timestamp=getNowAsTimestamp();
	if($i18n!=NULL){
		$dateWord=StringUtil::convertTimestampToWord($timestamp,getNowAsTimestamp(),$i18n);
		if(strlen((string)$dateWord))return$dateWord.','.date(getConfig('time_format'),$timestamp);}
	return date(getConfig('datetime_format'),$timestamp);}
function getNowAsTimestamp(){return time()+getConfig('time_offset');}
function resetConfigCache(){
	getConfig('supported_languages');
	$cacheBuilder=new ConfigCacheFileWriter(getSupportedLanguages());
	$cacheBuilder->buildConfigCache();}
function addFrontMessage(FrontMessage$message){
		val::$_frontMessages[]=$message;}
function getFrontMessages(){
	if(val::$_frontMessages==NULL)$this->_frontMessages=[];
	return val::$_frontMessages;}
function setAjaxRequest($isAjaxRequest){
	val::$_isAjaxRequest=$isAjaxRequest;}
function isAjaxRequest(){return val::$_isAjaxRequest;}
function getContextParameters(){
	if(val::$_contextParameters==NULL)$_contextParameters=[];
	return val::$_contextParameters;}
function addContextParameter($name,$value){
	if(val::$_contextParameters==NULL)val::$_contextParameters=[];
	val::$_contextParameters[$name]=$value;}
}
// gekapselte original WebSocccer Klasse
function WebSoccer_class(){
/**
 * Core functions and application context state of the current request.
 *
 * @author Ingo Hofman
 */
class WebSoccer{
	private static $_instance;
	private $_skin;
	private $_pageId;
	private $_templateEngine;
	private $_frontMessages;
	private $_isAjaxRequest;
	private $_user;
	private $_contextParameters;
	static function getInstance(){
        if(self::$_instance==NULL)self::$_instance=new WebSoccer();
        return self::$_instance;}
    function __construct(){
		$this->_isAjaxRequest=FALSE;}
    function getUser(){
    	if($this->_user==NULL)$this->_user=new User();
    	return$this->_user;}
	function getConfig($name){
		global$conf;
		if(!isset($conf[$name]))throw new Exception('Missing configuration: '.$name);
		return$conf[$name];}
	function getAction($id){
		global$action;
		if(!isset($action[$id]))throw new Exception('Action not found: '.$id);
		return$action[$id];}
	function getSkin(){
		if($this->_skin==NULL){
			$skinName=getConfig('skin');
			if(class_exists($skinName))$this->_skin=new $skinName($this);
			else throw new Exception('Configured skin \''.$skinName.'\' does not exist. Check the system settings.');}
		return$this->_skin;}
	function getPageId(){
		return$this->_pageId;}
	function setPageId($pageId){
		$this->_pageId=$pageId;}
	function getTemplateEngine($i18n,ViewHandler$viewHandler=NULL){
		if($this->_templateEngine==NULL)$this->_templateEngine=new TemplateEngine($this,$i18n,$viewHandler);
		return$this->_templateEngine;}
	function getRequestParameter($name){
		if (isset($_REQUEST[$name])){
			$value=trim($_REQUEST[$name]);
			if(strlen($value))return$value;}
		return NULL;}
	function getInternalUrl($pageId=NULL,$queryString='',$fullUrl=FALSE){
		if($pageId==NULL)$pageId=$this->getPageId();
		if(strlen($queryString))$queryString='&'.$queryString;
		if($fullUrl){
			$url=getConfig('homepage').getConfig('context_root');
			if($pageId!='home'||strlen($queryString))$url .='/?page='.$pageId.$queryString;}
		else$url=getConfig('context_root').'/?page='.$pageId.$queryString;
		return$url;}
	function getInternalActionUrl($actionId,$queryString='',$pageId=NULL,$fullUrl=FALSE){
		if($pageId==NULL)$pageId=$this->getRequestParameter('page');
		if(strlen($queryString))$queryString='&'.$queryString;
		$url=getConfig('context_root').'/?page='.$pageId.$queryString.'&action='.$actionId;
		if($fullUrl)$url=getConfig('homepage').$url;
		return$url;}
	function getFormattedDate($timestamp=NULL){
		if($timestamp==NULL)$timestamp=$this->getNowAsTimestamp();
		return date(getConfig('date_format'),$timestamp);}
	function getFormattedDatetime($timestamp,I18n$i18n=NULL){
		if($timestamp==NULL)$timestamp=$this->getNowAsTimestamp();
		if($i18n!=NULL){
			$dateWord=StringUtil::convertTimestampToWord($timestamp,$this->getNowAsTimestamp(),$i18n);
			if(strlen($dateWord))return$dateWord.', '.date(getConfig('time_format'),$timestamp);}
		return date(getConfig('datetime_format'),$timestamp);}
	function getNowAsTimestamp(){
		return time()+getConfig('time_offset');}
	function resetConfigCache(){
		$i18n=I18n::getInstance(getConfig('supported_languages'));
		$cacheBuilder=new ConfigCacheFileWriter($i18n->getSupportedLanguages());
		$cacheBuilder->buildConfigCache();}
	function addFrontMessage(FrontMessage $message){
		$this->_frontMessages[]=$message;}
	function getFrontMessages(){
		if($this->_frontMessages==NULL)$this->_frontMessages=[];
		return$this->_frontMessages;}
	function setAjaxRequest($isAjaxRequest){
		$this->_isAjaxRequest=$isAjaxRequest;}
	function isAjaxRequest(){
		return$this->_isAjaxRequest;}
	function getContextParameters(){
		if($this->_contextParameters==NULL)$this->_contextParameters=[];
		return$this->_contextParameters;}
	function addContextParameter($name,$value){
		if($this->_contextParameters==NULL)$this->_contextParameters=[];
		$this->_contextParameters[$name]=$value;}}
/**
 * Data Base Connection class.
 * As native PHP functions.
 *
 * @author Rolf Joseph
 */
function DbConnection(){
function DbConnection_functions(){
function connect($host,$user,$password,$dbname){
	val::$connection=new mysqli($host,$user,$password,$dbname);
	val::$connection->set_charset('utf8');
	if(mysqli_connect_error()){throw new Exception('Die Datenbank ist zur Zeit nicht verfï¿½gbar: ('.mysqli_connect_errno().') '.mysqli_connect_error());}}
function close(){
	val::$connection->close();}
function querySelect($fromTable,$columns,$whereCondition,$parameters=NULL,$limit=NULL){
	$queryStr=buildQueryString($fromTable,$columns,$whereCondition,$parameters,$limit);
	return executeQuery($queryStr);}
function queryCachedSelect($fromTable,$columns,$whereCondition,$parameters = NULL,$limit = NULL){
	$queryStr=buildQueryString($fromTable,$columns,$whereCondition,$parameters,$limit);
	if(isset($queryCache[$queryStr]))return $queryCache[$queryStr];
	$result=executeQuery($queryStr);
	$rows=[];
	while($row=$result->fetch_array())$rows[]=$row;
	$result->free();
	$queryCache[$queryStr]=$rows;
	return$rows;}
function queryUpdate($fromTable,$columns,$whereCondition,$parameters){
	$queryStr='UPDATE '.$fromTable.' SET ';
	$queryStr=$queryStr.buildColumnsValueList($columns);
	$queryStr=$queryStr.' WHERE ';
	$wherePart=buildWherePart($whereCondition,$parameters);
	$queryStr=$queryStr.$wherePart;
	executeQuery($queryStr);
	$queryCache=[];}
function queryDelete($fromTable,$whereCondition,$parameters){
	$queryStr='DELETE FROM '.$fromTable;
	$queryStr=$queryStr.' WHERE ';
	$wherePart=buildWherePart($whereCondition,$parameters);
	$queryStr=$queryStr.$wherePart;
	executeQuery($queryStr);
	$queryCache=[];}
function queryInsert($fromTable,$columns){
	$queryStr='INSERT '.$fromTable.' SET ';
	$queryStr=$queryStr.buildColumnsValueList($columns);
	executeQuery($queryStr);}
function getLastInsertedId(){
	return val::$connection->insert_id;}
function buildQueryString($fromTable,$columns,$whereCondition,$parameters=NULL,$limit=NULL)
{	$queryStr='SELECT ';
	if(is_array($columns)){
		$firstColumn=TRUE;
		foreach($columns as$dbName=>$aliasName){
			if(!$firstColumn)$queryStr=$queryStr.',';
			else$firstColumn=FALSE;
			if(is_numeric($dbName))$dbName=$aliasName;
			$queryStr=$queryStr.$dbName.' AS '.$aliasName;}}
	else$queryStr = $queryStr.$columns;
	$queryStr=$queryStr.' FROM '.$fromTable.' WHERE ';
	$wherePart=buildWherePart($whereCondition,$parameters);
	if(!empty($limit))$wherePart=$wherePart.' LIMIT '.$limit;
	$queryStr=$queryStr.$wherePart;
	return$queryStr;}
function buildColumnsValueList($columns){
	$queryStr='';
	$firstColumn=TRUE;
	foreach($columns as$dbName=>$value){
		if(!$firstColumn)$queryStr=$queryStr.',';
		else$firstColumn=FALSE;
		if(strlen($value))$columnValue = '\''.val::$connection->real_escape_string($value).'\'';
		else$columnValue='DEFAULT';
		$queryStr=$queryStr.$dbName.'='.$columnValue;}
	return$queryStr;}
function buildWherePart($whereCondition,$parameters){
	$maskedParameters=prepareParameters($parameters);
	return vsprintf($whereCondition,$maskedParameters);}
function prepareParameters($parameters){
	if(!is_array($parameters))$parameters=[$parameters];
	$arrayLength=count($parameters);
	for($i=0;$i<$arrayLength;++$i)$parameters[$i]=val::$connection->real_escape_string(@trim($parameters[$i]));
	return$parameters;}
function executeQuery($queryStr){
	$queryResult=val::$connection->query($queryStr);
	if (!$queryResult)throw new Exception('Database Query Error: '.val::$connection->error);
	return$queryResult;}}
/**
 * Data Base Connection class.
 *
 * @author Ingo Hofmann
 */
function DbConnection_class(){
class DbConnection{
	public $connection;
	private static $_instance;
	private $_queryCache;
	static function getInstance(){
		if(self::$_instance==NULL)self::$_instance=new DbConnection();
		return self::$_instance;}
	function connect($host,$user,$password,$dbname){
		@$this->connection=new mysqli($host,$user,$password,$dbname);
		@$this->connection->set_charset('utf8');
		if(mysqli_connect_error())throw new Exception('Database Connection Error ('.mysqli_connect_errno().') '.mysqli_connect_error());}
	function close(){
		$this->connection->close();}
	function querySelect($columns,$fromTable,$whereCondition,$parameters=NULL,$limit=NULL){
		$queryStr=$this->buildQueryString($columns,$fromTable,$whereCondition,$parameters,$limit);
		return $this->executeQuery($queryStr);}
	function queryCachedSelect($columns,$fromTable,$whereCondition,$parameters=NULL,$limit=NULL){
		$queryStr=$this->buildQueryString($columns,$fromTable,$whereCondition,$parameters,$limit);
		if(isset($this->_queryCache[$queryStr]))return$this->_queryCache[$queryStr];
		$result=$this->executeQuery($queryStr);
		$rows=[];
		while($row=$result->fetch_array())$rows[]=$row;
		$result->free();
		$this->_queryCache[$queryStr]=$rows;
		return$rows;}
	function queryUpdate($columns,$fromTable,$whereCondition,$parameters){
		$queryStr='UPDATE '.$fromTable.' SET ';
		$queryStr=$queryStr.self::buildColumnsValueList($columns);
		$queryStr=$queryStr.' WHERE ';
		$wherePart=self::buildWherePart($whereCondition, $parameters);
		$queryStr=$queryStr.$wherePart;
		$this->executeQuery($queryStr);
		$this->_queryCache=[];}
	function queryDelete($fromTable,$whereCondition,$parameters){
		$queryStr='DELETE FROM '.$fromTable;
		$queryStr=$queryStr.' WHERE ';
		$wherePart=self::buildWherePart($whereCondition,$parameters);
		$queryStr=$queryStr.$wherePart;
		$this->executeQuery($queryStr);
		$this->_queryCache=[];}
	function queryInsert($columns,$fromTable){
		$queryStr='INSERT '.$fromTable.' SET ';
		$queryStr=$queryStr.$this->buildColumnsValueList($columns);
		$this->executeQuery($queryStr);}
	function getLastInsertedId(){
		return$this->connection->insert_id;}
	private function buildQueryString($columns,$fromTable,$whereCondition,$parameters=NULL,$limit=NULL){
		$queryStr='SELECT ';
		if (is_array($columns)){
			$firstColumn=TRUE;
			foreach($columns as$dbName=>$aliasName){
				if(!$firstColumn)$queryStr=$queryStr.', ';
				else$firstColumn=FALSE;
				if(is_numeric($dbName))$dbName=$aliasName;
				$queryStr=$queryStr.$dbName.' AS '.$aliasName;}}
		else$queryStr=$queryStr.$columns;
		$queryStr=$queryStr.' FROM '.$fromTable.' WHERE ';
		$wherePart=self::buildWherePart($whereCondition,$parameters);
		if(!empty($limit))$wherePart=$wherePart.' LIMIT '.$limit;
		$queryStr=$queryStr.$wherePart;
		return$queryStr;}
	private function buildColumnsValueList($columns){
		$queryStr='';
		$firstColumn=TRUE;
		foreach($columns as$dbName=>$value){
			if(!$firstColumn)$queryStr=$queryStr.', ';
			else$firstColumn=FALSE;
			if(strlen($value))$columnValue='\''.$this->connection->real_escape_string($value).'\'';
			else$columnValue='DEFAULT';
			$queryStr=$queryStr.$dbName.'='.$columnValue;}
		return$queryStr;}
	private function buildWherePart($whereCondition,$parameters){
		$maskedParameters=self::prepareParameters($parameters);
		return vsprintf($whereCondition,$maskedParameters);}
	function prepareParameters($parameters){
		if(!is_array($parameters))$parameters=[$parameters];
		$arrayLength=count($parameters);
		for($i=0;$i<$arrayLength;$i++)$parameters[$i]=$this->connection->real_escape_string(trim($parameters[$i]));
		return$parameters;}
	function executeQuery($queryStr){
		$queryResult=$this->connection->query($queryStr);
		if(!$queryResult)throw new Exception('Database Query Error: '.$this->connection->error);
		return$queryResult;}}
function I18n(){
define('PAGE_NAV_LABEL_SUFFIX','_navlabel');
define('LANG_SESSION_PARAM','lang');
/**
 * Use the native PHP function instead the instance I18n.
 *
 * @author Rolf Joseph
 */
/**
 * Handles internationalization tasks,
 * as native PHP functions.
 *
 * @author Rolf Joseph
 */
function I18n_functions(){
function getSupportedLanguages(){
	return $supportedLanguages=array_map('trim',explode(',',getConfig("supported_languages")));}
function getCurrentLanguage(){
	if($currentLanguage==0){
		if(isset($_SESSION[LANG_SESSION_PARAM]))$lang=$_SESSION[LANG_SESSION_PARAM];
		elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))$lang=strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2));
		else$lang=$supportedLanguages[0];
		if(!in_array($lang,$supportedLanguages))$lang=$supportedLanguages[0];
		$currentLanguage=$lang;}}
function setCurrentLanguage($language){
	if($language==$currentLanguage)return;
	$lang=strtolower($language);
	if(!in_array($lang,$supportedLanguages))$lang=CurrentLanguage();
	$_SESSION['lang']=$lang;
	$currentLanguage=$lang;}
function getMessage($messageKey,$paramaters=null){
	global $msg;
	if(!hasMessage($messageKey)){return '???'.$messageKey.'???';}
	$message=stripslashes($msg[$messageKey]);
	if($paramaters!=null){$message=sprintf($message,$paramaters);}
	return $message;}
function hasMessage($messageKey){
	global $msg;
	return isset($msg[$messageKey]);}
function getNavigationLabel($pageId){
	return Message($pageId.'_navlabel');}}
/**
 * Handles internationalization tasks.
 *
 * @author Ingo Hofmann
 */
function I18n_class(){
class I18n{
	private static $_instance;
	private $_currentLanguage;
	private $_supportedLanguages;
	static function getInstance($supportedLanguages){
		if(self::$_instance==NULL)self::$_instance=new I18n($supportedLanguages);
		return self::$_instance;}
	function getCurrentLanguage(){
		if($this->_currentLanguage==NULL){
			if (isset($_SESSION[LANG_SESSION_PARAM]))$lang=$_SESSION[LANG_SESSION_PARAM];
			elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))$lang=strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2));
			else$lang=$this->_supportedLanguages[0];
			if(!in_array($lang,$this->_supportedLanguages))$lang=$this->_supportedLanguages[0];
			$this->_currentLanguage=$lang;}
		return$this->_currentLanguage;}
	function setCurrentLanguage($language){
		if($language==$this->_currentLanguage)return;
		$lang=strtolower($language);
		if(!in_array($lang,$this->_supportedLanguages))$lang=$this->getCurrentLanguage();
		$_SESSION[LANG_SESSION_PARAM]=$lang;
		$this->_currentLanguage=$lang;}
	function getMessage($messageKey,$paramaters=NULL){
		global$msg;
		if(!$this->hasMessage($messageKey))return'???'.$messageKey.'???';
		$message=stripslashes($msg[$messageKey]);
		if($paramaters!=NULL)$message=sprintf($message,$paramaters);
		return$message;}
	function hasMessage($messageKey){
		global$msg;
		return isset($msg[$messageKey]);}
	function getNavigationLabel($pageId){
		return$this->getMessage($pageId.PAGE_NAV_LABEL_SUFFIX);}
	function getSupportedLanguages(){
		return$this->_supportedLanguages;}
	private function __construct($supportedLanguages){
		$this->_supportedLanguages=array_map('trim',explode(',',$supportedLanguages));}}
}}}}}}}