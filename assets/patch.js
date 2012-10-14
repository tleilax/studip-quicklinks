if (!((typeof jQuery !== "undefined" && jQuery !== null) && (typeof STUDIP !== "undefined" && STUDIP !== null))) {
  throw 'No valid Stud.IP environment';
}

(function($) {
  var create_link;
  create_link = function(link) {
    return "<li class=\"item\" id=\"ql-" + link.link_id + "\" data-id=\"" + link.link_id + "\">\n    <a class=\"hidden options\" href=\"#\">\n        <img src=\"" + STUDIP.ASSETS_URL + "/images/icons/16/blue/trash.png\" alt=\"" + ("entfernen".toLocaleString()) + "\">\n    </a>\n    <a href=\"" + link.link + "\">" + link.title + "</a>\n</li>";
  };
  $(function() {
    var div, html, link, links, list, parent;
    links = $('#barBottomright a').filter(function() {
      return $(this).text() === 'Quicklinks';
    });
    if (!(links.length > 0)) return;
    parent = links.closest('li');
    list = (function() {
      var _i, _len, _ref, _results;
      _ref = STUDIP.Quicklinks.links;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        link = _ref[_i];
        _results.push(create_link(link));
      }
      return _results;
    })();
    html = "<div class=\"quick-links list left\">\n    <ul>\n        <li class=\"item trigger\">\n            <label>\n                <input type=\"checkbox\">\n                <span>" + ('Aktuelle Seite'.toLocaleString()) + "</span>\n            </label>\n        </li>\n        " + (list.join("\n")) + "\n    </ul>\n</div>";
    div = $(html);
    $(':checkbox', div).attr('checked', STUDIP.Quicklinks.id !== false);
    parent.append(div);
    return parent.hover(function(event) {
      return $(this).toggleClass('hovered', event.type === 'mouseenter');
    });
  });
  $('.quick-links.list :checkbox').live('click', function() {
    var params, uri,
      _this = this;
    uri = STUDIP.Quicklinks.api;
    params = {};
    if (this.checked) {
      uri = uri.replace('METHOD', 'store');
      params.link = STUDIP.Quicklinks.uri;
      params.title = document.title;
    } else {
      uri = uri.replace('METHOD', "remove/" + STUDIP.Quicklinks.id);
    }
    this.disabled = true;
    $(this).showAjaxNotification();
    return $.post(uri, params, function(json) {
      var link, _ref;
      if (json.link_id != null) {
        link = $(create_link(json));
        $('.quick-links.list ul').append(link.hide());
        link.show('blind');
      } else {
        $("#ql-" + STUDIP.Quicklinks.id).hide('blind', function() {
          return $(this).remove();
        });
      }
      STUDIP.Quicklinks.id = (_ref = json.link_id) != null ? _ref : false;
      _this.disabled = false;
      _this.checked = json.link_id != null;
      return $(_this).hideAjaxNotification();
    });
  });
  return $('.quick-links.list a.options').live('click', function() {
    var id, uri,
      _this = this;
    id = "" + $(this).closest('.item').data().id;
    uri = STUDIP.Quicklinks.api.replace('METHOD', "remove/" + id);
    return $.post(uri, function(json) {
      $("#ql-" + id).hide('blind', function() {
        return $(this).remove();
      });
      if (id === STUDIP.Quicklinks.id) {
        STUDIP.Quicklinks.id = false;
        return $('.quick-links.list :checkbox').attr('checked', false);
      }
    });
  });
})(jQuery);