<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function upload(Request $request){
        if ($request->hasFile('file')){
            $type = $request->file('file')->getClientOriginalExtension();
            $Fname = ($request->post('file_name') == '') ? Auth::user()->id . '_' . time() . '.' . $type : $request->post('file_name');
            $request->file('file')->move(public_path().'/uploads/'.$request->post('directory'),$Fname);
            return response($Fname,200);
        }else{
            return response()->json(['success' => 0,'message' => 'file empty']);
        }
    }
    public function delete(Request $request,$name)
    {
        if ($request->post($name) != ''){
            if(file_exists(public_path().'/uploads/'.$request->post($name))){
                unlink(public_path().'/uploads/'.$request->post($name));
            }
            return response(1,200);
        }else{
            return response()->json(['success' => 0,'message' => 'file empty']);
        }
    }
    public function delete_file($file)
    {
        if(file_exists(public_path().'/uploads/'.$file) && $file != ''){
            unlink(public_path().'/uploads/'.$file);
        }
        return response(1,200);
    }
    public function download(Request $request){
        $path = public_path('uploads/' . $request->get('file'));
        return Response::download($path);
    }
}
