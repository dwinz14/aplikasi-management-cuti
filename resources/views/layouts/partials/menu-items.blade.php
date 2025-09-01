@php
    $menuItems = getFilteredMenuItems();
@endphp

@foreach ($menuItems as $item)
    <a href="{{ route($item['route']) }}"
        class="{{ isMenuActive($item['active_pattern']) ? 'bg-black/20 text-white' : 'text-indigo-100 hover:bg-black/20 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition duration-150">
        {!! $item['icon'] !!}
        {{ $item['name'] }}
    </a>
@endforeach
