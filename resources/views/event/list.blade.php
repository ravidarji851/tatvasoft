@extends('layouts.app')
@section('title','List')
@section('content')
	<h2>Event List</h2>
	 <a href="{{route('event.create')}}">Add</a>
	 <table id="list">
	 	<th>Title</th>
	 	<th>Event</th>

	 	<th>Start</th>
	 	<th>Recurence</th>
	 	<th>Action</th>
	 	



	 </table>
@stop
@section('js')
<script type="text/javascript">
	$(document).ready(function() {
		$('#list').DataTable({
			serverSide:'!0',
			ajax:{
				url:"{{route('event.ajax.list')}}",
				dataType:'json',

			},
			columns:[
				{data:'id',name:'id'},
				{data:'title',name:'title'},

				{data:'to_date',name:'start_date'},
				{data:'rccurrence',name:'recurence_type'},
				{data:'id',name:'Action',
					render:function(data){
						return '<a href="{{route("event.edit")}}/'+data+'">Edit</a>&nbsp;<a href="{{route("event.view")}}/'+data+'">View</a><a href="javascript:void(0)" class="delete" data-id="'+data+'">Delete</a>'
					}
				},





			],
		});
		$(document).on('click','.delete',function(e){
			e.preventDefault();
			 	var id = $(this).data("id");
		        $.ajax(
		        {
		            url: "{{route('event.delete')}}",
		            type: 'post',
             	  	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		            dataType: "json",
		            data: {
		            	"_token": "{{ csrf_token() }}",
        				"id": id
        			},
		            success: function (response)
		            {
		            	if(response.status=="success"){
		            		// $('#list').DataTable().ajax.reload();
		            	}
		               
		            },
		            complete:function(){
		            	$('#list').DataTable().ajax.reload();
		            }
		        });

		})
	});
	
</script>
@stop