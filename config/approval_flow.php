<?php

return [
    // urutan acc cuti:
    'staff'   => ['pengganti', 'atasan_divisi'],
    'kasie'   => ['pengganti', 'kadiv'],
    'kadiv'   => ['pengganti', 'direksi'],
    'hrd'     => ['direksi'],
    'direksi' => ['auto'], // pemohon direksi auto-approved
];
