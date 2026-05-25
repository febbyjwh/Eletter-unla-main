<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-xl font-bold mb-4">Arsip Saya</h2>

    <h3 class="font-semibold mb-2">Surat Masuk</h3>
    <ul class="mb-4">
        @foreach ($suratMasuk as $surat)
            <li class="border-b py-1 text-sm">{{ $surat->no_surat }} — {{ $surat->perihal }}</li>
        @endforeach
    </ul>

    <h3 class="font-semibold mb-2">Surat Keluar</h3>
    <ul>
        @foreach ($suratKeluar as $surat)
            <li class="border-b py-1 text-sm">{{ $surat->no_surat }} — {{ $surat->perihal }}</li>
        @endforeach
    </ul>
</div>
