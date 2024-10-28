<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function index()
    {
        $id = session('user_id');
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Home', 'profile']
        ];
        $page = (object) [
            'title' => 'Profile Anda'
        ];
        $activeMenu = 'profile'; // set menu yang sedang aktif
        $user = UserModel::with('level')->find($id);
        $level = LevelModel::all(); // ambil data level untuk filter level
        return view('profile.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'user' => $user,'activeMenu' => $activeMenu]);
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        $breadcrumb = (object) ['title' => 'Detail User', 'list' => ['Home', 'User', 'Detail']];
        $page = (object) ['title' => 'Detail user'];
        $activeMenu = 'user'; // set menu yang sedang aktif
        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('profile.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'nullable|integer',
            'username' => 'nullable|max:20|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'nullable|max:100',
            'password' => 'nullable|min:6|max:20'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $user = UserModel::find($id);
        if ($user) {
            $data = $request->only(['username', 'nama', 'level_id']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            $user->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    return redirect('/');
}


    public function edit_foto(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('profile.edit_foto', ['user' => $user, 'level'=>$level]);
    }

    public function update_foto(Request $request, $user_id) {
        // Validasi file foto
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Ambil pengguna
        $user = UserModel::find($user_id);
    
        // Hapus foto lama jika ada
        if ($user->foto) {
            Storage::delete($user->foto);
        }
    
        // Simpan foto baru
        $path = $request->file('foto')->store('profile_photos', 'public');
    
        // Update kolom foto di database
        $user->foto = $path;
        $user->save();
    
        return response()->json(['status' => true, 'message' => 'Foto profil berhasil diperbarui!']);
    }
    


}