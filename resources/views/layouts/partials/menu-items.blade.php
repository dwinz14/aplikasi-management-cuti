@php
    $menuItems = getFilteredMenuItems();
@endphp

@foreach ($menuItems as $item)
    <a href="{{ route($item['route']) }}"
        class="{{ isMenuActive($item['active_pattern']) ? 'bg-black/20 text-white dark:bg-slate-600 dark:text-gray-100' : 'text-indigo-100 hover:bg-black/20 hover:text-white dark:text-gray-300 dark:hover:bg-slate-600 dark:hover:text-gray-100' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 hover:scale-105">
        {!! $item['icon'] !!}
        {{ $item['name'] }}
    </a>
@endforeach
