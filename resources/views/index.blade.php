@extends('master')

@section('content')
    <main role="main">
        <div class="container">
            <div class="card-deck">
                @foreach($boards as $board)
                    <div class="card text-white bg-dark mb-3" style="max-width: 18rem;" id="board{{$board->id}}">
                        <div class="card-header text-center">
                            {{$board->name}}
                            <input type="checkbox" class="float-right" id="destroyBoard{{$board->id}}" onclick="deleteboard({{$board->id}})">
                            {{--todo- trash glyphicon--}}
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
                            <div id="cards{{$board->id}}">
                                <div class="card bg-dark mb-3" style="max-width: 18rem;" id="cards{{$card->id}}">
                                    <div class="card-header text-justify">
                                        {{$card->name}}
                                        <input type="checkbox" class="float-right" id="destroyCard{{$card->id}}" onclick="deletecard({{$card->id}})">
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