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
 * Data Base Connection class.
 * As native PHP functions.
 *
 * @author Rolf Joseph
 */
function connect($host,$user,$password,$dbname){
	val::$connection=new mysqli($host,$user,$password,$dbname);
	val::$connection->set_charset('utf8');
	if(mysqli_connect_error()){throw new Exception('Die Datenbank ist zur Zeit nicht verf�gbar: ('.mysqli_connect_errno().') '.mysqli_connect_error());}}
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
	return$queryResult;}
/**
 * Data Base Connection class.
 *
 * @author Ingo Hofmann
 */
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
