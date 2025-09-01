<?php

return [
    'items' => [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => '<svg class="mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>',
            'roles' => ['super_admin', 'hrd', 'kadiv', 'staff', 'kasie', 'direksi'],
            'active_pattern' => 'dashboard*'
        ],
        [
            'name' => 'Pengajuan Cuti',
            'route' => 'cuti.index',
            'icon' => '<svg class="mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>',
            'roles' => ['direksi', 'hrd', 'kadiv', 'staff', 'kasie'],
            'active_pattern' => 'cuti.index*'
        ],
        [
            'name' => 'Approval Cuti',
            'route' => 'approval.index',
            'icon' => '<svg class="mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>',
            'roles' => ['direksi', 'kadiv', 'kasie', 'staff'],
            'active_pattern' => 'approval.index*'
        ],
        [
            'name' => 'History Approval',
            'route' => 'approval.history',
            'icon' => '<svg class="mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>',
            'roles' => ['direksi', 'kadiv', 'kasie', 'staff'],
            'active_pattern' => 'approval.history*'
        ],
        [
            'name' => 'Rekap Cuti',
            'route' => 'hrd.rekap.index',
            'icon' => '<svg class="mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>',
            'roles' => ['hrd', 'super_admin'],
            'active_pattern' => 'hrd.rekap.index*'
        ],
        [
            'name' => 'Kuota Cuti',
            'route' => 'hrd.quota.index',
            'icon' => '<svg class="mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                </svg>',
            'roles' => ['hrd', 'super_admin'],
            'active_pattern' => 'hrd.quota.index*'
        ],
        // Add more menu items here as needed
    ]
];
