@extends('layouts.template')

@section('content')
<div id="profileModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profil Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    @if($user && $user->foto)
                        <img class="rounded-circle border border-primary p-2 mb-3" width="150" height="150" src="{{ asset($user->foto) }}" alt="Profile Picture">
                    @else
                        <img class="rounded-circle border border-primary p-2 mb-3" width="150" height="150" src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture">
                    @endif
                    <h5>{{ $user->username }}</h5>
                    <p>{{ $user->nama }}</p>
                </div>
                <table class="table">
                    <tr><th>ID</th><td>{{ $user->user_id }}</td></tr>
                    <tr><th>Level</th><td>{{ $user->level->level_nama }}</td></tr>
                    <tr><th>Username</th><td>{{ $user->username }}</td></tr>
                    <tr><th>Nama</th><td>{{ $user->nama }}</td></tr>
                    <tr><th>Password</th><td>********</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" onclick="openModal('{{ url('/profil/' . $user->user_id . '/edit_ajax') }}')">Edit Profil</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
