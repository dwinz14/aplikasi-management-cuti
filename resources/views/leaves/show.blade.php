<x-app-layout>
    <h2>Detail Pengajuan Cuti</h2>
    <p><strong>Pemohon:</strong> {{ $leave->user->name }}</p>
    <p><strong>Tanggal:</strong> {{ $leave->start_date }} s/d {{ $leave->end_date }} ({{ $leave->total_hari }} hari)</p>
    <p><strong>Alasan:</strong> {{ $leave->alasan }}</p>

    <hr>

    {{-- Pengganti --}}
    @if(auth()->id() === $leave->pengganti_id && $leave->status_pengganti === 'pending')
        <form method="POST" action="{{ route('cuti.approve.pengganti', $leave) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">Approve sebagai Pengganti</button>
        </form>
        <form method="POST" action="{{ route('cuti.reject.pengganti', $leave) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    @endif

    {{-- Kadiv --}}
    @if(auth()->id() === $leave->kadiv_id && $leave->status_kadiv === 'pending')
        <form method="POST" action="{{ route('cuti.approve.kadiv', $leave) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">Approve sebagai Kadiv</button>
        </form>
        <form method="POST" action="{{ route('cuti.reject.kadiv', $leave) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    @endif

    {{-- HRD --}}
    @if(auth()->user()->role === 'hrd' && $leave->status_hrd === 'pending')
        <form method="POST" action="{{ route('cuti.approve.hrd', $leave) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">Approve HRD</button>
        </form>
        <form method="POST" action="{{ route('cuti.reject.hrd', $leave) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    @endif

    <br>
    <a href="{{ route('cuti.index') }}">← Kembali ke Daftar</a>
</x-app-layout>
