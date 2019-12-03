<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
    @include('home.layouts.htmlheader')
@show

    <body id="page-top">
        <!-- Header -->
        @include('home.layouts.navigation')
        @include('home.layouts.slideheader')

        <!-- Components - Main Content -->
        @include('home.intro')
        @include('home.menu.menu')
        @include('home.branch')
                
        <!-- Footer -->
        @section('footer')
            @include('home.layouts.footer')
        @show

        @section('scripts')
            @include('home.layouts.scripts')
        @show
    </body>
</html>
