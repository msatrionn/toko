<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $karyawan = DB::table('karyawan')
            ->join('users', 'users.id', 'karyawan.user_id')
            ->get();
        return view('admin.karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $acak = random_int(1, 9999999);
        $request->validate([
            'username' => 'unique:users'
        ]);
        User::create([
            'id' => $acak,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'karyawan',
        ]);
        Karyawan::create([
            'user_id' => $acak,
            'nama_karyawan' => $request->nama_karyawan,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
        ]);

        return redirect('admin/karyawan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::all();
        $karyawan = Karyawan::join('users', 'users.id', 'karyawan.user_id')->where('id_karyawan', $id)->first();
        return view('admin.karyawan.edit', compact('karyawan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $karyawan = DB::table('karyawan')->where('id_karyawan', $id);
        $karyawan_user = User::join('karyawan', 'karyawan.user_id', 'users.id')
            ->where('id_karyawan', $id)->first();

        $karyawan->update([
            'nama_karyawan' => $request->nama_karyawan,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        $karyawan_user->update([
            'username' => $request->username
        ]);

        return redirect('admin/karyawan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $karyawan = DB::table('karyawan')->where('id_karyawan', $id);
        $karyawan->delete();
    }
}
