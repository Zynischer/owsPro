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
 * Providing base functionality for all Model-Classes.
 * Code minified, but clear for a better overview.
 *
 * Use example: class MyModell extends Model
 *
 * @author Rolf Joseph / ErdemCan
 */
class Model{
	// renderView to set allways on true, to show the template.
	function renderView(){return TRUE;}
	// getTemplateParameters as basic data for rendering the templae. Normaly we have to code to collect the nessara data.
	function getTemplateParameters(){return[];}}