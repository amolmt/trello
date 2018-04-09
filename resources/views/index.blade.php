@extends('master')

@section('content')
    <main role="main">
        <div class="container">
            <div class="card-deck">
                @foreach($boards as $board)
                    <div class="card text-dark bg-light mb-3" style="max-width: 18rem;" id="board{{$board->id}}">
                        <div class="card-header text-center">
                            <!-- <i class="fas fa-trash"></i> -->
                            {{$board->name}}
                            <!-- <input type="checkbox" class="float-right" id="destroyBoard{{$board->id}}" onclick="deleteboard({{$board->id}})"> -->
                            <i class="fas fa-trash float-right" onclick="destroyboard(' + board.id + ')"></i>
                            <i class="fas fa-check float-right" style="padding-right: 10px;" id="destroyBoard{{$board->id}}" onclick="deleteboard({{$board->id}})"></i>
                        </div>
                        <div class="card-body">
                            <form>
                                <input type="hidden" name="boardId" value="{{ $board->id }}">
                                <textarea placeholder="card details" class="form-control text-justify"
                                          id="cardName{{$board->id}}"
                                          name="cardName"></textarea>
                                <hr>
                                <button type="button" class="btn btn-sm btn-outline-success btn-block"
                                        onclick="addcard({{$board->id}})" id="createCard{{$board->id}}">Add card
                                </button>
                            </form>
                            @foreach($board->cards as $card)
                                <hr>
                            <div id="remove_all_cards">
                            <div id="cards{{$board->id}}">
                                <div class="card bg-light mb-3" style="max-width: 18rem;" id="cards{{$card->id}}" contenteditable="true">
                                    <div class="card-header text-justify">
                                        {{$card->name}}
                                        <i class="fas fa-check-square float-right" id="destroyCard{{$card->id}}" onclick="deletecard({{$card->id}})"></i>
                                        <i class="fas fa-pen-square float-right" style=" padding-right:10px;" onclick="editcard({{$card->id}})"></i>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Last
                                updated: {{$board->updated_at->toFormattedDateString()}}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection