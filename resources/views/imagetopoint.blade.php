<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Image To Point</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <div class="row container mt-3">
            <div class="container col-2"></div>
            <div class="container col-4">
                <form method="POST" enctype="multipart/form-data" action="{{ route('get-image') }}">
                    @csrf

                    <div class="form-group">
                        <label><i class="fas fa-fan fa-spin mr-2"></i>Image to upload</label>
                        <input type="file" id="image" class="form-control-file" name="image" accept="image/jpeg, image/jpg, image/png" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
            <div class="container col-5">
                <img src="{{ $imageUrl }}" width="80%" height="100%">
            </div>
            <div class="container col-1"></div>
        </div>
    </body>

    @section('scripts')
        @include('layouts.partials.scripts')
    @show
</html>
