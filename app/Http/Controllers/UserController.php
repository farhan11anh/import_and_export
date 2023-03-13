<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class UserController extends Controller
{

    public function index(){
        return view('welcome', [
            'users' => \App\Models\User::all()
        ]);
    }

    public function exportPDF(){
        $data = User::all();
        $pdf = PDF::loadView('pdf_view', ['data'=>$data]);

        return $pdf->download('user.pdf');
    }

    public function destroy($id){
        $post = User::findOrFail($id);
        $post->delete();

        if($post){
            return redirect()
                ->route('user.index')
                ->with([
                    'success' => 'user telah dihapus'
                ]);
        } else {
            return redirect()
                ->route('user.index')
                ->with([
                    'error' => 'data tidak dihapus'
                ]);
        }
    }
}
