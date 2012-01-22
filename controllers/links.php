<?php
class LinksController extends AuthenticatedController {

    public function before_filter(&$action, &$args) {

		$this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox'));
		$this->user_id = $GLOBALS['auth']->auth['uid'];
//		PageLayout::setTitle('');

    }

    public function index_action() {
		$this->links = Quicklink::LoadAll($this->user_id);
    }

	public function edit_action ($id) {
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
				$this->flash['success'] = 'Der Link wurde gespeichert';
				$this->redirect('links/index');		
			} else {			
				$this->flash['error'] = implode(".\n", $errors);
			}
		}
		
		if ($id) {
			$this->link = Quicklink::Load($this->user_id, $id);
		}
	}
	
	public function move_action($id, $direction) {
		Quicklink::Move($this->user_id, $id, $direction);
		$this->flash['success'] = 'Der Link wurde verschoben';
		$this->redirect('links/index');
	}
	
	public function delete_action($id) {
		Quicklink::Delete($this->user_id, $id);
		$this->redirect('links/index');
	}

}
