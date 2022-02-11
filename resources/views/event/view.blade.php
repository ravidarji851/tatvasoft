@extends('layouts.app')
@section('title','Create')
@section('content')
	<h3>Create Event <a href="{{route('event.list')}}">Back</a></h3>
	
<table>
	<th>Date</th>
	<th>Dayname</th>
	@if(isset($data) && !empty($data->get_date()))
	@foreach($data->get_date as $k =>$v)
	<tr>
		<td>{{$v->date}}</td>
		<td>{{$v->date}}</td>
	</tr>
	@endforeach
	@endif
</table>
@stop