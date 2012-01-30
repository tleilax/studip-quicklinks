<?php
class LinksController extends AppController {

    public function before_filter(&$action, &$args) {

        PageLayout::setTitle(_('Quicklink Verwaltung'));
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        $this->user_id = $GLOBALS['auth']->auth['uid'];
        
        parent::before_filter($action, $args);
    }

    public function index_action() {
        $this->links = Quicklink::LoadAll($this->user_id);

        $this->setInfoboxImage('infobox/administration.jpg');
        $this->addToInfobox(_('Aktionen'),
                            sprintf('<a data-behaviour="modal" href="%s">%s</a>', $this->url_for('links/edit'), _('Neuen Link eintragen')),
                            'icons/16/black/plus.png');
    }

    public function edit_action ($id = null) {
        if (Request::submitted('store')) {
            $errors = array();

            if (!Request::get('link')) {
                $errors[] = 'Link muss angegeben werden';
            }
            if (!Request::get('title')) {
                $errors[] = 'Titel muss angegeben werden';
            }

            if (empty($errors)) {
                Quicklink::Save($this->user_id, $id, Request::get('link'), Request::get('title'));
                PageLayout::postMessage(Messagebox::success(_('Der Link wurde gespeichert.')));
                $this->redirect('links/index');     
            } else {            
                PageLayout::postMessage(Messagebox::error(_('Es sind Fehler aufgetreten.'), $errors));
            }
        }
        
        if ($id) {
            $this->link = Quicklink::Load($this->user_id, $id);
        }
    }
    
    public function move_action($id, $direction) {
        Quicklink::Move($this->user_id, $id, $direction);
        PageLayout::postMessage(Messagebox::success(_('Der Link wurde verschoben.')));
        $this->redirect('links/index');
    }
    
    public function delete_action($id) {
        Quicklink::Delete($this->user_id, $id);
        PageLayout::postMessage(Messagebox::success(_('Der Link wurde gelöscht.')));
        $this->redirect('links/index');
    }

}
