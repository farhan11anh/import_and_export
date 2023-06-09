<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PDF;

use Maatwebsite\Excel\Facades\Excel;

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

    public function preview(Request $request){
        // dd($request->file('file')->getRealPath());
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray('', $path, null, \Maatwebsite\Excel\Excel::TSV)[0];
        $header = $data[0];
        // var_dump(count($header));

        for($i = 0; $i < count($data)-1; $i++){
            $j = $i+1;
            $content[$i]['name'] = $data[$j][0];
            $content[$i]['email'] = $data[$j][1];
            $content[$i]['password'] = $data[$j][2];
        }

        // dd($content);
        // echo $content;
        // echo 'aaa';
        $response = [
            'status' => 'success',
            'msg' => 'Setting created successfully',
            'content' => $content
        ];

        // $response['status'] = 'success';
        return response()->json($response);

    }
}
