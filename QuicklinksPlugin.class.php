<?php
require 'bootstrap.php';

/**
 * QuicklinksPlugin.class.php
 *
 * @author  Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @version 0.4
 */

class QuicklinksPlugin extends StudIPPlugin implements SystemPlugin {

    function __construct() {
        parent::__construct();

        $navigation = new Navigation('Quicklinks');
        $navigation->setURL(PluginEngine::getURL($this, array(
            'link'  => $_SERVER['REQUEST_URI'],
            'title' => PageLayout::getTitle(),
        ), 'links'));
        Navigation::insertItem('/links/quick', $navigation, 'logout');

        PageLayout::addStylesheet($this->getPluginURL().'/assets/quicklinks.sass');
        $this->addCoffeescript('patch');
        
        PageLayout::addHeadElement('script', array(), 'STUDIP.Quicklinks = '.json_encode(array(
            'api' => PluginEngine::getLink($this, array(), 'ajax/METHOD'),
            'uri' => $_SERVER['REQUEST_URI'],
            'id'  => Quicklink::FindLink($GLOBALS['auth']->auth['uid'], $_SERVER['REQUEST_URI']),
            'links' => Quicklink::LoadAll($GLOBALS['auth']->auth['uid']),
        )).';');
    }

    function initialize () {
        Navigation::getItem('/links/quick')->setURL(PluginEngine::getLink($this, array(), 'links'));
        $this->addCoffeescript('studip-modal');
    }

    function perform($unconsumed_path) {
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'links'
        );
        $dispatcher->dispatch($unconsumed_path);
    }

    private function addCoffeescript($script, $extension = '.coffee') {
        $script = basename($script, $extension) . $extension;
        PageLayout::addHeadElement('script', array(
            'src'  => $this->getPluginURL() . '/assets/' . $script,
            'type' => 'text/coffeescript'
        ), '');
    }
}
