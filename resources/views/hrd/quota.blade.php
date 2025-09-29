 <x-app-layout>
     <x-slot name="header">
         <div class="flex items-center justify-between">
             <div>
                 <h2
                     class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                     {{ __('Manajemen Kuota Cuti') }}
                 </h2>
                 <p class="text-sm text-gray-500 mt-1">
                     Kelola kuota cuti karyawan dengan mudah dan efisien.
                 </p>
             </div>
         </div>
     </x-slot>

     <div class="space-y-4">
         {{-- Filter & Search --}}
         <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl p-4 transition-all">
             <div class="flex items-center justify-between mb-3">
                 <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Filter & Pencarian</h3>
                 <span class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                     <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     Gunakan filter untuk mencari data spesifik
                 </span>
             </div>

             <form method="GET" action="{{ route('hrd.quota.index') }}">
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                     {{-- Search --}}
                     <div>
                         <label for="search"
                             class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Cari Nama</label>
                         <input type="text" id="search" name="search" value="{{ $search }}"
                             class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs"
                             placeholder="Ketik nama karyawan...">
                     </div>

                     {{-- Division --}}
                     <div>
                         <label for="division_id"
                             class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Divisi</label>
                         <select id="division_id" name="division_id"
                             class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                             <option value="">-- Semua Divisi --</option>
                             @foreach ($divisions as $division)
                                 <option value="{{ $division->id }}"
                                     {{ $divisionId == $division->id ? 'selected' : '' }}>
                                     {{ $division->nama_divisi }}
                                 </option>
                             @endforeach
                         </select>
                     </div>

                     {{-- Role --}}
                     <div>
                         <label for="role"
                             class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Role</label>
                         <select id="role" name="role"
                             class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                             <option value="">-- Semua Role --</option>
                             <option value="staff" {{ $role == 'staff' ? 'selected' : '' }}>Staff</option>
                             <option value="kasie" {{ $role == 'kasie' ? 'selected' : '' }}>Kasie</option>
                             <option value="kabag" {{ $role == 'kabag' ? 'selected' : '' }}>kabag</option>
                             <option value="hrd" {{ $role == 'hrd' ? 'selected' : '' }}>HRD</option>
                         </select>
                     </div>

                     {{-- Actions --}}
                     <div class="flex flex-col md:flex-row gap-2 items-stretch justify-end">
                         <button type="submit"
                             class="inline-flex items-center justify-center px-3 py-1.5 bg-primary-600 rounded-md text-white font-medium text-xs hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                             Terapkan
                         </button>
                         <a href="{{ route('hrd.quota.index') }}"
                             class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 dark:bg-slate-700 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                             ↺ Reset
                         </a>
                     </div>
                 </div>
             </form>
         </div>

         {{-- Reset Kuota --}}
         <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl p-4">
             <div class="flex items-center justify-between mb-4">
                 <div>
                     <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Pengaturan Kuota Cuti</h3>
                     <p class="text-xs text-gray-500 dark:text-gray-400">Reset kuota cuti untuk semua karyawan atau per
                         divisi</p>
                 </div>
                 <span class="text-xs text-amber-600 dark:text-amber-400 flex items-center">
                     Aksi ini tidak dapat dibatalkan
                 </span>
             </div>

             <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                 {{-- Semua --}}
                 <div class="p-3 rounded-lg border border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20">
                     <h4 class="text-xs font-medium text-red-700 dark:text-red-200 mb-1">Reset Semua Karyawan</h4>
                     <form action="{{ route('hrd.quota.reset') }}" method="POST" x-data
                         @submit.prevent="if(confirm('Apakah yakin reset semua kuota?')) $el.submit()">
                         @csrf
                         <div class="flex gap-2">
                             <input type="number" name="default_quota" value="12" min="0"
                                 class="flex-1 rounded-md border-red-300 dark:border-red-600 dark:bg-red-900/50 dark:text-red-200 text-xs focus:border-red-500 focus:ring-red-500">
                             <button type="submit"
                                 class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500">
                                 Reset Semua
                             </button>
                         </div>
                     </form>
                 </div>

                 {{-- Per Divisi --}}
                 <div
                     class="p-3 rounded-lg border border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20">
                     <h4 class="text-xs font-medium text-yellow-700 dark:text-yellow-200 mb-1">Reset per Divisi</h4>
                     <form action="{{ route('hrd.quota.resetDivision') }}" method="POST" x-data
                         @submit.prevent="if(confirm('Apakah yakin reset kuota divisi ini?')) $el.submit()">
                         @csrf
                         <div class="space-y-2">
                             <select name="division_id" required
                                 class="w-full rounded-md border-yellow-300 dark:border-yellow-600 dark:bg-yellow-900/70 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                 <option value="">-- Pilih Divisi --</option>
                                 @foreach ($divisions as $division)
                                     <option value="{{ $division->id }}">{{ $division->nama_divisi }}</option>
                                 @endforeach
                             </select>
                             <div class="flex gap-2">
                                 <input type="number" name="default_quota" value="12" min="0"
                                     class="flex-1 rounded-md border-yellow-300 dark:border-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-200 text-xs focus:border-yellow-500 focus:ring-yellow-500">
                                 <button type="submit"
                                     class="px-3 py-1.5 bg-yellow-600 text-white text-xs font-medium rounded-md hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500">
                                     Reset Divisi
                                 </button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>

         {{-- Tabel Kuota --}}
         <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl overflow-hidden">
             <div class="overflow-x-auto">
                 <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                     <thead class="bg-gray-50 dark:bg-slate-700/50">
                         <tr>
                             <th
                                 class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                 Nama</th>
                             <th
                                 class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                 Divisi</th>
                             <th
                                 class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                 Role</th>
                             <th
                                 class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                 Sisa Cuti</th>
                             <th
                                 class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                 Aksi</th>
                         </tr>
                     </thead>
                     <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                         @forelse($users as $user)
                             <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40">
                                 <td class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}
                                 </td>
                                 <td class="px-4 py-2 text-gray-500 dark:text-gray-400">
                                     {{ $user->division->nama_divisi ?? '-' }}</td>
                                 <td class="px-4 py-2">
                                     <span
                                         class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                                        @if ($user->role == 'staff') bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white
                                        @elseif($user->role == 'kasie') bg-green-100 text-green-800 dark:bg-green-600 dark:text-white
                                        @elseif($user->role == 'kabag') bg-purple-100 text-purple-800 dark:bg-purple-600 dark:text-white
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-white @endif">
                                         {{ ucfirst($user->role) }}
                                     </span>
                                 </td>
                                 <td class="px-4 py-2"><span class="font-semibold">{{ $user->sisa_cuti }}</span> hari
                                 </td>
                                 <td class="px-4 py-2">
                                     <form action="{{ route('hrd.quota.update', $user) }}" method="POST"
                                         class="flex items-center gap-2">
                                         @csrf
                                         @method('POST')
                                         <input type="number" name="sisa_cuti" value="{{ $user->sisa_cuti }}"
                                             min="0"
                                             class="w-16 rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 text-xs focus:border-primary-500 focus:ring-primary-500">
                                         <button type="submit"
                                             class="px-2 py-0.5 bg-primary-600 text-white text-xs rounded-md hover:bg-primary-500 focus:ring-2 focus:ring-primary-500">
                                             Update
                                         </button>
                                     </form>
                                 </td>
                             </tr>
                         @empty
                             <tr>
                                 <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                     Tidak ada data karyawan ditemukan.
                                 </td>
                             </tr>
                         @endforelse
                     </tbody>
                 </table>
             </div>
             @if ($users->hasPages())
                 <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">{{ $users->links() }}</div>
             @endif
         </div>
     </div>
     <x-toast-notification />
 </x-app-layout>
