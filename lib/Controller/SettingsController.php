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

namespace OCA\DDNSClient\Controller;

use OCA\DDNSClient\Db\Client;
use OCA\DDNSClient\Db\ClientMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\BackgroundJob\IJobList;
use OCP\IRequest;
use OCP\IURLGenerator;

class SettingsController extends Controller {
	private $urlGenerator;
	private $clientMapper;
	private $jobList;

	public function __construct($appName,
					IRequest $request,
					IURLGenerator $urlGenerator,
					ClientMapper $clientMapper,
					IJobList $jobList
	) {
		parent::__construct($appName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->clientMapper = $clientMapper;
		$this->jobList = $jobList;
	}

	public function addClient($provider, $hostname, $username, $password) {
		$client = new Client();
		$client->setProvider($provider);
		$client->setHostname($hostname);
		$client->setUsername($username);
		$client->setPassword($password);
		$client->setStatus('Registering...');
		$this->clientMapper->insert($client);

		if ($this->jobList->has('OCA\DDNSClient\BackgroundJobs\UpdateBindings', null) == false) {
			$this->jobList->add('OCA\DDNSClient\BackgroundJobs\UpdateBindings');
		}

		$client->updateBinding();
		$this->clientMapper->update($client);

		return new RedirectResponse($this->urlGenerator->getAbsoluteURL('/index.php/settings/admin/sharing'));
	}

	public function deleteClient($id) {
		$client = $this->clientMapper->getByUid($id);
		$this->clientMapper->delete($client);

		$clients = $this->clientMapper->getClients();
		if (empty($clients))
			$this->jobList->remove('OCA\DDNSClient\BackgroundJobs\UpdateBindings');

		return new RedirectResponse($this->urlGenerator->getAbsoluteURL('/index.php/settings/admin/sharing'));
	}
}
