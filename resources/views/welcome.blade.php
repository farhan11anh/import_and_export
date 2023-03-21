<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>

    </style>

    <title>test import dan eksport csv</title>

</head>

<body>
    <div class="container my-5">
        <h1 class="fs-5 fw-bold text-center test">test import dan eksport csv</h1>
        <div class="row">
            <div class="d-flex my-2">
                <a href="/pdf" class="btn btn-primary me-1">Export Data</a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Import Data
                </button>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (isset($_GET['msg']))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $_GET['msg'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $item)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <form onsubmit="return confirm('apakah anda yakin ?');"
                                    action="{{ route('user.destroy', $item->id) }}" method="POST">

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="/import" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="file" name="file" id="filep" class="form-control">
                            <button id="previewz" class="btn btn-primary" type="button">Preview</button>
                        </div>
                    </form>

                    <table border="1">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody id="datazz" >
                            <tr >
                                <td colspan="4" class="mid" >empty</td>
                            </tr>

                        </tbody>
                    </table>
                    <button id="submit-btn" class="btn btn-primary hide" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>

        $('#submit-btn').click((e)=>{
            e.preventDefault()
            let file_data = $('#filep').prop('files')[0];
            let form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('_token', '{{csrf_token()}}');
            $.ajax({
                url:'/import',
                dataType : 'text',
                cache : false,
                contentType : false,
                processData : false,
                data : form_data,
                type : 'post',
                success : function(data){
                    console.log(data);

                    $(location).prop('href', 'http://127.0.0.1:8000/user?msg=Data Imported Successfully')
                }
            })

        })

        $('#previewz').click(function(e){
            e.preventDefault();
            let file_data = $('#filep').prop('files')[0];
            let form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('_token', '{{csrf_token()}}');
            $.ajax({
                url:'/previews',
                dataType : 'text',
                cache : false,
                contentType : false,
                processData : false,
                data : form_data,
                type : 'post',
                success : function(data){
                    let content = JSON.parse(data);
                    var contents = content.content;

                    // kosongkan yang ada pada tag tr content
                    $('#datazz').html('')

                    for([i, data] of contents.entries() ) {
                        data = `<tr>
                                <td>${i+1}</td>
                                <td>${data.name}</td>
                                <td>${data.email}</td>
                                <td>action</td>
                        </tr>`;
                        $('#datazz').append(data)
                    }

                }
            })

            $('#submit-btn').removeClass('hide');

        })
    </script>

</body>

</html>
