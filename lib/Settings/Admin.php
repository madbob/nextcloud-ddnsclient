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

namespace OCA\DDNSClient\Settings;

use OCA\DDNSClient\Db\ClientMapper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

class Admin implements ISettings {
	/** @var ClientMapper */
	private $clientMapper;

	/**
	 * @param ClientMapper $clientMapper
	 */
	public function __construct(ClientMapper $clientMapper) {
		$this->clientMapper = $clientMapper;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm() {
		return new TemplateResponse(
			'ddnsclient',
			'admin',
			[
				'clients' => $this->clientMapper->getClients(),
			],
			''
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSection() {
		return 'sharing';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority() {
		return 80;
	}
}
