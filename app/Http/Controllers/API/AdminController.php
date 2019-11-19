<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Pekerja;
use App\DetailPekerja;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:4'
        ]);
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:4'
        ]);
        $hash = Hash::make($request['password']);
        $request->merge(['password' => $hash]);
        $user->update($request->all());
        return ['message' => 'data changed'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);

        return ['message' => 'Admin Deleted'];
    }

    public function showPekerja()
    {
        // return Pekerja::find(1)->listPekerja;
        return Pekerja::with('listPekerja')->get();
    }

    public function addPekerja(Request $req){
        // $this->validate($req,[
        //     'email' => 'required|string|email|max:191|unique:pekerja',
        //     'password' => 'required|string|min:4',
        //     'status' => 'required|string',
        //     'nama' => 'required|string|max:255',
        //     'nomorKTP' => 'required|number|max:18',
        //     'ttl' => 'required',
        //     'tel' => 'required|number|max:13',
        //     'alamat' => 'required|string|max:255',
        //     'keahlian' => 'sometimes|string|max:1000',
        //     'kondisiKhusus' => 'sometimes|string|max:1000',
        //     'agama' => 'required|string|max:255',
        //     'fotoKTP' => 'sometimes',
        //     'fotoDiri' => 'sometimes'
        // ]);
        
        $pekerja = Pekerja::create(['email' => $req->email,
                                    'password' => Hash::make($req->password),
                                    'status' => $req->status,
                                ]);

        $name = time().'.' . explode('/', explode(':', substr($req->fotoKTP, 0, strpos($req->fotoKTP, ';')))[1])[1];
        \Image::make($req->fotoKTP)->save(public_path('img/profile/').$name);                        
        $req->merge(['fotoKTP' => $name]); 
        
        $name1 = time().'.' . explode('/', explode(':', substr($req->fotoDiri, 0, strpos($req->fotoDiri, ';')))[1])[1];
        \Image::make($req->fotoDiri)->save(public_path('img/profile/').$name1);                        
        $req->merge(['fotoDiri' => $name1]); 

        $detail = DetailPekerja::create([
            'id_pekerja' => $pekerja->id,
            'nama' => $req->nama,
            'nomorKTP' => $req->nomorKTP,
            'ttl' => $req->ttl,
            'tel' => $req->tel,
            'alamat' => $req->alamat,
            'keahlian' => $req->keahlian,
            'kondisiKhusus' => $req->kondisiKhusus,
            'agama' => $req->agama,
            'fotoKTP' => $req->fotoKTP,
            'fotoDiri' => $req->fotoDiri
        ]);
        
        return ['message' => 'Berhasil'];
    }

    public function editPekerja(Request $request, $id){
        // $this->validate($req,[
        //     'email' => 'required|string|email|max:191|unique:pekerja',
        //     'password' => 'required|string|min:4',
        //     'status' => 'required|string',
        //     'nama' => 'required|string|max:255',
        //     'nomorKTP' => 'required|number|max:18',
        //     'ttl' => 'required',
        //     'tel' => 'required|number|max:13',
        //     'alamat' => 'required|string|max:255',
        //     'keahlian' => 'required|string|max:1000',
        //     'kondisiKhusus' => 'required|string|max:1000',
        //     'agama' => 'required|string|max:255',
        //     'fotoKTP' => 'sometimes',
        //     'fotoDiri' => 'sometimes'
        // ]);
        $hash = Hash::make($request['password']);
        $request->merge(['password' => $hash]);
        $pekerja = Pekerja::findOrFail($id);
        $pekerja->update($request->all());
        
        $user = DetailPekerja::findOrFail($id);
        $currentPhoto = $user->fotoKTP;
        
        if($request->fotoKTP != $user->fotoKTP){
            $name = time().'.' . explode('/', explode(':', substr($request->fotoKTP, 0, strpos($request->fotoKTP, ';')))[1])[1];
            \Image::make($request->fotoKTP)->save(public_path('img/profile/').$name);
            $request->merge(['fotoKTP' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;

            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        
        $currentPhoto1 = $user->fotoDiri;
        if($request->fotoDiri != $user->fotoDiri){
            $name1 = time().'.' . explode('/', explode(':', substr($request->fotoDiri, 0, strpos($request->fotoDiri, ';')))[1])[1];
            \Image::make($request->fotoDiri)->save(public_path('img/profile/').$name1);                        
            $request->merge(['fotoDiri' => $name1]); 

            $userPhoto1 = public_path('img/profile/').$currentPhoto1;

            if(file_exists($userPhoto1)){
                @unlink($userPhoto1);
            }
        }
        $user->update($request->all());
    }

    public function hapusPekerja($id)
    {
        $detail = DetailPekerja::findOrFail($id);
        $detail->delete();
        $pekerja = Pekerja::findOrFail($id);
        $pekerja->delete();       

        return ['message' => 'Admin Deleted'];
    }
}
