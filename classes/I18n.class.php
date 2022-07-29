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
	return Message($pageId.'_navlabel');}
/**
 * Handles internationalization tasks.
 *
 * @author Ingo Hofmann
 */
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
		global $msg;
		return isset($msg[$messageKey]);}
	function getNavigationLabel($pageId){
		return$this->getMessage($pageId.PAGE_NAV_LABEL_SUFFIX);}
	function getSupportedLanguages(){
		return$this->_supportedLanguages;}
	private function __construct($supportedLanguages){
		$this->_supportedLanguages=array_map('trim',explode(',',$supportedLanguages));}}