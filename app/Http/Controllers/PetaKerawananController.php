<?php

namespace App\Http\Controllers;

use App\Http\Requests\petakerawanan\{
    PostPetaKerawananRequest,
    UpdatePetaKerawananRequest
};
use App\Imports\PetaKerawananImport;
use App\Models\PetaKerawanan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PetaKerawananController extends Controller
{
    public function __construct()
    {
        $this->middleware('isCanAdmin');
    }

    public function index()
    {
        $datas = PetaKerawanan::paginate(5);
        return view('petakerawanan.index', compact('datas'));
    }

    public function create()
    {
        return view('petakerawanan.form');
    }

    public function store(PostPetaKerawananRequest $request)
    {
        try {

            $data = [
                'jenis' => $request->jenis
            ];

            
            
            PetaKerawanan::create($data);
            // dd($request->all()); 

            return redirect()->route('petakerawanan.index')->with('msg_success', 'Berhasil membuat peta kerawanan');
        } catch (\Throwable $th) {
            return redirect()->route('petakerawanan.index')->with('msg_error', 'Berhasil membuat peta kerawanan');
        }
    }

    public function show($id)
    {   
        $data = PetaKerawanan::find($id);
        return view('petakerawanan.show', compact('data'));
    }

    public function edit($id)
    {
        $data = PetaKerawanan::find($id);
        return view('petakerawanan.form', compact('data'));
    }

    public function update(UpdatePetaKerawananRequest $request, $id)
    {
        try {
            $petaKerawanan = PetaKerawanan::find($id);
            $data = [
                'jenis' => $request->jenis
            ];

            $petaKerawanan->update($data);

            return redirect()->route('petakerawanan.index')->with('msg_success', 'Berhasil mengubah peta kerawanan');
        } catch (\Throwable $th) {
            return redirect()->route('petakerawanan.index')->with('msg_error', 'Berhasil mengubah peta kerawanan');
        }
    }

    public function destroy($id)
    {
        $petaKerawanan = PetaKerawanan::find($id);
        if(!$petaKerawanan) return abort(404);

        $petaKerawanan->delete();

        return redirect()->route('petakerawanan.index')->with('msg_success', 'Berhasil menghapus peta kerawanan');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new PetaKerawananImport, $request->file);
            return back()->with('msg_success', 'Berhasil membuat peta kerawanan');
        } catch (\Throwable $th) {
            return back()->with('msg_error', 'Gagal membuat peta kerawanan');
        }
    }
}