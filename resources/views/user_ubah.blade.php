<!DOCTYPE html>
<html>
    <head>
        <title>Form Ubah Data User</title>
    </head>
    <body>
        <h1>Form Ubah Data User</h1>
        <a href="/user">Kembali</a>
        <form method="post" action="/user/ubah_simpan/{{ $data->user_id }}">

            {{ csrf_field() }}
            {{ method_field('PUT') }}

        <label>Username</label>
        <input type="text" nama="username" placeholder="Masukkan Username" value="{{ $data->username }}">
        <br>
        <label>Nama</label>
        <input type="text" nama="nama" placeholder="Masukkan Nama" value="{{ $data->nama }}">
        <br>
        <label>Password</label>
        <input type="text" nama="password" placeholder="Masukkan Password" value="{{ $data->password }}">
        <br>
        <label>Level ID</label>
        <input type="text" nama="level_id" placeholder="Masukkan ID Level" value="{{ $data->level_id }}">
        <br>
        <input type="submit" class="btn btn-success" value="Ubah">
        
        </form>
    </body>
</html>