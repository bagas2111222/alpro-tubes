<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Canteen Income</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/employee.css')}}">

</head>

<div class="sidebar">
    <br>
    <h1>Code Ranger</h1>
    <a href="{{ url('/admin/history') }}"><i class="bx bx-home-alt-2" style="color: #ffffff"></i> History Penjualan</a>
    <a href="{{ url('/admin/employee') }}"><i class="bx bx-user-circle" style="color: #ffffff"></i> Daftar Karyawan</a>

    <a href="{{ url('/admin/diskon') }}"><i class="bx bxs-discount" style="color: #ffffff"></i> Diskon</a>
    <a href="{{ url('/admin/stok') }}"><i class="bx bxs-package"></i> Stok</a>
</div>
<a href="/logout"><i class="bx bx-log-in" style="color: #ffffff" id="out"> Logout</i></a>
<!-- <div class="category">
    <div class="category-box">
        <p class="nominal-title">
            Rp10.000,00
            <img src="{{asset('icon/income.png')}}" alt="Income Icon" height="50" width="50" />
        </p>
        <p class="category-price">Income</p>
    </div>

    <div class="category-box">
        <p class="nominal-title">
            500 Stock
            <img src="{{asset('icon/ready-stock.png')}}" alt="Stock Icon" height="50" width="50" />
        </p>
        <p class="category-price">Stock Item</p>
    </div>
</div> -->
@yield('content')

</body>

</html>