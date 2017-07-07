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

use ManyDNS\ManyDNS;

$urlGenerator = \OC::$server->getURLGenerator();
$themingDefaults = \OC::$server->getThemingDefaults();

script('ddnsclient', 'setting-admin');
style('ddnsclient', 'setting-admin');

/** @var array $_ */
/** @var \OCA\DDNSClient\Db\Client[] $clients */
$clients = $_['clients'];
?>

<div id="ddnsclient" class="section">
	<h2><?php p($l->t('Dynamic DNS Providers')); ?></h2>
	<p class="settings-hint"><?php p($l->t('Dynamic DNS configurations to easily access your local %s.', [$themingDefaults->getName()])); ?></p>

	<table class="grid">
		<thead>
		<tr>
			<th id="headerProvider" scope="col"><?php p($l->t('Provider')); ?></th>
			<th id="headerHostname" scope="col"><?php p($l->t('Hostname')); ?></th>
			<th id="headerUsername" scope="col"><?php p($l->t('Username')); ?></th>
			<th id="headerPassword" scope="col"><?php p($l->t('Password')); ?></th>
			<th id="headerPassword" scope="col"><?php p($l->t('Status')); ?></th>
			<th id="headerRemove">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$imageUrl = $urlGenerator->imagePath('core', 'actions/toggle.svg');
		foreach ($clients as $client) {
		?>
			<tr>
				<td><?php p($client->getProvider()); ?></td>
				<td><?php p($client->getHostname()); ?></td>
				<td><?php p($client->getUsername()); ?></td>
				<td data-value="<?php p($client->getPassword()); ?>"><code>****</code><img class='show-ddnsclient-credentials' src="<?php p($imageUrl); ?>"/></td>
				<td><?php p($client->getStatus()); ?></td>
				<td>
					<form id="form-inline" class="delete" action="<?php p($urlGenerator->linkToRoute('ddnsclient.Settings.deleteClient', ['id' => $client->getId()])); ?>" method="POST">
						<input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']) ?>" />
						<input type="submit" class="button icon-delete" value="">
					</form>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	<br/>
	<h3><?php p($l->t('Add provider')); ?></h3>
	<form action="<?php p($urlGenerator->linkToRoute('ddnsclient.Settings.addClient')); ?>" method="POST">
		<select id="provider" name="provider">
			<?php foreach(ManyDNS::getProviders() as $provider): ?>
			<option value="<?php p($provider->getName()) ?>"><?php p($provider->getName()) ?></option>
			<?php endforeach ?>
		</select>
		<input type="text" id="hostname" name="hostname" placeholder="<?php p($l->t('Hostname')); ?>">
		<input type="text" id="username" name="username" placeholder="<?php p($l->t('Username')); ?>">
		<input type="password" id="password" name="password" placeholder="<?php p($l->t('Password')); ?>">
		<input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']) ?>" />
		<input type="submit" class="button" value="<?php p($l->t('Add')); ?>">
	</form>
</div>
