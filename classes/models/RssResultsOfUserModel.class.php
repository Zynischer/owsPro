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
 * Provides RSS items for the latest results of user's teams.
 * Since it is a publixc page, the user ID and language must be provided
 * with request parameters <code>id</code> (User-Id) and <code>lang</code>.
 */
class RssResultsOfUserModel implements IModel {
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
		
		$userId = (int) $this->_websoccer->getRequestParameter('id');
		$matches = MatchesDataService::getLatestMatchesByUser($this->_websoccer, $this->_db, $userId);
		
		$items = array();
		
		foreach ($matches as $match) {
			$items[] = array(
					'url' => $this->_websoccer->getInternalUrl('match', 'id=' . $match['id'], TRUE),
					'title' => $match['home_team'] . ' - ' . $match['guest_team'] . ' (' . $match['home_goals'] . ':' . $match['guest_goals'] . ')',
					'date' => gmdate(DATE_RSS, $match['date'])
					);
		}
		
		return array('items' => $items);
	}
	
}

?>