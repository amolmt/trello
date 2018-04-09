<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoardController extends Controller
{

    public function index(){

        $boards = Board::with('cards')->where('status', 1)->get();
        return view('index', compact('boards'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
                'name' => ['required'],
            ]);

        if ($validator->fails()) {
            return response()->json($validator->messages()->first(), 422);
        }


        try {
            DB::beginTransaction();
            $board = new Board();
            $board->name = $request->name;
            $board->save();
            DB::commit();
            return response()->json($board, 200);
        } catch (QueryException $e) {
            DB::rollBack();
        }

        Session::flash('flash_message', 'Board created successfully!');
    }


    public function destroy(Request $request)
    {
//        print_r($request->all());exit;
        $id = $request->id;
        $board = Board::find($id);
        $board->status = 0;
        $board->save();

        Session::flash('flash_message', 'Board deleted successfully!');

        return response()->json($id, 200);
    }

    public function show(){

        $boards = Board::all();
//        var_dump($boards->name);
        return view('master', compact('boards'));
    }
}
