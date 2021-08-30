<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Style;
use App\Piece;

class PieceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $pieces=Piece::with(['style'])->get();

        return view('Admin.contractual.piece.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','pieces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $styles=Style::where('sty_status','1')->get();
        return view('Admin.contractual.piece.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','styles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Piece $piece)
    {
        $this->validate($request,[
            'pi_styleid'=>'required',
            'pi_name'=>'required',
            'pi_price_dz'=>'required',
        ]);
        $piece->fill($request->all());
        $piece->pi_code=$this->uniquecode('10','PI','pi_code','tbl_piece');
        $piece->save();
        if($piece){
            session()->flash('success','Piece Stored Successfully.');
        }else{
            session()->flash('error','Something Went Wrong!');
        }

        return redirect()->back();
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
    public function edit($pi_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $styles=Style::where('sty_status','1')->get();
        $piece=Piece::find($pi_id);
        return view('Admin.contractual.piece.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','styles','piece'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pi_id)
    {
        $this->validate($request,[
            'pi_styleid'=>'required',
            'pi_name'=>'required',
            'pi_price_dz'=>'required',
            'pi_status'=>'required',
        ]);
        $piece=Piece::find($pi_id);
        $piece->fill($request->all());
        $piece->save();
        if($piece){
            session()->flash('success','Piece Updated Successfully.');
        }else{
            session()->flash('error','Something Went Wrong!');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $piece=Piece::find($id)->delete();
        if($piece){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }
    }
}
