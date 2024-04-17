@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Responsive Table</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/files/add-file.css">
    <!-- <link rel="stylesheet" href="assets/css/files/files.css"> -->
    <script src="{{ asset('assets/js/files.js') }}"></script>
</head>
<!-- @if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif -->
<div class="container">
    <form enctype="multipart/form-data" method="POST" action="{{route('saveFile')}}">
        @csrf
        <div class="row">
            <div class="col-25">
                <label for="filename">File Name</label>
            </div>
            <div class="col-75">
                <input type="text" id="fname" name="filename" placeholder="File name.." required>
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="description">Description</label>
            </div>
            <div class="col-75">
                <textarea id="description" name="description" placeholder="Write something.." style="height:200px"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="file">Upload File</label>
            </div>
            <div class="col-75">
                <input type="file" id="file" name="file" required>
            </div>
        </div>
        <div class="row">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>

</html>
@endsection