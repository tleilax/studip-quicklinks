###
Modal behaviour for Stud.IP v0.9
Released under the MIT license
###
(($, STUDIP) ->
    # Initialize STUDIP object if neccessary
    STUDIP ?= {}
    return if STUDIP.Modal?

    # Decorators for content
    decorators =
        # Get title from fieldset's legend
        'form fieldset legend': (options) ->
            legend = $('form fieldset legend', this).first()
            options.title = legend.remove().text() if legend?
        # Create dialog's buttons from form buttons
        '.type-button': (options) ->
            buttons = $('.type-button', this)

            # Process all .buttons except .cancel
            $('.button:not(.cancel)', buttons).hide().each ->
                label = $(@).text()
                options.buttons[label] = => $(@).click()

            # Process .cancel button
            cancel  = $('.button.cancel', buttons).hide()
            options.close_label = cancel.text() if cancel.length

            buttons.hide() unless $(':visible', buttons).length

    # Click handler for modal links
    click_handler = (event) ->
        event.preventDefault()

        href  = $(@).attr 'href'
        title = $(@).attr 'title'
        label = $(@).text()

        options =
            modal  : true
            width  : 500
            title  : title ? label
            buttons: {}
            close_label: 'Schliessen'.toLocaleString()

        $('<div/>').load href, ->
            # Apply appropriate decorators
            for test, decorator of decorators
                decorator.apply @, [options] unless not $(@).find(test).length

            # Add close button, remove label variable
            options.buttons[options.close_label] = -> $(@).dialog 'close'
            delete options.close_label

            $(@).dialog options

    # Create global object
    STUDIP.Modal =
        version: '0.9',
        selector: 'a[data-behaviour~=modal]',
        register: ->
            $(@selector).on 'click', click_handler
        unregister: ->
            $(@selector).off 'click', click_handler

    # Register modal behaviour
    STUDIP.Modal.register()

)(jQuery, STUDIP)
