<?php
require 'bootstrap.php';

/**
 * QuicklinksPlugin.class.php
 *
 * This plugin uses janl's JS template engine "Mustache"
 * https://github.com/janl/mustache.js
 *
 * @author  Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @version 0.4
 */

class QuicklinksPlugin extends StudIPPlugin implements SystemPlugin {

	public function __construct() {
		parent::__construct();

		$navigation = new Navigation('Quicklinks');
		$navigation->setURL(PluginEngine::getURL($this, array(
			'link'  => $_SERVER['REQUEST_URI'],
			'title' => PageLayout::getTitle(),
		), 'links'));
		Navigation::insertItem('/links/quick', $navigation, 'logout');

		PageLayout::addStylesheet($this->getPluginURL().'/assets/quicklinks.css');
		
		PageLayout::addScript($this->getPluginURL().'/assets/mustache-0.4.0-dev.js');
		PageLayout::addScript($this->getPluginURL().'/assets/patch.js');
		PageLayout::addHeadElement('script', array(), 'STUDIP.Quicklinks = '.json_encode(array(
			'api' => PluginEngine::getLink($this, array(), 'ajax/METHOD'),
			'uri' => $_SERVER['REQUEST_URI'],
			'id'  => Quicklink::FindLink($GLOBALS['auth']->auth['uid'], $_SERVER['REQUEST_URI']),
			'links' => Quicklink::LoadAll($GLOBALS['auth']->auth['uid']),
		)).';');
	}

	public function initialize () {
		Navigation::getItem('/links/quick')->setURL(PluginEngine::getLink($this, array(), 'links'));
		PageLayout::addScript($this->getPluginURL().'/assets/script.js');
	}

	public function perform($unconsumed_path) {
		$dispatcher = new Trails_Dispatcher(
			$this->getPluginPath(),
			rtrim(PluginEngine::getLink($this, array(), null), '/'),
			'links'
		);
		$dispatcher->dispatch($unconsumed_path);
	}
}
