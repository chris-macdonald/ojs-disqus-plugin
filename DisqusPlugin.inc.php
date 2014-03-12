<?php

/**
 * @file plugins/generic/disqus/DisqusPlugin.inc.php
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class DisqusPlugin
 * @ingroup plugins_generic_disqus
 *
 * @brief Disqus plugin class
 */


import('lib.pkp.classes.plugins.GenericPlugin');

class DisqusPlugin extends GenericPlugin {

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
		if ($success && $this->getEnabled()) {
			// Insert Disqus page tag to article footer
			HookRegistry::register('Templates::Article::Footer::PageFooter', array($this, 'insertFooter'));

			// Insert Disqus page tag to article interstitial footer
			HookRegistry::register('Templates::Article::Interstitial::PageFooter', array($this, 'insertFooter'));

			// Insert Disqus page tag to article pdf interstitial footer
			HookRegistry::register('Templates::Article::PdfInterstitial::PageFooter', array($this, 'insertFooter'));

			// Insert Disqus page tag to reading tools footer
			//HookRegistry::register('Templates::Rt::Footer::PageFooter', array($this, 'insertFooter'));
		}
		return $success;
	}

	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category, and should be suitable for part of a filename
	 * (ie short, no spaces, and no dependencies on cases being unique).
	 * @return String name of plugin
	 */
	function getName() {
		return 'DisqusPlugin';
	}

	function getDisplayName() {
		return __('plugins.generic.disqus.displayName');
	}

	function getDescription() {
		return __('plugins.generic.disqus.description');
	}

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array(
				'disable',
				__('manager.plugins.disable')
			);
			$verbs[] = array(
				'settings',
				__('plugins.generic.disqus.manager.settings')
			);
		} else {
			$verbs[] = array(
				'enable',
				__('manager.plugins.enable')
			);
		}
		return $verbs;
	}

	/**
	 * Determine whether or not this plugin is enabled.
	 */
	function getEnabled() {
		$journal =& Request::getJournal();
		if (!$journal) return false;
		return $this->getSetting($journal->getId(), 'enabled');
	}

	/**
	 * Set the enabled/disabled state of this plugin
	 */
	function setEnabled($enabled) {
		$journal =& Request::getJournal();
		if ($journal) {
			$this->updateSetting($journal->getJournalId(), 'enabled', $enabled ? true : false);
			return true;
		}
		return false;
	}

	/**
	 * Insert Disqus page tag to footer
	 */
	function insertFooter($hookName, $params) {
		if ($this->getEnabled()) {
			$journal =& Request::getJournal();
			$journalId = $journal->getJournalId();
			$disqusShortname = $this->getSetting($journalId, 'disqusShortname');
			if (!empty($disqusShortname)) {
				$templateMgr =& TemplateManager::getManager();
				$templateMgr->assign('disqusShortname', $disqusShortname);
				$templateMgr->display($this->getTemplatePath() . 'disqus.tpl');
			}
		}
		return false;
	}

	/**
	 * Set the page's breadcrumbs, given the plugin's tree of items
	 * to append.
	 * @param $subclass boolean
	 */
	function setBreadcrumbs($isSubclass = false) {
		$templateMgr =& TemplateManager::getManager();
		$pageCrumbs = array(
			array(
				Request::url(null, 'user'),
				'navigation.user'
			),
			array(
				Request::url(null, 'manager'),
				'user.role.manager'
			)
		);
		if ($isSubclass) $pageCrumbs[] = array(
			Request::url(null, 'manager', 'plugins'),
			'manager.plugins'
		);

		$templateMgr->assign('pageHierarchy', $pageCrumbs);
	}

	/**
	 * Perform management functions
	 */
	function manage($verb, $args) {
		$templateMgr =& TemplateManager::getManager();
		$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));
		$journal =& Request::getJournal();
		$returner = true;

		switch ($verb) {
			case 'enable':
				$this->setEnabled(true);
				$returner = false;
				break;
			case 'disable':
				$this->setEnabled(false);
				$returner = false;
				break;
			case 'settings':
				if ($this->getEnabled()) {
					$this->import('DisqusSettingsForm');
					$form = new DisqusSettingsForm($this, $journal->getJournalId());
					if (Request::getUserVar('save')) {
						$form->readInputData();
						if ($form->validate()) {
							$form->execute();
							Request::redirect(null, 'manager', 'plugin');
						} else {
							$this->setBreadCrumbs(true);
							$form->display();
						}
					} else {
						$this->setBreadCrumbs(true);
						$form->initData();
						$form->display();
					}
				} else {
					Request::redirect(null, 'manager');
				}
				break;
			default:
				Request::redirect(null, 'manager');
		}
		return $returner;
	}
}
?>
