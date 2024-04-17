<link rel="stylesheet" href="assets/css/index.css">
<script src="{{ asset('assets/js/index.js') }}"></script>

@if (Session::has('success'))
<div class="alert alert-success messages success alert-dismissible fade show" role="alert">
    <h4 class="alert-heading">Success!</h4>
    <p>{{ Session::get('success') }}</p>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert(this)" style="border-radius: 15px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (Session::has('errors'))
<div class="alert alert-errors messages errors alert-dismissible fade show" role="alert">
    <h4 class="alert-heading">errors!</h4>
    <p>{{ Session::get('errors') }}</p>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert(this)" style="border-radius: 15px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif