if (typeof STUDIP === 'undefined') {
    throw 'No STUDIP environment found';
}

(function ($) {

    // #1: Disable arbitrary element if input's value is empty
    //     Example:
    //        <input type="text" data-disableIfEmpty="#submit">
    //        <button id="submit">
    $(document).on('change', '[data-disableIfEmpty]', function () {
        var $this    = $(this),
            target   = $this.data('disableifempty'),
            value    = $this.val(),
            disabled = !value;
        if ($this.is(':checkbox')) {
            disabled = !$this.prop('checked') && !$this.prop('indeterminate');
        }
        $(target).prop('disabled', disabled);
    });
    
    $(document).ready(function () {
        // Initialize states
        $('[data-disableIfEmpty]').change(); // #1
    });
    
}(jQuery));