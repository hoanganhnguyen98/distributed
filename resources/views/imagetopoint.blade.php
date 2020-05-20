<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Image</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <div class="container mt-3">
            @if(Session::has('success'))
                @include('layouts.toast.success')
            @endif

            @if($errors->any())
                @include('layouts.toast.errors')
            @endif

            <form method="POST" enctype="multipart/form-data" action="{{ route('get-image') }}">
                @csrf

                 <div class='preview' style="display: none;">
                    <img src="" id="img" width="100" height="100">
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" id="image" class="form-control-file" name="image" accept="image/jpeg, image/jpg, image/png" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        Upload
                    </button>
                </div>
            </form>
        </div>

        @if(Session::has('image'))
        <div class="container mt-3">
            <img src="{{ Session::get('image') }}">
        </div>
        @endif
    </body>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>
