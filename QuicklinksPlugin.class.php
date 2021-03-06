<?php
require 'bootstrap.php';

/**
 * QuicklinksPlugin.class.php
 *
 * @author  Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @version 0.5
 */

class QuicklinksPlugin extends StudIPPlugin implements SystemPlugin
{

    function __construct()
    {
        parent::__construct();

        $navigation = new Navigation('Quicklinks');
        $navigation->setURL(PluginEngine::getURL($this, array(
            'link'  => $_SERVER['REQUEST_URI'],
            'title' => PageLayout::getTitle(),
        ), 'links'));
        Navigation::insertItem('/links/quick', $navigation, 'logout');

        $this->addStylesheet('assets/quicklinks.less');
        PageLayout::addScript($this->getPluginURL() . '/assets/patch.min.js');
        PageLayout::addScript($this->getPluginURL() . '/assets/html-additions.js');

        $quick_links = Quicklink::GetInstance($GLOBALS['auth']->auth['uid']);
        PageLayout::addHeadElement('script', array(), 'STUDIP.Quicklinks = ' . json_encode(array(
            'api'   => PluginEngine::getLink($this, array(), 'ajax/METHOD'),
            'uri'   => $_SERVER['REQUEST_URI'],
            'id'    => $quick_links->findLink($_SERVER['REQUEST_URI']),
            'links' => array_map('studip_utf8encode', $quick_links->loadAll()),
        )).';');
    }

    function initialize ()
    {
        Navigation::getItem('/links/quick')->setURL(PluginEngine::getLink($this, array(), 'links'));
    }

    function perform($unconsumed_path)
    {
        URLHelper::removeLinkParam('cid');

        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'links'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
}
