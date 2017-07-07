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

namespace OCA\DDNSClient\Db;

use OCP\AppFramework\Db\Entity;

use ManyDNS\ManyDNS;
use ManyDNS\FailedUpdateException;

/**
 * @method string getProvider()
 * @method void setProvider(string $provider)
 * @method string getHostname()
 * @method void setHostname(string $hostname)
 * @method string getUsername()
 * @method void setUsername(string $username)
 * @method string getPassword()
 * @method void setPassword(string $password)
 * @method string getStatus()
 * @method void setStatus(string $status)
 */
class Client extends Entity {
	/** @var string */
	protected $provider;
	/** @var string */
	protected $hostname;
	/** @var string */
	protected $username;
	/** @var string */
	protected $password;
	/** @var string */
	protected $status;

	public function __construct() {
		$this->addType('id', 'int');
		$this->addType('provider', 'string');
		$this->addType('hostname', 'string');
		$this->addType('username', 'string');
		$this->addType('password', 'string');
		$this->addType('status', 'string');
	}

	public function updateBinding()
	{
		$provider = ManyDNS::getProvider($this->provider);
		if ($provider == null) {
			\OC::$server->getLogger()->error('Unable to find provider ' . $this->provider);
			$this->setStatus('Invalid Provider');
		}
		else {
			try {
				$provider->updateNow($this->username, $this->password, $this->hostname);
				\OC::$server->getLogger()->info('Updated IP binding to ' . $this->provider);
				$this->setStatus('OK');
			}
			catch(FailedUpdateException $e) {
				\OC::$server->getLogger()->error('Error while updating IP binding to ' . $this->provider);
				$this->setStatus($e->getMessage());
			}
		}
	}
}
