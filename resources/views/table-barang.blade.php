@extends('template.penjualan')
@section('content')

<div class="main-content">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Barang Dipilih</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Diskon (%)</th>
                        <th>Total Harga</th>
                        <th>Total Harga Setelah Diskon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangData as $item)
                    <tr>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td>{{ $item['jumlah'] }}</td>
                        <td>{{ $item['disc_rate'] }}%</td>
                        <td>{{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                        <td>{{ number_format($item['total_harga_diskon'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

                <button class="btn btn-primary mb-3" id="btnBayar">Bayar</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('btnBayar').addEventListener('click', function () {
    let totalHarga = {{ $totalHargaSetelahDiskon }}; // Total harga dari server

    Swal.fire({
        title: 'Pembayaran',
        text: "Total Bayar: Rp " + totalHarga,
        input: 'number',
        inputPlaceholder: 'Masukkan jumlah uang',
        showCancelButton: true,
        confirmButtonText: 'Bayar',
        preConfirm: (uangDibayar) => {
            if (!uangDibayar || uangDibayar < totalHarga) {
                Swal.showValidationMessage('Uang tidak cukup!');
            } else {
                return fetch("{{ route('bayar', ['id' => $penjualan->id]) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        pembayaran: uangDibayar,
                        total_harga: totalHarga,
                        status: 'selesai'
                    })
                }).then(response => response.json());
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pembayaran telah diterima.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '/penjualan'; // Arahkan ke halaman /penjualan
            });
        }
    });
});

</script>
@endsection
