(function ($) {
	$(function () {
		$(window).on('resize', function() {
			$('[data-effect~="full-height"]').each(function () {
				var jThis = $(this);
				var height = $('#' + jThis.data('master')).add(jThis.parent()).first().innerHeight();

				if (jThis.is('[data-height-dec]')) {
					jThis.attr('data-height-dec').split(/ +/).forEach(function (item) {
						height -= /^[0-9]+$/.test(item) ? parseInt(item)
							: ($('#' + item).outerHeight() || 0);
					});
				}

				jThis.height(height);
			});
		}).trigger('resize');
	})
})(jQuery);

(function ($) {
	$(function () {
		$(window).on('resize', function() {
			$('[data-effect~="full-width"]').each(function () {
				var jThis = $(this);
				var width = $('#' + jThis.data('master')).add(jThis.parent()).first().innerWidth();

				if (jThis.is('[data-width-dec]')) {
					jThis.attr('data-width-dec').split(/ +/).forEach(function(item) {
						width -= /^[0-9]+$/.test(item) ? parseInt(item)
							: ($('#' + item).outerWidth() || 0);
					});
				}

				jThis.width(width);
			});
		}).trigger('resize');
	})
})(jQuery);

(function ($) {
	$(function (){
		var jBar = $('#progressbar');

		$('div', jBar).css({width: 0}).hide();

		function updateBar(value, action){
			var jProgress = action !== undefined
				? $('.progress-bar-' + action, jBar)
				: $('div', jBar).filter(':visible');

			if (jProgress.length > 0){
				if (!jProgress.is(':visible')) {
					$('div', jBar).not('.progress-bar-' + action).hide();
					jProgress.show();
				}

				jProgress.css({width: value + '%'});

			}
		}

		$('#btn-deploy').bind('click', function() {
			var jThis = $(this);

			if (!jThis.is('.disabled')) {
				jThis.addClass('disabled');

				if (jThis.is('[data-href]')) {
					rebuild(jThis.attr('data-href'));
				}
			}
			return false;
		});

		function rebuild(url){
			$.ajax(url, {
				method: 'GET',
				datatype: 'json'
			}).done(function (Response) {
				if (!Response['status'] || !Response['url']) {
					document.location.reload();
					return false;
				}

				var __INTERVAL__ = setInterval(function () {
					$.ajax(Response['url'], {
						method: 'GET',
						datatype: 'json',
					}).done(function (Response) {
						if (!Response['status']) {
							updateBar(100);
							clearInterval(__INTERVAL__);

							setTimeout(function () {
								document.location.reload();
							}, 500);
						}

						updateBar(Response['percent'], Response['action']);
					});
				}, 200);
			});
		}
	});
})(jQuery);

(function ($) {
	$(function (){
		$('[data-effect~="waiting"]').click(function(){
			var jThis = $(this);

			if (!jThis.is('.waiting')){
				jThis.addClass('waiting');

				setTimeout(function(){
					jThis.removeClass('waiting');
				}, 500);

				return false;
			}

			return true;
		});
	});
})(jQuery);

(function ($) {
	$(function (){
		$.tabSave = function(tid) {
			var tids = $.cookie('_et') !== undefined
				? $.cookie('_et').toString().split(',') : [];

			tids.unshift(tid);

			if (tids.length > 255){
				tids = tids.slice(0, 254)
			}

			$.cookie('_et', tids.join(','), { path: '/'});
		};

		function _search(tid) {
			if ($.cookie('_et') !== undefined && tid !== undefined){
				return $.cookie('_et').toString().split(',').indexOf(tid.toString());
			}

			return -1;
		}

		$.fn.tabFind = function(name) {
			return $(this.filter(function(index, node){
				return _search($(node).data(name)) > -1;
			}).toArray().sort(
				function(first, second){
					return _search($(first).data(name), 10) - _search($(second).data(name), 10);
				}
			)).first();
		};
	});
})(jQuery);
