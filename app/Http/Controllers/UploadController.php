<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function viewUpload()
    {
        // $isLogin = Session::get('status_login');
        // if($isLogin != 1) {
        //     return redirect()->route('login-page');
        // }

        return view('fitur.tiket');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);
      
        $fileName = time().'.'.$request->file->extension();  
       
        $request->file->move(public_path('uploads'), $fileName);
     
        /*  
            Write Code Here for
            Store $fileName name in DATABASE from HERE 
        */
       
        return back()
            ->with('success','You have successfully upload file.')
            ->with('file', $fileName);
   
    }

    public function download(Request $request)
    {
        $fileupload = $request->upload;
        // $document_name = str_replace("storage/", "", $fileupload);
        // $files = Storage::allFiles($document_name);
        return $fileupload;
        // return Storage::download($files[0], 'file_url');

        // return $document_name;
    }
}
