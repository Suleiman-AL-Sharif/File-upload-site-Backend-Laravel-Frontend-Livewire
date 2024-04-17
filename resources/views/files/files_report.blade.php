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

<form method="POST" action="{{route('getFilesAuditReport')}}">
    @csrf

    <div style="display: flex;justify-content: space-around;">
        <div class="filter">
            <label for="">User</label>
            <select class="select-filter" name="userId" id="userId">
                <option value="0">--------------</option>
                @foreach ($users as $user)
                <option value="{{$user->id}}">{{$user->email}}</option>
                @endforeach
            </select>
        </div>
        <div class="filter">
            <label for="">File</label>
            <select class="select-filter" name="fileId" id="fileId">
                <option value="0">--------------</option>
                @foreach ($files as $file)
                <option value="{{$file->id}}">{{$file->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <th>ID</th>
                <th>File Name</th>
                <th>User Email</th>
                <th>Action</th>
                <th>Time</th>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>
                    <td>{{$reservation->id}}</td>
                    <td>{{$reservation->name}}</td>
                    <td>{{$reservation->email}}</td>
                    <td>{{$reservation->service}}</td>
                    <td>{{$reservation->time}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style=" width: 93%">
        <button style="float: right;" class="btn">Submit</button>
    </div>
</form>


</html>
<style>
    .filter {
        width: 25%;
        text-align: center;
        padding: 2%;
        border-radius: 12px;
        margin: 1%;
        background-color: #d1e8f5;
    }

    .select-filter {
        text-align: center;
        padding: 1%;
        border-radius: 5px;
        width: 75%;
        height: 30px;
    }
</style>
@endsection