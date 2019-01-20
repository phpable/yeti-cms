@if ($pages > 1)
	<ul class="pagination-list">

		@if($active > 1)
			<li><a href="?page=1"><i class="icon-double-angle-left"></i></a></li>
			<li><a href="?page={{ $active - 1 }}"><i class="icon-angle-left"></i></a></li>
		@else
			<li class="disabled"><span title="Start"><i class="icon-double-angle-left"></i></span></li>
			<li class="disabled"><span title="Prev"><i class="icon-angle-left"></i></span></li>
		@endif

		@for($page = 1; $page <= $pages; $page++)
			@if ($page == $active)
				<li class="hidden-phone active pagination-active"><span>{{ $page }}</span></li>
			@else
				<li class="hidden-phone"><a class="pagenav" href="?page={{ $page }}" title="{{ $page }}">{{ $page }}</a></li>
			@endif
		@endfor

		@if($active < $pages)
			<li><a href="?page={{ $active + 1 }}" title="Next"><i class="icon-angle-right"></i></a></li>
			<li><a href="?page={{ $pages }}" title="End"><i class="icon-double-angle-right"></i></a></li>
		@else
			<li class="disabled"><span title="Next"><i class="icon-angle-right"></i></span></li>
			<li class="disabled"><span title="End"><i class="icon-double-angle-right"></i></span></li>
		@endif

	</ul>
@endif
