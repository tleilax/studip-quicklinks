/*
Modal behaviour for Stud.IP v0.9
Released under the MIT license
*/
(function($, STUDIP) {
  var click_handler, decorators;
  if (STUDIP == null) STUDIP = {};
  if (STUDIP.Modal != null) return;
  decorators = {
    'form fieldset legend': function(options) {
      var legend;
      legend = $('form fieldset legend', this).first();
      if (legend != null) return options.title = legend.remove().text();
    },
    '.type-button': function(options) {
      var buttons, cancel;
      buttons = $('.type-button', this);
      $('.button:not(.cancel)', buttons).hide().each(function() {
        var label,
          _this = this;
        label = $(this).text();
        return options.buttons[label] = function() {
          return $(_this).click();
        };
      });
      cancel = $('.button.cancel', buttons).hide();
      if (cancel.length) options.close_label = cancel.text();
      if (!$(':visible', buttons).length) return buttons.hide();
    }
  };
  click_handler = function(event) {
    var href, label, options, title;
    event.preventDefault();
    href = $(this).attr('href');
    title = $(this).attr('title');
    label = $(this).text();
    options = {
      modal: true,
      width: 500,
      title: title != null ? title : label,
      buttons: {},
      close_label: 'Schliessen'.toLocaleString()
    };
    return $('<div/>').load(href, function() {
      var decorator, test;
      for (test in decorators) {
        decorator = decorators[test];
        if (!!$(this).find(test).length) decorator.apply(this, [options]);
      }
      options.buttons[options.close_label] = function() {
        return $(this).dialog('close');
      };
      delete options.close_label;
      return $(this).dialog(options);
    });
  };
  STUDIP.Modal = {
    version: '0.9',
    selector: 'a[data-behaviour~=modal]',
    register: function() {
      return $(this.selector).live('click', click_handler);
    },
    unregister: function() {
      return $(this.selector).die('click', click_handler);
    }
  };
  return STUDIP.Modal.register();
})(jQuery, STUDIP);