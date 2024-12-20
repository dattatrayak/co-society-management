<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Society Management</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('theme/css/styles.css') }}" rel="stylesheet" />
    {{-- <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script> --}}
</head>

<body class="sb-nav-fixed">

    @include('society.layout.header')

    <div id="layoutSidenav">

        @include('society.layout.sidebar')
        <div id="layoutSidenav_content">

            <main>
                @yield('content')
            </main>

            @include('society.layout.footer')
        </div>
    </div>

    <!-- jQuery (required for the Icon Picker) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('theme/js/scripts.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('theme/js/datatables-simple-demo.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js">
    </script>

<!-- FontAwesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- FontAwesome Icon Picker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.min.css">

<!-- FontAwesome Icon Picker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js"></script>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet" />

    <script>
        @yield('script')
        $(document).ready(function() {

            @yield('scriptDockReady')
            // Attach the icon picker to the input field
            $('#icon').iconpicker({
            placement: 'bottomRight',
            hideOnSelect: true,
            inputSearch: true,
        });

        // Update the preview on icon change
        $('#icon').on('iconpickerSelected', function (event) {
            $('#iconPreview').attr('class', event.iconpickerValue); // Update the icon preview
        });

        // Attach the picker to the button
        $('#iconPickerButton').on('click', function () {
            alert('dsfasdfas ');
            $('#icon').iconpicker('toggle'); // Open or close the picker
        });
        });
    </script>

</body>

</html>
