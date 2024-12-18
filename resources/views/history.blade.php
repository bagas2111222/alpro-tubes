@extends('template.store')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-content">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">History</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Penjualan</th>
                            <th>Total Harga</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualan as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->tgl_penjualan }}</td>
                            <td>{{ $user->total_harga }}</td>
                            <td>{{ $user->pembayaran }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <a href="/admin/history/{{ $user->id }}"><button class="btn btn-success btn-sm">Detail</button></a>
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

@endsection