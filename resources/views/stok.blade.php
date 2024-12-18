@extends('template.store')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="main-content">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Stok Barang</h5>
            </div>
            <div class="card-body">
                <button class="btntb mb-3" onclick="showNewStockPopup()">+ New Stock</button>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($barang as $barang)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $barang->nama }}</td>
                                <td>{{ $barang->harga_beli }}</td>
                                <td>{{ $barang->harga_jual }}</td>
                                <td>{{ $barang->stok }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editStock({{ $barang->id }}, '{{ $barang->nama }}', {{ $barang->harga_beli }}, {{ $barang->harga_jual }}, {{ $barang->stok }})">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('barang.destroy', $barang->id) }}')">Delete</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <!-- GPT BANTUAN popup tambah data-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showNewStockPopup() {
            Swal.fire({
                title: 'Tambah Stok Baru',
                showCloseButton: true, // Tambahkan tombol X
                html: `
                    <input type="text" id="nama" class="swal2-input" placeholder="Nama Barang">
                    <input type="number" id="harga_beli" class="swal2-input" placeholder="Harga Beli">
                    <input type="number" id="harga_jual" class="swal2-input" placeholder="Harga Jual">
                    <input type="number" id="stok" class="swal2-input" placeholder="Stok">
                `,
                confirmButtonText: 'Tambah',
                focusConfirm: false,
                preConfirm: () => {
                    const nama = document.getElementById('nama').value;
                    const harga_beli = document.getElementById('harga_beli').value;
                    const harga_jual = document.getElementById('harga_jual').value;
                    const stok = document.getElementById('stok').value;

                    if (!nama || !harga_beli || !harga_jual || !stok) {
                        Swal.showValidationMessage(`Semua field harus diisi!`);
                    }

                    return { nama, harga_beli, harga_jual, stok };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim data ke server
                    fetch('/stok/tambah', {
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
    <!-- gpt popup edit -->
    <script>
    function editStock(id, nama, harga_beli, harga_jual, stok) {
        Swal.fire({
            title: 'Edit Stok Barang',
            showCloseButton: true, // Tambahkan tombol X
            html: `
                <input type="text" id="edit-nama" class="swal2-input" value="${nama}" placeholder="Nama Barang">
                <input type="number" id="edit-harga_beli" class="swal2-input" value="${harga_beli}" placeholder="Harga Beli">
                <input type="number" id="edit-harga_jual" class="swal2-input" value="${harga_jual}" placeholder="Harga Jual">
                <input type="number" id="edit-stok" class="swal2-input" value="${stok}" placeholder="Stok">
            `,
            confirmButtonText: 'Simpan',
            focusConfirm: false,
            preConfirm: () => {
                const nama = document.getElementById('edit-nama').value;
                const harga_beli = document.getElementById('edit-harga_beli').value;
                const harga_jual = document.getElementById('edit-harga_jual').value;
                const stok = document.getElementById('edit-stok').value;

                if (!nama || !harga_beli || !harga_jual || !stok) {
                    Swal.showValidationMessage(`Semua field harus diisi!`);
                }

                return { nama, harga_beli, harga_jual, stok };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data ke server untuk diperbarui
                fetch(`/stok/edit/${id}`, {
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
                            location.reload(); // Reload halaman untuk melihat data terbaru
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