@extends('template.store')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-content">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Karyawan</h5>
        </div>

        <div class="card-body">
        <button class="btntb mb-3" onclick="showNewStockPopup()">+ New employee</button>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>*****</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="showEditPopup('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser('{{ $user->id }}')">Delete</button>
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
            title: 'Tambah Employee Baru',
            showCloseButton: true,
            html: `
                <input type="text" id="name" class="swal2-input" placeholder="Nama">
                <input type="email" id="email" class="swal2-input" placeholder="Email">
                <input type="password" id="password" class="swal2-input" placeholder="Password">
                <select id="role" class="swal2-input">
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </select>
            `,
            confirmButtonText: 'Tambah',
            focusConfirm: false,
            preConfirm: () => {
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const role = document.getElementById('role').value;

                // Validasi semua field harus diisi
                if (!name || !email || !password || !role) {
                    Swal.showValidationMessage('Semua field harus diisi!');
                    return false;
                }

                return {
                    name,
                    email,
                    password,
                    role
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/user/tambah', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Data berhasil ditambahkan.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', 'Email sudah terdaftar atau ada kesalahan lain.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Gagal!', 'Terjadi kesalahan pada server.', 'error');
                    });
            }
        });
    }
    </script>

    <!-- Edit Popup -->
<script>
function showEditPopup(id, name, email, role) {
    Swal.fire({
        title: 'Edit Employee',
        showCloseButton: true,
        html: `
            <input type="hidden" id="edit-id" value="${id}">
            <input type="text" id="edit-name" class="swal2-input" placeholder="Nama" value="${name}">
            <input type="email" id="edit-email" class="swal2-input" placeholder="Email" value="${email}">
            <input type="password" id="edit-password" class="swal2-input" placeholder="Password (Kosongkan jika tidak ingin diganti)">
            <select id="edit-role" class="swal2-input">
                <option value="admin" ${role === 'admin' ? 'selected' : ''}>Admin</option>
                <option value="kasir" ${role === 'kasir' ? 'selected' : ''}>Kasir</option>
            </select>
        `,
        confirmButtonText: 'Simpan Perubahan',
        focusConfirm: false,
        preConfirm: () => {
            const id = document.getElementById('edit-id').value;
            const name = document.getElementById('edit-name').value;
            const email = document.getElementById('edit-email').value;
            const password = document.getElementById('edit-password').value;
            const role = document.getElementById('edit-role').value;

            if (!name || !email || !role) {
                Swal.showValidationMessage('Semua field kecuali password harus diisi!');
                return false;
            }

            return { id, name, email, password, role };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/user/update/${result.value.id}`, {
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
                    Swal.fire('Berhasil!', 'Data berhasil diubah.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Gagal!', 'Terjadi kesalahan pada server.', 'error');
            });
        }
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Hapus Data',
        text: "Apakah Anda yakin ingin menghapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/user/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                }
            });
        }
    });
}
</script>

@endsection