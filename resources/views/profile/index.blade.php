@extends('layouts.template')

@section('content')
    <div id="profileModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <!-- Konten modal akan di-load dengan AJAX -->
            </div>
        </div>
    </div>
    
    <div class="container bg-light mt-5 p-4 shadow-sm rounded">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <div class="card border-0">
                    <div class="card-body">
                        @if($user && $user->foto)
                            <img class="rounded-circle border border-primary p-2 mb-3" width="150px" height="150px" src="{{ asset($user->foto) }}" alt="Profile Picture">
                        @else
                            <img class="rounded-circle border border-primary p-2 mb-3" width="150px" height="150px" src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture">
                        @endif
                        <div class="mt-3">
                        </div><button class="btn btn-outline-info" onclick="openModal('{{ url('/profil/' . session('user_id') . '/edit_foto') }}')">
                            <i class="fas fa-camera"></i> Upload Foto Baru
                        </button>
                        <small class="form-text text-muted mt-2">Foto Profile</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Pengaturan Profil</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th scope="row">ID</th>
                                <td>{{ $user->user_id }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Level</th>
                                <td>{{ $user->level->level_nama }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Username</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Nama</th>
                                <td>{{ $user->nama }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Password</th>
                                <td>********</td>
                            </tr>
                        </table>
                        <div class="text-center mt-3">
                            <button onclick="modalAction('{{ url('/profil/' . session('user_id') .'/edit_ajax') }} ')" class="btn btn-warning btn-sm"> 
                                <i class="fas fa-edit"></i> Edit Profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
    </div>
@endsection

@push('css')
    <style>
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table-hover tr:hover {
            background-color: #f2f2f2;
        }
    </style>
@endpush

@push('js')
    <script>
        function openModal(url = '') {
            $('#profileModal').load(url, function() {
                $('#profileModal').modal('show');
            });
        }
        
        $(document).ready(function() {
            var profileTable = $('#profile').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('penjualan/list') }}",
                    type: "POST",
                    data: function(d) {
                        d.user_id = $('#user_id').val();
                    }
                },
                columns: [
                    { data: 'user_id', name: 'user_id' },
                    { data: 'level', name: 'level' },
                    { data: 'username', name: 'username' },
                    { data: 'nama', name: 'nama' }
                ],
                responsive: true,
                autoWidth: false
            });
            $('#user_id').on('change', function() {
                profileTable.ajax.reload();
            });
        });
    </script>
@endpush