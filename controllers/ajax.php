<?php
class AjaxController extends StudipController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->user_id     = $GLOBALS['user']->id;
        $this->quick_links = Quicklink::GetInstance($this->user_id);
    }

    function remove_action($id)
    {
        $this->quick_links->remove($id);
        $this->render_json(array('id' => false));
    }

    function store_action()
    {
        $link    = Request::get('link');
        $title   = Request::get('title');
        $title   = preg_replace('/- ' . preg_quote($GLOBALS['UNI_NAME_CLEAN'], '/') . '$/', '', $title);
        $link_id = $this->quick_links->store(null, $link, $title);
        $this->render_json(compact('link_id', 'link', 'title'));
    }
}
