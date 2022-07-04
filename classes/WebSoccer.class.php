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
/**
 * Core functions and application context state of the current request,
 * as native PHP functions, without to use a class of websoccer instance.
 *
 * @author Rolf Joseph
 */
class val{
	static $user,$skin,$pageId,$templateEngine,$_frontMessages,$_isAjaxRequest,$_contextParameters;}
function getUser(){
	if(val::$user==null)val::$user=new User();
	return val::$user;}
function getConfig($name){
	global $conf;
	if(!isset($conf[$name]))throw new Exception('Konfigurationseintrag wurde nicht gefunden: '.$name);
	return (string)$conf[$name];}
function getAction($id){
	global $action;
	if(!isset($action[$id]))throw new Exception('Action not found: '.$id);
	return $action[$id];}
function getSkin(){
	if(val::$skin==NULL){
		$skinName=Config('skin');
		if(class_exists($skinName))val::$skin=new $skinName(val::$skin);
		else throw new Exception('Configured skin \''.$skinName.'\' does not exist. Check the system settings.');}
	return val::$skin;}
function getPageId(){return val::$pageId;}
function setPageId($pageId){val::$pageId=$pageId;}
function getTemplateEngine($i18n,ViewHandler $viewHandler=null){
	if(val::$templateEngine==NULL)val::$templateEngine=new TemplateEngine(val::$instance,$i18n,$viewHandler);
	return val::$templateEngine;}
function getRequestParameter($name){
	if(isset($_REQUEST[$name])){
		$value=trim($_REQUEST[$name]);
		if(strlen((string)$value)){return $value;}}
	return NULL;}
function getInternalUrl($pageId=NULL,$queryString='',$fullUrl=FALSE){
	if($pageId==NULL)$pageId=PageId();
	if(strlen((string)$queryString))$queryString='&'.$queryString;
	if($fullUrl){
		$url=Config('homepage').Config('context_root');
		if($pageId!='home'||strlen((string)$queryString))$url.='/?page='.$pageId.$queryString;}
	else $url=Config('context_root').'/?page='.$pageId.$queryString;
	return $url;}
function getInternalActionUrl($actionId,$queryString='',$pageId=NULL,$fullUrl=FALSE){
	if($pageId==NULL)$pageId=Request('page');
	if(strlen((string)$queryString)){$queryString='&'.$queryString;}
	$url=Config('context_root').'/?page='.$pageId.$queryString.'&action='.$actionId;
	if($fullUrl)$url=Config('homepage').$url;
	return $url;}
function getFormattedDate($timestamp=NULL){
	if($timestamp==NULL)$timestamp=getNowAsTimestamp();
	return date(Config('date_format'),$timestamp);}
function getFormattedDatetime($timestamp,I18n $i18n=NULL){
	if($timestamp==NULL)$timestamp=getNowAsTimestamp();
	if($i18n!=NULL){
		$dateWord=StringUtil::convertTimestampToWord($timestamp,getNowAsTimestamp(),$i18n);
		if(strlen((string)$dateWord))return $dateWord.','.date(Config('time_format'),$timestamp);}
	return date(Config('datetime_format'),$timestamp);}
function getNowAsTimestamp(){return time()+ Config('time_offset');}
function resetConfigCache(){
	$i18n=I18n::getInstance(Config('supported_languages'));
	$cacheBuilder=new ConfigCacheFileWriter($i18n->getSupportedLanguages());
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
	if(val::$_contextParameters==null)$_contextParameters=[];
	return val::$_contextParameters;}
function addContextParameter($name,$value){
	if(val::$_contextParameters==null)val::$_contextParameters=[];
	val::$_contextParameters[$name]=$value;}
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
    	if($this->_user==null)$this->_user=new User();
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
			$skinName=$this->getConfig('skin');
			if(class_exists($skinName))$this->_skin=new $skinName($this);
			else throw new Exception('Configured skin \''.$skinName.'\' does not exist. Check the system settings.');}
		return$this->_skin;}
	function getPageId(){
		return$this->_pageId;}
	function setPageId($pageId){
		$this->_pageId=$pageId;}
	function getTemplateEngine($i18n,ViewHandler$viewHandler=null){
		if($this->_templateEngine==NULL)$this->_templateEngine=new TemplateEngine($this,$i18n,$viewHandler);
		return$this->_templateEngine;}
	function getRequestParameter($name){
		if (isset($_REQUEST[$name])){
			$value=trim($_REQUEST[$name]);
			if(strlen($value))return$value;}
		return NULL;}
	function getInternalUrl($pageId=null,$queryString='',$fullUrl=FALSE){
		if($pageId==null)$pageId=$this->getPageId();
		if(strlen($queryString))$queryString='&'.$queryString;
		if($fullUrl){
			$url=$this->getConfig('homepage').$this->getConfig('context_root');
			if($pageId!='home'||strlen($queryString))$url .='/?page='.$pageId.$queryString;}
		else$url=$this->getConfig('context_root').'/?page='.$pageId.$queryString;
		return$url;}
	function getInternalActionUrl($actionId,$queryString='',$pageId=null,$fullUrl=FALSE){
		if($pageId==null)$pageId=$this->getRequestParameter('page');
		if(strlen($queryString))$queryString='&'.$queryString;
		$url=$this->getConfig('context_root').'/?page='.$pageId.$queryString.'&action='.$actionId;
		if($fullUrl)$url=$this->getConfig('homepage').$url;
		return$url;}
	function getFormattedDate($timestamp=null){
		if($timestamp==null)$timestamp=$this->getNowAsTimestamp();
		return date($this->getConfig('date_format'),$timestamp);}
	function getFormattedDatetime($timestamp,I18n$i18n=null){
		if($timestamp==null)$timestamp=$this->getNowAsTimestamp();
		if($i18n!=null){
			$dateWord=StringUtil::convertTimestampToWord($timestamp,$this->getNowAsTimestamp(),$i18n);
			if(strlen($dateWord))return$dateWord.', '.date($this->getConfig('time_format'),$timestamp);}
		return date($this->getConfig('datetime_format'),$timestamp);}
	function getNowAsTimestamp(){
		return time()+$this->getConfig('time_offset');}
	function resetConfigCache(){
		$i18n=I18n::getInstance($this->getConfig('supported_languages'));
		$cacheBuilder=new ConfigCacheFileWriter($i18n->getSupportedLanguages());
		$cacheBuilder->buildConfigCache();}
	function addFrontMessage(FrontMessage $message){
		$this->_frontMessages[]=$message;}
	function getFrontMessages(){
		if($this->_frontMessages==null)$this->_frontMessages=[];
		return$this->_frontMessages;}
	function setAjaxRequest($isAjaxRequest){
		$this->_isAjaxRequest=$isAjaxRequest;}
	function isAjaxRequest(){
		return$this->_isAjaxRequest;}
	function getContextParameters(){
		if($this->_contextParameters==null)$this->_contextParameters=[];
		return$this->_contextParameters;}
	function addContextParameter($name,$value){
		if($this->_contextParameters==null)$this->_contextParameters=[];
		$this->_contextParameters[$name]=$value;}}