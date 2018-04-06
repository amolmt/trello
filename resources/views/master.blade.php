<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Trello</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="/css/album.css" rel="stylesheet">
</head>

<body>

<header>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="#" class="navbar-brand d-flex align-items-center">
                <strong>Trello</strong>
            </a>

            <button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#createBoardModal">Create a
                new board
            </button>
        </div>
    </div>
</header>
<br>
<main role="main">
    <div class="container">
        @include('layouts.bootstrapModals')
        <div class="card-deck1">
            @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    {{ Session::get('flash_message') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">

    </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-3.3.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
<script type="text/javascript">

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
                $('#cards' + id).prepend($('<div class="card-header text-justify">' + card.name + '<input type="checkbox" class="float-right" onclick="deletecard(' + card.id + ')"></div><hr>'));
                // location.reload();
            },
            error: function (err) {
                console.log(err);
            }
        });
    }


    function deletecard(id) {

        // console.log(id);

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
                // $("#card" + id).css(color:gray;text-decoration:line-through;);

                {{--$("#destroyCard{{$card->id}}").remove();--}}
            },
            error: function (err) {
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

                    $("#board" + board_id).remove();

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
                        $('<div class="card text-white bg-dark mb-3" style="max-width: 18rem;" id="board('+board.id+')">' +
                            '<input type="hidden" name="boardId" value="' + board.id + '">' +
                            '<div class="card-header text-center">' + board.name + '<input type="checkbox" class="float-right" onclick="deleteboard(' + board.id + ')"></div>' +
                            '<div class="card-body">' +
                            '<textarea placeholder="card details" class="form-control" id="cardName' + board.id + '" name="cardName1"></textarea>' + '<hr>' +
                            '<button type="submit" onclick="addcard(' + board.id + ')" class="btn btn-sm btn-outline-success btn-block" id="createCard' + board.id + '">Add card</button>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<small class="text-muted">' + date + board.updated_at + '</small>' +
                            '</div>' +
                            '</div>'
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
</body>
</html>