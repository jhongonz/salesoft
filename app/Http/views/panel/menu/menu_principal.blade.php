<ul class="sidebar-menu" data-widget="tree">
    <li class="header"></li>
    @if (count($menu) > 0)
        @foreach ($menu as $key => $option)
            @if ($option->haveChild)
                <li class="treeview @if($option->expand) active menu-open @endif">
                    <a href="#" >
                        <i class="fa fa-fw {{ $option->iconMenu }}"></i>
                        <span>{{ $option->name }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" @if($option->expand) style="display: block;" @endif>
                        @foreach ($option->children as $child)
                        	@if(!$child->hidden)
                        		@isset($child->link)
                        			<li>
		                                <a class="action-menu" href="#" data-url="{{ url($child->link) }}"><i class="fa fa-fw fa-dot-circle-o" style="color:snow;"></i>{{ $child->name }}</a>
		                            </li>
                        		@endisset
                            @endif
                        @endforeach
                    </ul>
                </li>
            @else
            	@if(!$option->hidden)
            		@isset($option->link)
	            		<li>
		                    <a class="action-menu" href="#" data-url="{{ url($option->link) }}"><i class="fa fa-fw {{ $option->iconMenu }}"></i><span> {{ $option->name }}</span></a>
		                </li>
	                @endif
            	@endif
            @endif
        @endforeach
    @endif
</ul>