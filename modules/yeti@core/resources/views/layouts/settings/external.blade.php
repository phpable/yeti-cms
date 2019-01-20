@object($Item, 'id', 'type', 'link')

@param($id)

@param($prefix, 'data')
@param($action, 'edit')
@param($hidden, false)

@param($canEdit, false)
@param($canDelete, false)
@param($canCommit, false)
@param($canRollback, false)

<tr @if ($hidden) style="display: none" @endif @if(!empty($id)) id="{{ $id }}" @endif @if(!empty($Item->id)) data-uid="{{ $Item->id }}" @endif>
	<td data-name="{{ $prefix }}[{{ $action  }}][{{ !empty($Item->id) ? $Item->id : '__UID__' }}][type]" data-type="list" data-options="script,style,font,canonical">{{ $Item->type }}</td>
	<td data-name="{{ $prefix }}[{{ $action  }}][{{ !empty($Item->id) ? $Item->id : '__UID__' }}][link]" data-type="text">{{ $Item->link }}</td>

	<td>
		<div class="row-actions">
			<a href="javascript:void(0);" title="Edit" data-role="edit" @if (!$canEdit) style="display: none" @endif>
				<i class="fa fa-pencil fa-action"></i>
			</a>
			<a href="javascript:void(0);" title="Commit" data-role="commit" @if (!$canCommit) style="display: none" @endif>
				<i class="fa fa-check fa-action"></i>
			</a>
			<a href="javascript:void(0);" title="Rollback" data-role="rollback" @if (!$canRollback) style="display: none" @endif>
				<i class="fa fa-remove fa-action"></i>
			</a>
			<a href="javascript:void(0);" title="Delete" data-role="delete" @if (!$canDelete) style="display: none" @endif data-name="{{ $prefix }}[delete][]">
				<i class="fa fa-trash fa-action"></i>
			</a>
		</div>
	</td>
</tr>
