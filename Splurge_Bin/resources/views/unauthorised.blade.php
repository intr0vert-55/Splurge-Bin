@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header">
                    <h3 class = "text-danger">
                        Access Denied
                        <i class="bi bi-dash-circle"></i>
                    </h3>
                </div>
                <div class="alert alert-danger">
                    You are unauthorised to access this page
                    <i class="bi bi-x-circle"></i>
                    <br>
                    <a href="javascript:history.back()" class = "alert-link">
                        <i class="bi bi-arrow-left"></i>
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
