<?php

/**
 * @file plugins/generic/disqus/DisqusSettingsForm.inc.php
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class DisqusSettingsForm
 * @ingroup plugins_generic_disqus
 *
 * @brief Form for journal managers to modify disqus plugin settings
 */


import('form.Form');

class DisqusSettingsForm extends Form {

	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $journalId int
	 */
	function DisqusSettingsForm(&$plugin, $journalId) {
		$this->journalId = $journalId;
		$this->plugin = &$plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		$this->addCheck(new FormValidator($this, 'disqusShortname', 'required', 'plugins.generic.disqus.manager.settings.disqusShortnameRequired'));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin = &$this->plugin;

		$this->_data = array(
			'disqusShortname' => $plugin->getSetting($journalId, 'disqusShortname')
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('disqusShortname'));
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin = &$this->plugin;
		$journalId = $this->journalId;

		$plugin->updateSetting($journalId, 'disqusShortname', $this->getData('disqusShortname'), 'string');
	}
}

?>
