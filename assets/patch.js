if (typeof jQuery === 'undefined' || typeof STUDIP === 'undefined') {
	throw 'No valid Stud.IP environment';
}

window.jQuery(function ($) {

	$('#barBottomright a').each(function () {
		if ($(this).text() !== 'Quicklinks') {
			return;
		}

		var bar = $('#barBottomright'),
			li = $(this).closest('li'),
			div = $('<div class="quick-link-list"/>'),
			input = $('<label><input type="checkbox"/> Aktuelle Seite</label>'),
			templates = {
				link: '<li id="ql-{{link_id}}"><a href="{{link}}">{{title}}</a></li>',
				list: '<ul>{{#links}}{{>link}}{{/links}}</ul>'
			},
			list = Mustache.to_html(templates.list, STUDIP.Quicklinks, templates);
		
		div.append(input).append(list);	
		$(':checkbox', input).click(function () {
			var	uri = STUDIP.Quicklinks.api,
				params = {};

			if (this.checked) {
				uri = STUDIP.Quicklinks.api.replace('METHOD', 'store');
				params = {link: STUDIP.Quicklinks.uri, title: document.title};
			} else {
				uri = STUDIP.Quicklinks.api.replace('METHOD', 'remove/' + STUDIP.Quicklinks.id);
			}
			this.disabled = true;

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

		div.css({
			right: 0,
			top: li.height()
		});

		li.append(div)
			.hover(function (event) {
				$(this).toggleClass('hovered', event.type === 'mouseenter');
			});

		$(this).closest('li').addClass('quick-link');
	});
});