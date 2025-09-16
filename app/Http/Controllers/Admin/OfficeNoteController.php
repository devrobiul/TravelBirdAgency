<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeNoteController extends Controller
{
    public function index(){
        return view('backend.pages.note.index',[
            'note'=>null,
            'notes'=>OfficeNote::latest()->get(),
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'note'=>'required',
        ]);

        OfficeNote::create([
            'user_id'=>Auth::id(),
            'note'=>$request->note,
            'created_at'=>now(),

        ]);
        session()->flash('success','Note created successfully');
        return back();
    }
        public function destroy($id){

        OfficeNote::findOrFail($id)->delete();
        session()->flash('success','Note deleted successfully');
        return back();
        
    }
}
