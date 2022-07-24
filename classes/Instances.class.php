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
/**
 * Provision of basic functionality for all class instances referenced here.
 *
 * @author Rolf Joseph / ErdemCan
 */
class Instances{
    function __construct($db,$i18n,$websoccer){$this->_db=$db;$this->_i18n=$i18n;$this->_websoccer=$websoccer;}}