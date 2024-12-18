<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <style>
    /* CSS tambahan untuk tampilan */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .content {
        flex: 1;
        padding-bottom: 80px;
        /* Tambahan padding untuk fixed button */
    }

    .product-box {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin: 10px 0;
    }

    /* Fixed button */
    .fixed-button {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 2px;
        z-index: 1000;
    }
    </style>
</head>

<body class="background">

<nav class="navbar custom-bg" data-bs-theme="light">
    <div class="container-fluid d-flex align-items-center">
        <!-- Tombol Logout di kiri -->

        <!-- Tulisan USER | Cashier -->
        <a class="navbar-brand custom-text">USER | Cashier</a>
        <a href="/logout" class="navbar-brand custom-text me-2 logout-color">Logout</a>


        <!-- Form Pencarian -->
        <form class="d-flex ms-auto" role="search">
            <input class="form-control me-2 shadow-thick" id="searchInput" type="search" placeholder="Cari Nama Barang" aria-label="Search" />
            <button class="btn btn-outline-success custom-outline shadow" type="button" onclick="searchProducts()">Cari</button>
        </form>
    </div>
</nav>


    <div class="product-container">
        @forelse ($barang as $barang)
        <div class="product-box">
            <p class="product-title">{{ $barang->nama }}</p>
            <p class="product-price">{{ $barang->harga_jual }}</p>
            <p class="product-stok" hidden>{{ $barang->stok }}</p>
            <div class="product-quantity">
                <button class="quantity-btn" onclick="decrement(this)">-</button>
                <p class="quantity-value">0</p>
                <button class="quantity-btn" onclick="increment(this)">+</button>
            </div>
            <p>Stok: {{ $barang->stok }} </p>
        </div>
        @empty
        <div class="alert alert-danger">
            Data Barang belum Tersedia.
        </div>
        @endforelse
    </div>

    <!-- Fixed Button -->
    <div class="fixed-button">
        <form action="{{ route('barang.selected') }}" method="POST" id="barangForm">
            @csrf
            <input type="hidden" name="selectedItems" id="selectedItems">
            <div class="center-box">
                <button type="submit" class="btn btn-primary w-50 button-selected">
                    Lanjutkan ke Tabel Barang Terpilih
                </button>
            </div>
        </form>
    </div>


    <script>
    function searchProducts() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase(); // Ambil input pencarian
        const productBoxes = document.querySelectorAll('.product-box'); // Ambil semua produk

        productBoxes.forEach((box) => {
            const productName = box.querySelector('.product-title').textContent.toLowerCase(); // Ambil nama produk

            // Tampilkan atau sembunyikan berdasarkan query
            if (productName.includes(searchQuery)) {
                box.style.display = 'block'; // Tampilkan jika cocok
            } else {
                box.style.display = 'none'; // Sembunyikan jika tidak cocok
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const form = document.getElementById('barangForm');
    const selectedItemsInput = document.getElementById('selectedItems');

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Mencegah form dikirim langsung

        const productBoxes = document.querySelectorAll('.product-box');
        const selectedItems = [];
        let isStockExceeded = false; // Flag untuk mengecek stok berlebih

        productBoxes.forEach((box) => {
            const name = box.querySelector('.product-title').textContent;
            const price = box.querySelector('.product-price').textContent;
            const stok = box.querySelector('.product-stok').textContent;
            const quantity = parseInt(box.querySelector('.quantity-value').textContent);

            // Validasi stok: jika jumlah > stok
            if (quantity > stok) {
                isStockExceeded = true;
            }

            // Hanya ambil barang dengan quantity lebih dari 0
            if (quantity > 0) {
                selectedItems.push({
                    nama: name,
                    harga: price,
                    jumlah: quantity
                });
            }
        });

        // Jika stok melebihi batas, tampilkan popup
        if (isStockExceeded) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Terlampaui',
                text: 'Anda melebihi batas stok yang tersedia.',
            });
        } else {
            // Jika stok valid, lanjutkan ke halaman berikutnya
            selectedItemsInput.value = JSON.stringify(selectedItems);
            form.submit();
        }
    });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>




    <!-- ini adalah fungsi tambah dan kurang value produk menu -->
    <script>
    function increment(button) {
        // Mendapatkan elemen jumlah produk
        const quantityValueElement = button.parentElement.querySelector('.quantity-value');

        // Mengubah teks menjadi angka dan menambah 1
        let quantity = parseInt(quantityValueElement.textContent);
        quantity++;

        // Memperbarui nilai pada elemen
        quantityValueElement.textContent = quantity;
    }

    function decrement(button) {
        // Mendapatkan elemen jumlah produk
        const quantityValueElement = button.parentElement.querySelector('.quantity-value');

        // Mengubah teks menjadi angka dan mengurangi 1 jika lebih besar dari 0
        let quantity = parseInt(quantityValueElement.textContent);
        if (quantity > 0) {
            quantity--;
        }

        // Memperbarui nilai pada elemen
        quantityValueElement.textContent = quantity;
    }
    </script>

</body>

</html>