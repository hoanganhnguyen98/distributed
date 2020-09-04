@extends('layouts.app')

@section('htmlheader_title')
    CPN
@endsection

@section('content')
<form method="post" action=cpn-file enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Tải File Excel</label>
        <input type="file" name="file" class="form-control-file"
        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Tải lên</button>
    </div>
</form>
@endsection

@section('custom_js')
<script type="text/javascript">
    $(".mailer-prop-value").eq(3).html();
    $(".liner-checker-last p").eq(0).html().substring(0, $(".liner-checker-last p").eq(0).html().length-5);
</script>
@endsection
