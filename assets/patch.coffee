throw 'No valid Stud.IP environment' unless jQuery? and STUDIP?

do ($ = jQuery) ->

    # Creates the html for a link
    create_link = (link) ->
        """
        <li class="item" id="ql-#{link.link_id}" data-id="#{link.link_id}">
            <a class="hidden options" href="#">
                <img src="#{STUDIP.ASSETS_URL}/images/icons/16/blue/trash.png" alt="#{"entfernen".toLocaleString()}">
            </a>
            <a href="#{link.link}">#{link.title}</a>
        </li>
        """

    # Build link list on dom ready
    $(document).ready ->
        links = $('#barBottomright a').filter -> $(@).text() is 'Quicklinks'

        return unless links.length > 0

        parent = links.closest 'li'
        href   = links.attr 'href'

        list = (create_link link for link in STUDIP.Quicklinks.links)
        html = """
               <div class="quick-links list left">
                   <ul>
                       <li class="item trigger">
                           <a href="#{href}" class="options">
                               <img src="#{STUDIP.ASSETS_URL}/images/icons/16/blue/admin.png" alt="#{"Verwaltung".toLocaleString()}">
                           </a>
                           <label>
                               <input type="checkbox">
                               <span>#{'Aktuelle Seite'.toLocaleString()}</span>
                           </label>
                       </li>
                       #{list.join("\n")}
                   </ul>
               </div>
               """
        div = $ html

        $(':checkbox', div).attr 'checked', STUDIP.Quicklinks.id != false

        parent.append(div)
        parent.hover (event) ->
            $(@).toggleClass 'hovered', event.type is 'mouseenter'

    # Add new link in list
    $(document).on 'click', '.quick-links.list :checkbox', ->
        uri    = STUDIP.Quicklinks.api
        params = {}

        if @checked
            uri = uri.replace 'METHOD', 'store'
            params.link  = STUDIP.Quicklinks.uri
            params.title = document.title
        else
            uri = uri.replace 'METHOD', "remove/#{STUDIP.Quicklinks.id}"

        @disabled = true

        $(@).showAjaxNotification()

        $.post uri, params, (json) =>
            if json.link_id?
                link = $ create_link(json)
                $('.quick-links.list ul').append link.hide()
                link.show 'blind'
            else
                $("#ql-#{STUDIP.Quicklinks.id}").hide 'blind', -> $(@).remove()

            STUDIP.Quicklinks.id = json.link_id ? false

            @disabled = false
            @checked  = json.link_id?
            $(@).hideAjaxNotification()

    # Execute removal of quicklinks in list via ajax
    $(document).on 'click', '.quick-links.list :not(.trigger) a.options', (event) ->
        event.preventDefault()

        id  = "" + $(@).closest('.item').data().id
        uri = STUDIP.Quicklinks.api.replace 'METHOD', "remove/#{id}"

        $.post uri, ->
            $("#ql-#{id}").hide 'blind', -> $(@).remove()

            if id is STUDIP.Quicklinks.id
                STUDIP.Quicklinks.id = false
                $('.quick-links.list :checkbox').attr 'checked', false

    # Execute moving of links in administration via ajax
    $(document).on 'click', 'table.quicklinks a[href*="links/move"]', (event) ->
        event.preventDefault()

        href = $(@).addClass('ajaxing').attr 'href'
        $(@).closest('tbody').find('td:last-child img').each ->
            $(@).closest('a').click -> false
            src = $(@).attr 'src'
            $(@).css('opacity', 0.5).attr 'src', src.replace /yellow|blue/, 'grey'
        $(@).closest('table').find(':checkbox').attr 'disabled', true

        $.get href, (response) =>
            $(@).closest('table.quicklinks').replaceWith response
