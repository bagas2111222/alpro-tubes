@extends('template.store')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="main-content">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Diskon Barang</h5>
            </div>
            <div class="card-body">
                <button class="btntb mb-3" onclick="showNewStockPopup()">+ Diskon</button>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Berakhir</th>
                                <th>Tingkat Diskon</th>
                                <th>Harga Awal</th>
                                <th>Harga Setelah Diskon</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($diskon as $diskon)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $diskon->barang->nama }}</td>
                                <td>{{ date('H:i:s d-m-Y ', strtotime($diskon->start_time)) }}</td>
                                <td>{{ date('H:i:s d-m-Y ', strtotime($diskon->end_time)) }}</td>
                                <td>{{ $diskon->disc_rate }}</td>
                                <td>{{ number_format($diskon->barang->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ number_format($diskon->barang->harga_jual - ($diskon->barang->harga_jual * $diskon->disc_rate / 100), 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="showEditStockPopup('{{ $diskon->id }}', '{{ $diskon->barang->id }}', '{{ $diskon->start_time }}', '{{ $diskon->end_time }}', '{{ $diskon->disc_rate }}')">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('diskon.destroy', $diskon->id) }}')">Delete</button>

                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Post belum Tersedia.
                            </div>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>



    <!-- GPT BANTUAN popup tambah data-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showNewStockPopup() {
            Swal.fire({
                title: 'Tambah Stok Baru',
                showCloseButton: true, // Tambahkan tombol X
                html: `
                    <select id="barang_id" class="swal2-input">
                        <option value="" disabled selected>Pilih barang</option>
                        @forelse ($barang as $barang)
                        <option value="{{$barang->id}}">{{$barang->nama}}</option>
                        @empty
                            <div class="alert alert-danger">
                                Data Post belum Tersedia.
                            </div>
                        @endforelse
                    </select>                    
                    <input type="datetime-local" id="start_time" class="swal2-input" placeholder="start time">
                    <input type="datetime-local" id="end_time" class="swal2-input" placeholder="end time">
                    <input type="number" id="disc_rate" class="swal2-input" placeholder="disc rate">
                `,
                confirmButtonText: 'Tambah',
                focusConfirm: false,
                preConfirm: () => {
                    const barang_id = document.getElementById('barang_id').value;
                    const start_time = document.getElementById('start_time').value;
                    const end_time = document.getElementById('end_time').value;
                    const disc_rate = document.getElementById('disc_rate').value;

                    if (!barang_id || !start_time || !end_time || !disc_rate) {
                        Swal.showValidationMessage(`Semua field harus diisi!`);
                    }
                    if(disc_rate < 0 || disc_rate > 100) {
                        Swal.showValidationMessage(`Diskon rate harus antara 0 dan 100!`);
                        return false; // Prevent the submission if disc_rate is out of range
                    }

                    return { barang_id, start_time, end_time, disc_rate };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim data ke server
                    fetch('/diskon/tambah', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Data berhasil ditambahkan.', 'success').then(() => {
                                location.reload(); // Reload halaman untuk melihat data baru
                            });
                        } else {
                            Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    });
                }
            });
        }
    </script>
<script>
    function showEditStockPopup(id, barang_id, start_time, end_time, disc_rate) {
        Swal.fire({
            title: 'Edit Stok Barang',
            showCloseButton: true, // Tambahkan tombol X
            html: `
                <select id="barang_id" class="swal2-input">
                    <option value="" disabled>Pilih barang</option>
                    @foreach ($edit as $item)
                        <option value="{{$item->id}}">{{ $item->nama }}</option>
                    @endforeach
                </select>

                <input type="datetime-local" id="start_time" class="swal2-input" value="${start_time}">
                <input type="datetime-local" id="end_time" class="swal2-input" value="${end_time}">
                <input type="number" id="disc_rate" class="swal2-input" value="${disc_rate}" placeholder="Diskon rate">
            `,
            confirmButtonText: 'Update',
            focusConfirm: false,
            preConfirm: () => {
                const new_barang_id = document.getElementById('barang_id').value;
                const new_start_time = document.getElementById('start_time').value;
                const new_end_time = document.getElementById('end_time').value;
                const new_disc_rate = document.getElementById('disc_rate').value;

                if (!new_barang_id || !new_start_time || !new_end_time || !new_disc_rate) {
                    Swal.showValidationMessage(`Semua field harus diisi!`);
                }
                if(new_disc_rate < 0 || new_disc_rate > 100) {
                    Swal.showValidationMessage(`Diskon rate harus antara 0 dan 100!`);
                    return false;
                }

                return { new_barang_id, new_start_time, new_end_time, new_disc_rate };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/diskon/update/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', 'Data berhasil diperbarui.', 'success').then(() => {
                            location.reload(); // Reload halaman
                        });
                    } else {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                });
            }
        });
    }
</script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload(); // Reload halaman
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                });
            }
        });
    }
</script>

@endsection