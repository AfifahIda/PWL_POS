<!DOCTYPE html>
<html>
    <head>
        <title>Form Tambah Data User</title>
    </head>
    <body>
        <h1>Form Tambah Data User</h1>
        <form method="post" action="/user/tambah_simpan">

            {{ csrf_field() }}

        <label>Username</label>
        <input type="text" nama="username" placeholder="Masukkan Username">
        <br>
        <label>Nama</label>
        <input type="text" nama="nama" placeholder="Masukkan Nama">
        <br>
        <label>Password</label>
        <input type="text" nama="password" placeholder="Masukkan Password">
        <br>
        <label>Level ID</label>
        <input type="text" nama="level_id" placeholder="Masukkan ID Level">
        <br>
        <input type="submit" class="btn btn-success" value="Simpan">
        
        </form>
    </body>
</html>