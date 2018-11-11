<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class FileUploadsController extends Controller
{

    /**
     * Display a listing of the file uploads.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $fileUploads = FileUpload::paginate(25);

        return view('file_uploads.index', compact('fileUploads'));
    }

    /**
     * Show the form for creating a new file upload.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('file_uploads.create');
    }

    /**
     * Store a new file upload in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            FileUpload::create($data);

            return redirect()->route('file_uploads.file_upload.index')
                             ->with('success_message', 'File Upload was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified file upload.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $fileUpload = FileUpload::findOrFail($id);

        return view('file_uploads.show', compact('fileUpload'));
    }

    /**
     * Show the form for editing the specified file upload.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $fileUpload = FileUpload::findOrFail($id);
        

        return view('file_uploads.edit', compact('fileUpload'));
    }

    /**
     * Update the specified file upload in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $fileUpload = FileUpload::findOrFail($id);
            $fileUpload->update($data);

            return redirect()->route('file_uploads.file_upload.index')
                             ->with('success_message', 'File Upload was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified file upload from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $fileUpload = FileUpload::findOrFail($id);
            $fileUpload->delete();

            return redirect()->route('file_uploads.file_upload.index')
                             ->with('success_message', 'File Upload was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'images' => ['nullable','file'],
     
        ];
                if ($request->route()->getAction()['as'] == 'file_uploads.file_upload.store' || $request->has('custom_delete_images')) {
            array_push($rules['images'], 'required');
        }
        $data = $request->validate($rules);
        if ($request->has('custom_delete_images')) {
            $data['images'] = null;
        }
        if ($request->hasFile('images')) {
            $data['images'] = $this->moveFile($request->file('images'));
        }


        return $data;
    }
  
    /**
     * Moves the attached file to the server.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    protected function moveFile($file)
    {
        if (!$file->isValid()) {
            return '';
        }
        
        return $file->store(config('codegenerator.files_upload_path'), config('filesystems.default'));
    }
}
