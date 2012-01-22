<?php
class AjaxController extends AuthenticatedController {
    public function before_filter(&$action, &$args) {
		$this->user_id = $GLOBALS['auth']->auth['uid'];
    }

	public function remove_action($id) {
		Quicklink::Delete($this->user_id, $id);
		$this->render_text('{"id": false}');
	}

	public function store_action() {
		$link = Request::get('link');
		$title = Request::get('title');
		$title = preg_replace('/- '.preg_quote($GLOBALS['UNI_NAME_CLEAN'], '/').'$/', '', $title);
		$link_id = Quicklink::Save($this->user_id, null, $link, $title);
		$this->render_text(json_encode(compact('link_id', 'link', 'title')));
	}
}