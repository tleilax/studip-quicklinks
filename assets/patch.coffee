throw 'No valid Stud.IP environment' unless jQuery? and STUDIP?

(($) ->

    create_link = (link) ->
        """
        <li id="ql-#{link.link_id}">
            <a href=\"#{link.link}\">#{link.title}</a>
        </li>
        """

    $ ->
        links = $('#barBottomright a').filter -> $(@).text() is 'Quicklinks'

        return unless links.length

        parent = links.closest 'li'

        list = (create_link link for link in STUDIP.Quicklinks.links)
        html = """
               <div class="quick-link-list">
                  <label>
                      <input type="checkbox"}>
                      #{'Aktuelle Seite'.toLocaleString()}
                  </label>
                  <ul>#{list.join("\n")}</ul>
               </div>
               """
        div = $(html).css
            right: 0
            top  : parent.height()

        $(':checkbox', div).attr 'checked', STUDIP.Quicklinks.id?

        parent.append div
        parent.addClass 'quick-link'
        parent.hover (event) ->
            $(@).toggleClass 'hovered', event.type is 'mouseenter'

    $('.quick-link-list :checkbox').on 'click', ->
        uri    = STUDIP.Quicklinks.api
        params = {}

        if @checked
            uri = uri.replace 'METHOD', 'store'
            params.link  = STUDIP.Quicklinks.uri
            params.title = document.title
        else
            uri = uri.replace 'METHOD', "remove/#{STUDIP.Quicklinks.id}"

        @disabled = true

        $.post uri, params, (json) =>
            console.log json
            if json.link_id?
                $('.quick-link-list ul').append create_link(json)
            else
                $("#ql-#{STUDIP.Quicklinks.id}").remove()

            STUDIP.Quicklinks.id = json.link_id ? false

            @disabled = false
            @checked  = json.link_id?

)(jQuery)

###
$(':checkbox', input).click(function () {
    $.post(uri, params, function (json) {
        if (!json.link_id) {
            $('#ql-' + STUDIP.Quicklinks.id).remove();
        } else {
            $(Mustache.to_html(templates.link, json)).appendTo('.quick-link-list ul');
        }
        STUDIP.Quicklinks.id = json.link_id || false;
        $(':checkbox', input).attr('checked', !!json.link_id).attr('disabled', false);
    }, 'json');
    return false;
}).attr('checked', !!STUDIP.Quicklinks.id);

###