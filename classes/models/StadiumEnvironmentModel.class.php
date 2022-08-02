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
 * Provides available and built buildings of current club.
 */
class StadiumEnvironmentModel implements IModel {
	private $_db;
	private $_i18n;
	private $_websoccer;

	public function __construct($db, $i18n, $websoccer) {
		$this->_db = $db;
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
	}

	/**
	 * (non-PHPdoc)
	 * @see IModel::renderView()
	 */
	public function renderView() {
		return TRUE;
	}

	/**
	 * (non-PHPdoc)
	 * @see IModel::getTemplateParameters()
	 */
	public function getTemplateParameters() {

		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) {
			throw new Exception(getMessage("feature_requires_team"));
		}

		$dbPrefix = getConfig('db_prefix');

		// get existing buildings
		$existingBuildings = array();
		$result = $this->_db->querySelect('*', $dbPrefix . '_buildings_of_team INNER JOIN '. $dbPrefix . '_stadiumbuilding ON id = building_id',
				'team_id = %d ORDER BY construction_deadline DESC', $teamId);
		$now = getNowAsTimestamp();
		while ($building = $result->fetch_array()) {
			$building['under_construction'] = $now < $building['construction_deadline'];
			$existingBuildings[] = $building;
		}
		$result->free();

		// get available buildings
		$availableBuildings = array();
		$result = $this->_db->querySelect('*', $dbPrefix . '_stadiumbuilding',
				'id NOT IN (SELECT building_id FROM ' . $dbPrefix . '_buildings_of_team WHERE team_id = %d) ' .
				' AND (required_building_id IS NULL OR required_building_id IN (SELECT building_id FROM ' . $dbPrefix . '_buildings_of_team WHERE team_id = %d AND construction_deadline < %d))' .
				' ORDER BY name ASC', array($teamId, $teamId, $now));
		while ($building = $result->fetch_array()) {

			// i18n of name and description
			if (hasMessage($building['name'])) {
				$building['name'] = getMessage($building['name']);
			}
			if (hasMessage($building['description'])) {
				$building['description'] = getMessage($building['description']);
			}
			$availableBuildings[] = $building;
		}
		$result->free();

		return array('existingBuildings' => $existingBuildings, 'availableBuildings' => $availableBuildings);
	}

}

?>