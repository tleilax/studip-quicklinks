<?php
class LinksController extends QuicklinksController
{
    function before_filter(&$action, &$args)
    {
        PageLayout::setTitle(_('Quicklink Verwaltung'));
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));

        parent::before_filter($action, $args);

        $this->user_id = $GLOBALS['auth']->auth['uid'];
        $this->quick_links = Quicklink::GetInstance($this->user_id);
    }

    function index_action()
    {
        $this->links = $this->quick_links->loadAll();

        $this->setInfoboxImage('infobox/administration.jpg');
        $this->addToInfobox(_('Aktionen'),
                            sprintf('<a data-behaviour="modal" href="%s">%s</a>', $this->url_for('links/edit'), _('Neuen Link eintragen')),
                            'icons/16/black/plus.png');
    }

    function edit_action ($id = null)
    {
        if (Request::submitted('store')) {
            $errors = array();

            if (!Request::get('link')) {
                $errors[] = 'Link muss angegeben werden';
            }
            if (!Request::get('title')) {
                $errors[] = 'Titel muss angegeben werden';
            }

            if (empty($errors)) {
                $this->quick_links->store($id, Request::get('link'), Request::get('title'));
                PageLayout::postMessage(Messagebox::success(_('Der Link wurde gespeichert.')));
                $this->redirect('links/index');
            } else {
                PageLayout::postMessage(Messagebox::error(_('Es sind Fehler aufgetreten.'), $errors));
            }
        }

        if ($id) {
            $this->link = $this->quick_links->load($id);
        }
    }

    function move_action($id, $direction)
    {
        $this->quick_links->move($id, $direction);
        if (!Request::isAjax()) {
            PageLayout::postMessage(Messagebox::success(_('Der Link wurde verschoben.')));
        }
        $this->redirect('links/index');
    }

    function delete_action($id)
    {
        $this->quick_links->remove($id);
        if (!Request::isAjax()) {
            PageLayout::postMessage(Messagebox::success(_('Der Link wurde gelöscht.')));
        }
        $this->redirect('links/index');
    }
    
    function bulk_action()
    {
        $action = Request::option('action');
        $ids    = Request::optionArray('ids');
        if (in_array('all', $ids)) {
            $ids = $this->quick_links->loadAllIds();
        }
        if ($action === 'delete') {
            array_map(array($this->quick_links, 'remove'), $ids);

            $count   = count($ids);
            $message = sprintf(ngettext('Ein Link wurde gelöscht', '%u Links wurden gelöscht', $count), $count);
            PageLayout::postMessage(MessageBox::success($message));
        }
        $this->redirect('links/index');
    }
}
