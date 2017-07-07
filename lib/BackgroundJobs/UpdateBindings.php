<?php
/**
 * @copyright Copyright (c) 2017 Roberto Guido <bob@linux.it>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\DDNSClient\BackgroundJobs;

use OC\BackgroundJob\TimedJob;
use OCA\DDNSClient\Db\ClientMapper;

class UpdateBindings extends TimedJob {

	/**
	 * UpdateBindings constructor.
	 */
	public function __construct(ClientMapper $clientMapper) {
		$this->clientMapper = $clientMapper;
		$this->setInterval(60 * 10);
	}

	protected function run($argument) {
		$clients = $this->clientMapper->getClients();

		foreach($clients as $client) {
			$client->updateBinding();
			$this->clientMapper->update($client);
		}
	}
}