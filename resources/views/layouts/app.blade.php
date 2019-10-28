<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
    @include('layouts.partials.htmlheader')
@show

    <body>
        <div>
            @include('layouts.partials.mainheader')

            <section>
                @yield('content')
            </section>

            @include('layouts.partials.footer')
        </div>

        @section('scripts')
            @include('layouts.partials.scripts')
        @show

        @yield('custom_js')
    </body>
</html>
