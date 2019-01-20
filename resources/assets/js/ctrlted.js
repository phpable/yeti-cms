(function($){
	var __TYPES = ['text', 'list'];
	var jForm = null;

	function checkContext(jElement){
		var jContext = jForm.find('tr.editing').first();
		return jContext.length < 1 || $.contains(jContext[0], jElement[0]);
	}

	function checkInput(jElement){
		var errCount = 0;

		$('input,select', jElement.closest('tr').find('td[data-name]')).each(function () {
			var jControl = $(this);

			if (jControl.val().replace(/^\s+|\s+$/, '').length < 1){
				jControl.addClass('error');
				errCount++;
			}

		});

		return errCount < 1;

	}

	function initInput(jElement){
		var type = jElement.data('type');
		if (!__TYPES.indexOf(type)){
			type = 'text';
		}

		var jControl = null;
		switch (type) {
			case "list":
				jControl = $('<select></select>');

				$.each(jElement.data('options').split(/\s*,\s*/), function(index, option){
					var jOption = $('<option value="'
						+ option + '">' + option + '</option>');

					if (jElement.text() === option){
						jOption.attr('selected', true);
					}

					jControl.append(jOption);
				});

				break;
			case "text":
			case "password":
			default:
				jControl = $('<input type="'
					+ type +'"/>').val(jElement.text());
				break;
		}

		var jStorage = $('<input type="hidden" name="'
			+ jElement.data('name') + '"/>');

		jStorage.val(jElement.text());
		jStorage.prependTo(jForm);

		return jControl;
	}

	function commitInput(jElement){
		var jControl = jElement.find('input,select');

		var jStorage = jForm.find('input[type="hidden"][name="'
			+ jElement.data('name') + '"]');

		jStorage.val(jControl.val());
		jControl.remove();

		return jStorage.val();
	}

	function rollbackInput(jElement){
		jElement.find('input,select').remove();

		var jStorage = jForm.find('input[type="hidden"][name="'
			+ jElement.data('name') + '"]');

		return jStorage.val();
	}

	$.fn.ctrlTED = function(options){
		return this.each(function() {
			var jTable = $(this);

			if (jTable.is('table')) {
				jForm = jTable.closest('form').first();
				if (jForm.length > 0) {

					/**
					 * Commit action
					 */
					$(jTable).on('click', '[data-role="commit"]', function () {
						var jThis = $(this);

						if (checkContext(jThis) && checkInput(jThis)) {

							var jContainer = jThis.closest('tr');
							jContainer.closest('tr').removeClass('editing').find('td[data-name]').each(function () {
								$(this).text(commitInput($(this)));
							});

							$('[data-role="edit"]', jContainer).show();
							$('[data-role="delete"]', jContainer).show();
							$('[data-role="rollback"]', jContainer).hide();
							jThis.hide();
						}

						return false;
					});

					/**
					 * Rollback action
					 */
					$(jTable).on('click', '[data-role="rollback"]', function () {
						var jThis = $(this);
						if (checkContext(jThis)) {

							var jContainer = jThis.closest('tr');
							jContainer.closest('tr').removeClass('editing').find('td[data-name]').each(function () {
								$(this).text(rollbackInput($(this)));
							});

							jForm.find('input[type="hidden"][name*="'
								+ jContainer.data('uid') + '"]').remove();

							if (jContainer.is('.dynamic')) {
								jThis.closest('tr.dynamic').remove();
							}

							$('[data-role="edit"]', jContainer).show();
							$('[data-role="delete"]', jContainer).show();
							$('[data-role="commit"]', jContainer).hide();
							jThis.hide();
						}

						return false;
					});

					/**
					 * Edit action.
					 */
					$(jTable).on('click', '[data-role="edit"]', function () {
						var jThis = $(this);
						if (checkContext(jThis)) {

							var jContainer = jThis.closest('tr');
							jContainer.addClass('editing').find('td[data-name]').each(function () {
								var jThis = $(this);

								initInput(jThis).appendTo(jThis.empty());
							});

							$('[data-role="commit"]', jContainer).show();
							$('[data-role="rollback"]', jContainer).show();
							$('[data-role="delete"]', jContainer).hide();
							jThis.hide();
						}

						return false;
					});

					/**
					 * Delete action
					 */
					$(jTable).on('click', '[data-role="delete"]', function () {
						var jThis = $(this);
						if (checkContext(jThis)) {

							var jContainer = jThis.closest('tr');
							var uid = jContainer.data('uid');

							jForm.find('input[type="hidden"][name*="' + uid + '"]').remove();
							if (!jContainer.is('.dynamic ')) {
								$('<input type="hidden" name="' + jThis.data('name') + '" value="' + uid + '"/>').prependTo(jForm);
							}

							jContainer.remove();
						}

						return false;
					});

					/**
					 * Create action
					 */
					$(jTable).on('click', '[data-role="create"]', function () {
						var jThis = $(this);

						if (checkContext(jThis)) {
							var jTemplate = $('#' + jThis.data('template')).first();

							if (jTemplate.length > 0) {
								var uid = 'tmp' + jForm.find('input[type="hidden"]',
									jForm).length;

								var jRow = $('<tr class="dynamic" data-uid="' + uid
									+ '">' + jTemplate.html().replace(/__UID__/g, uid) + '</tr>');

								jTable.append(jRow);
								jRow.addClass('editing').find('td[data-name]').each(function () {
									var jThis = $(this);

									initInput(jThis).appendTo(jThis.empty());
								}).find('input').first().focus();

								$('[data-role="rollback"]', jRow).show();
								$('[data-role="commit"]', jRow).show();

								$('[data-role="edit"]', jRow).hide();
								$('[data-role="delete"]', jRow).hide();
							}
						}

						return false;
					});

					$(jTable).on('change', 'input,select', function () {
						$(this).removeClass('error');
					});

					$(jTable).on('keyup', 'input', function (jEvent) {
						var jThis = $(this);
						jThis.removeClass('error');

						switch (jEvent.keyCode) {
							case 27:
								jThis.closest('tr').find('[data-role="rollback"]').trigger('click');
								break;
							case 13:
								jThis.closest('tr').find('[data-role="commit"]').trigger('click');
								break;
						}
					});
				}
			}
		});
	};
})(jQuery);
