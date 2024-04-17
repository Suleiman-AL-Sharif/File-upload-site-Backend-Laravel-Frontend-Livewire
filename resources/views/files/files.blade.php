@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Responsive Table</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/files/files.css">
    <script src="{{ asset('assets/js/files.js') }}"></script>
</head>

<form method="POST" action="{{route('checkInFiles')}}">
    @csrf
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Available</th>
                <th>View</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($files as $file)
                <tr>
                    <td>{{$file->id}}</td>
                    <td>{{$file->name}}</td>
                    <td>{{$file->description}}</td>
                    <td id="avilable_{{$file->id}}"> @if ($file->avilable)
                        Yes
                        @else
                        No
                        @endif()
                    </td>
                    <td><a style="text-decoration: none;" target="_blank" href="uplodedFiles/{{$file->path}}">View Content</a></td>
                    <td id="download_{{$file->id}}">@if(!$file->avilable && $file->user_id == Auth::user()->id)
                        <a style="text-decoration: none;" href="{{ route('downloadFile', $file->id) }}">Download</a>
                        @else
                        ------
                        @endif()
                    </td>
                    <!-- <td><a href="uplodedFiles/{{$file->path}}">Download</a></td> -->
                    <!-- <td><input type="file" name="" value="uplodedFiles/{{ old($file->path) }}"></td> -->
                    <td id="upload_{{$file->id}}">@if (!$file->avilable && $file->user_id == Auth::user()->id)
                        <input  type="file" class="file-input" onchange="uploadFile(this)" data-file_id="{{$file->id}}">
                        @endif
                    </td>
                    <!-- <td><input type="file" placeholder="{{ $file->path }}"></td> -->
                    <td id="action_{{$file->id}}">@if ($file->avilable)
                        <input type="checkbox" data-file_id="{{$file->id}}" name="fileIds[]" value="{{ $file->id }}">
                        @elseif (!$file->avilable && $file->user_id == Auth::user()->id)
                        <a class="btn-danger checkout" onclick="checkout({{ $file->id }})">Ceckout</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style=" width: 93%">
        <button style="float: right;" class="btn">CheckIn</button>
    </div>
</form>

</html>
@endsection