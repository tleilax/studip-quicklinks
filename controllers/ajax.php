<?php
class AjaxController extends AuthenticatedController {
    function before_filter(&$action, &$args) {
        $this->user_id = $GLOBALS['auth']->auth['uid'];
    }

    function remove_action($id) {
        Quicklink::Delete($this->user_id, $id);
        $this->render_json(array('id' => false));
    }

    function store_action() {
        $link = Request::get('link');
        $title = Request::get('title');
        $title = preg_replace('/- '.preg_quote($GLOBALS['UNI_NAME_CLEAN'], '/').'$/', '', $title);
        $link_id = Quicklink::Save($this->user_id, null, $link, $title);
        $this->render_json(compact('link_id', 'link', 'title'));
    }
    
    private function render_json($arguments = array()) {
        $this->response->add_header('Content-Type', 'application/json');
        $this->render_text(json_encode($arguments));
    }
}