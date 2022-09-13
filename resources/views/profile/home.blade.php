@extends('layouts.app')
@section('content')
<div class="container">

   @can('update',$user->profile)
   <home-component :user="{{$user}}" :profile="{{$user->profile}}" 
      :posts="{{$user->posts}}" canview="{{True}}"
   >
   </home-component>  
   @else
   <home-component :user="{{$user}}" :profile="{{$user->profile}}" 
      :posts="{{$user->posts}}" canview="{{False}}"
   >
   </home-component>  
   @endcan

   
</div>
@endsection
