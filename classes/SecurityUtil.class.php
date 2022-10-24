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
class SecurityUtil{
	static function hashPassword($password,$salt){
		return hash('sha256',$salt.hash('sha256',$password));}
	static function isAdminLoggedIn(){
		if(isset($_SESSION['HTTP_USER_AGENT'])){
			if($_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['HTTP_USER_AGENT'])){
				self::logoutAdmin();
				return FALSE;}}
		else{
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);}
		return(isset($_SESSION['valid'])&&$_SESSION['valid']);}
	static function logoutAdmin(){
		$_SESSION=[];
		session_destroy();}
	static function generatePassword(){
		$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789%!=?';
		return substr(str_shuffle($chars),0,8);}
	static function generatePasswordSalt(){
		return substr(self::generatePassword(),0,4);}
	static function generateSessionToken($userId, $salt){
		$useragent=(isset($_SESSION['HTTP_USER_AGENT']))?$_SESSION['HTTP_USER_AGENT']:'n.a.';
		return md5($salt.$useragent.$userId);}
	static function loginFrontUserUsingApplicationSession(WebSoccer $websoccer,$userId){
		$_SESSION['frontuserid']=$userId;
		session_regenerate_id();
		$userProvider=new SessionBasedUserAuthentication($websoccer);
		$userProvider->verifyAndUpdateCurrentUser($websoccer->getUser());}
}