@extends('master')

@section('content')
    <main role="main">
        <div class="container">
            <div class="card-deck">
                @foreach($boards as $board)
                    <div class="card text-dark bg-light mb-3" style="max-width: 18rem;" id="board{{$board->id}}">
                        <div class="card-header text-center">
                            {{$board->name}}
                            <i class="fas fa-trash float-right"></i>
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
                                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                                            <div class="card-header text-justify">
                                                <input class="form-control text-justify" type="text" value="{{$card->name}}" disabled>
                                                <hr>
                                                {{--<i class="fas fa-check-square float-right" id="destroyCard{{$card->id}}" onclick="deletecard({{$card->id}})"></i>--}}
                                                <i class="fas fa-check-square float-right delete" id="{{$card->id}}"></i>
                                                <i class="fas fa-pen-square float-right edit" id="{{$card->id}}" data-card="{{$card->id}}" data-type="edit" style=" padding-right:10px;"></i>
                                                {{--<i class="fas fa-share float-right edit" id="{{$card->id}}" data-type="update"></i>--}}
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
    <script>
        $('.edit').on('click', function () {
            var id = $(this).attr("id");
            $(this).siblings('input').prop('disabled', false);
            var name = $(this).siblings('input').val();
            var url = "{{url('/edit-card')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: {id:id, name:name, '_token': '{{ csrf_token() }}'},
                dataType: "JSON",
                success: function(id){
                },
                error: function(err){
                    console.log(err);
                }
            });
        });

        $('.delete').on('click', function () {
            var id = $(this).attr("id");
            var url = "{{url('/delete-card')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: "JSON",
                success: function () {
                    console.log("deleted");
                    $(this).remove();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    </script>
    <script>
        function addcard(id) {
            var cardName = ($('#cardName' + id).val());
            var url = "{{url('/create-card')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: {name: cardName, board_id: id, '_token': '{{ csrf_token() }}'},
                dataType: "JSON",
                success: function (card) {
                    // console.log(card);
                    $('#cardName' + id).val('');
                    $('#cards' + id).prepend($('<div class="card-header text-justify"><input class="form-control text-justify" type="text" value="'+card.name+'" disabled>' + '<hr><i class="fas fa-check-square float-right delete"></i><i class="fas fa-pen-square float-right" style=" padding-right:10px;"></i></div><hr>'));
                    // location.reload();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function deletecard(id) {
            var url = "{{url('/delete-card')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: "JSON",
                success: function (id) {
                    console.log("deleted");
                    $("#cards" + id).remove();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function editcard(id){
            console.log(id);
            var url = "{{url('/edit-card')}}";
            // var name = ($("#cards" + ).val());
            $.ajax({
                type: "POST",
                url: url,
                data: {id:id, name:name, '_token': '{{ csrf_token() }}'},
                dataType: "JSON",
                success: function(name){
                    console.log(name);
                },
                error: function(err){
                    console.log(err);
                }
            });
        }

        function deleteboard(board_id) {
            var r=confirm("Are you sure?!");
            var txt;
            if (r == true) {
                var url = "{{url('/delete-board')}}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        // id: id,
                        board_id: board_id,
                        // status: status,
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: "JSON",
                    success: function (cards) {
                        console.log("deleted");

                        // $("#cards" + board_id).remove();
                        $("#remove_all_cards").remove();

                        {{--$("#destroyCard{{$card->id}}").remove();--}}
                        // location.reload();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            } else {
                txt = "You pressed Cancel!";
                console.log(txt);
            }
        }

        $(document).ready(function () {
            $("#createBoard").click(function () {
                var boardName = $('#boardName').val();
                var url = "{{url('/create-board')}}";
                var date = "Last updated: ";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {name: boardName, '_token': '{{ csrf_token() }}'},
                    dataType: "JSON",
                    success: function (board) {
                        $("#boardName").val('');
                        $('#createBoardModal').modal('hide');
                        $('.card-deck').prepend(
                            $(' <div id="remove_all_cards"><div class="card text-dark bg-light mb-3" style="max-width: 18rem;" id="board('+board.id+')">' +
                                '<input type="hidden" name="boardId" value="' + board.id + '">' +
                                '<div class="card-header text-center">' + board.name + '<i class="fas fa-trash float-right"></i><i class="fas fa-check float-right" style="padding-right: 10px;" onclick="deleteboard(' + board.id + ')"></i></div>' +
                                '<div class="card-body">' +
                                '<textarea placeholder="card details" class="form-control" id="cardName' + board.id + '" name="cardName1"></textarea>' + '<hr>' +
                                '<button type="submit" onclick="addcard(' + board.id + ')" class="btn btn-sm btn-outline-success btn-block" id="createCard' + board.id + '">Add card</button>' +
                                '</div>' +
                                '<div class="card-footer">' +
                                '<small class="text-muted">' + date + board.updated_at + '</small>' +
                                '</div>' +
                                '</div></div>'
                            )
                        );
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
        });
    </script>
@endsection