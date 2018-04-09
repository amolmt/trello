<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{

    public function index()
    {

        $cards = Card::where('status', 1)->latest();
        return view('index', compact('cards'));
    }


    public function store(Request $request)
    {

//        print_r($request->all());
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages()->first(), 422);
        }

        try {
            DB::beginTransaction();
            $card = new Card();
            $card->name = $request->name;
            $card->board_id = $request->board_id;
            $card->save();
            DB::commit();
            return response()->json($card, 200);
        } catch (QueryException $e) {
            DB::rollBack();
        }

        Session::flash('flash_message', 'Card created successfully!');

    }


    public function destroy(Request $request)
    {
        $id = $request->id;
        $card = Card::find($id);
        $card->status = 0;
        $card->save();

        Session::flash('flash_message', 'Card deleted successfully!');

        return response()->json($id, 200);
//        return redirect('/');
    }


    public function destroyBoard(Request $request)
    {
        $status = $request->status;
        $board_id = $request->board_id;
        $id = $request->id;
        $cards = Card::where('board_id', $board_id)->update(['status' => 0]);

        return response()->json($cards, 200);
    }

    public function show()
    {
        $cards = Card::all();
        return view('master', compact('cards'));
    }

    public function edit(Request $request)
    {
//        dd($request->all());
        print_r($request->all());
        $id = $request->id;
//        $name = $request->name;
        $card = Card::find($id);
        $card->name = $request->name;
        $card->save();

        return response()->json($id, '200');
    }

}
