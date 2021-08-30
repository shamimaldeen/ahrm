<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Documents;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Documents $document)
    {
        if(!empty($request->emp_id) && !empty($request->title) && isset($request->doc)){
            $fileInfo=$this->fileInfo($request->doc);
            if(in_array($fileInfo['type'], $this->MimeTypes())){
                $filename=$request->emp_id.'-'.date('YmdHis').'-'.rand().'-'.rand().'.'.$fileInfo['extension'];
                $upload=$this->fileUpload($request->doc,'documents',$filename);
                if($upload){
                    $document->fill($request->all());
                    $document->name=$fileInfo["name"];
                    $document->file=$filename;
                    $document->uploaded_by=Auth::guard('admin')->user()->suser_empid;
                    $document->save();
                    if($document){
                        session()->flash('success','Document Uploaded successfully');
                        return redirect('employee-details/'.$request->emp_id.'/view#documents');
                    }else{
                        session()->flash('error','Something Went Wrong. Try Again');
                        return redirect('employee-details/'.$request->emp_id.'/view#documents');
                    }

                }else{
                    session()->flash('error','Document not Uploaded. Try Again');
                    return redirect('employee-details/'.$request->emp_id.'/view#documents');
                }
            }
            session()->flash('error','Please Image or PDF Document');
            return redirect('employee-details/'.$request->emp_id.'/view#documents');
        }
        session()->flash('error','Please Write a Title & Choose a Document to upload.');
        return redirect('employee-details/'.$request->emp_id.'/view#documents');
    }

    public function MimeTypes()
    {
        return array(
            'image/jpeg',
            'image/jpeg',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/tiff',
            'image/tiff',
            'image/svg+xml',
            'application/pdf',
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($emp_id)
    {
        return view('Admin.employeeDetails.documents.create',compact('emp_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document=Documents::find($id);
        return view('Admin.employeeDetails.documents.edit',compact('document'));
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
        $document=Documents::find($id);
        if(!empty($request->title)){
            $document->fill($request->all());
            $document->save();
            if($document){
                if(isset($request->doc)){
                    $fileInfo=$this->fileInfo($request->doc);
                    if(in_array($fileInfo['type'], $this->MimeTypes())){
                        $filename=$document->emp_id.'-'.date('YmdHis').'-'.rand().'-'.rand().'.'.$fileInfo['extension'];
                        $upload=$this->fileUpload($request->doc,'documents',$filename);
                        if($upload){
                            if(file_exists(public_path('/documents/'.$document->file))){
                                unlink(public_path('/documents/'.$document->file));
                            }
                            $document->name=$fileInfo["name"];
                            $document->file=$filename;
    
                            $document->save();
                        }else{
                            session()->flash('error','Document not Uploaded. Try Again');
                            return redirect('employee-details/'.$request->emp_id.'/view#documents');
                        }
                    }
                }
                session()->flash('success','Document Updated successfully');
                return redirect('employee-details/'.$document->emp_id.'/view#documents');
            }else{
                session()->flash('error','Something Went Wrong. Try Again');
                return redirect('employee-details/'.$document->emp_id.'/view#documents');
            }
            session()->flash('error','Please Image or PDF Document');
            return redirect('employee-details/'.$document->emp_id.'/view#documents');
        }
        session()->flash('error','Please Write a Title & Choose a Document to upload.');
        return redirect('employee-details/'.$document->emp_id.'/view#documents');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $documents=Documents::find($id);
        if($documents->file!="" && file_exists(public_path('/documents/'.$documents->file))){
            unlink(public_path('/documents/'.$documents->file));
        }
        $documents->delete();
        if($documents){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false]);
        }
    }
}
