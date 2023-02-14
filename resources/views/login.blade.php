@extends('layout')

@section('content')
    
<div class="wide-block pb-1 pt-2">        
    <form method="GET" action="{{route('authenticate')}}">
        @csrf            
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="label" for="name">Name</label>
                <input type="text" class="form-control" id="username" placeholder="Enter your name" name="username" required>
                <i class="clear-input">
                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                </i>
                @if(session('message'))
                <div class="pt-1">
                    <strong>
                        <p class="alert alert-danger">{{ session('message') }}</p>
                    </strong>
                </div>                    
                @endif
            </div>
        </div>
                        
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="label" for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Type a password" name="password" required>
                <i class="clear-input">
                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                </i>
            </div>
        </div>  

        <div class="input-wrapper pt-2">
            <button type="submit" class="btn btn-info btn-lg btn-block">Log In</button>
        </div>        
    </form>
</div>    

@endsection