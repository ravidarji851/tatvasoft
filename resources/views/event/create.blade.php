@extends('layouts.app')
@section('title','Create')
@section('content')
<div class="col-lg-6">
	<h3>Create Event <a href="{{route('event.list')}}">Back</a></h3>
	
	<form action="{{route('event.store')}}" method="post" id="event_create">
		@csrf
		<div class="form-group">
		  <label for="email">Title:</label>
		  <input type="text" class="form-control" id="title" placeholder="Enter title" name="title">
	  		@if($errors->has('title'))
		 		<label style="color: red">{{$errors->first('title')}}</label>
		  	@endif
		</div>
		<div class="form-group">
		  <label for="email">Start Date:</label>
		  <input type="text" class="form-control" id="start_date" placeholder="Enter start date" name="start_date" autocomplete="false">
		  	@if($errors->has('start_date'))
		 		<label style="color: red">{{$errors->first('start_date')}}</label>
		  	@endif
		</div>
		<div class="form-group">
		  <label for="email">End Date:</label>
		  <input type="text" class="form-control" id="end_date" placeholder="Enter end date" name="end_date" autocomplete="false">
		  	@if($errors->has('end_date'))
		 		<label style="color: red">{{$errors->first('end_date')}}</label>
		  	@endif
		</div>
		<div class="form-group">
		  	<label for="email">Recurrence:</label>
			  	<div>
				  	<input type="radio" name="recurence_type" value="0" id="recurence_type"><label><span>&nbsp;Repeat</span></label>
				  	<select id="repeat_every" name="repeat_every" class="recurence_type_0">
                        <option selected="selected" value="1">Every</option>
                        <option value="2">Every Other</option>
                        <option value="3">Every Third</option>
                        <option value="4">Every Fourth</option>
                    </select>
                   <select id="repeat_day" name="repeat_day" class="recurence_type_0">
                        <option selected="selected" value="0">Day</option>
                        <option value="1">Week</option>
                        <option value="2">Month</option>
                        <option value="3">Year</option>
                    </select>
			  	</div>
			  	<div>
			  		<input type="radio" name="recurence_type" value="1" id="recurence_type">
			  		<span>Repeat on the
					<select id="repeat_on_the" name="repeat_on_the" class="recurence_type_1">
						<option selected="selected" value="0">First</option>
						<option value="1">Second</option>
						<option value="2">Third</option>
						<option value="3">Fourth</option>
					</select>
					</span>&nbsp;
					<select id="days_id" name="days_id" class="recurence_type_1">
						@foreach($days as $k =>$v)
							<option value="{{$v->num}}">{{$v->title}}</option>
						@endforeach
					</select>
                    of the
                    <select id="repeat_on_the_year" name="repeat_on_the_year" class="recurence_type_1">
                        <option selected="selected" value="0">Month</option>
                        <option value="1">3 Months</option>
                        <option value="2">4 Months</option>
                        <option value="3">6 Months</option>
                        <option value="4">Year</option>
                    </select>
			  	</div>
			  	<div id="radio_error"></div>
			  	@if($errors->has('recurence_type'))
			 		<label style="color: red">{{$errors->first('recurence_type')}}</label>
			  	@endif
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	
</div>
@stop
@section('js')
<script type="text/javascript">
	$(document).ready(function(){
				

		$('#event_create').validate({
			// ignore:[],
			rules:{
				title:{
					required:true,
				},
				start_date:{
					required:true,
				},
				end_date:{
					required:true,
				},
				recurence_type:{
					required:true,
				},
			},
			messages:{
				title:{
					required:'Title required',
				},
				start_date:{
					required:'Start date required',
				},
				end_date:{
					required:'End date required',
				},
				recurence_type:{
					required:'Recurrence required',
				},

			},
		 	errorPlacement: function(error, element) {
				if (element.attr("type") == "radio") {
					error.appendTo('#radio_error');
				} else {
					error.insertBefore(element);
				}
			}
		});

		$('#start_date').datepicker({
			format:'yyyy-mm-dd',
			autoclose: true, 
			todayHighlight: true,
			// startDate: '2022-02-13',
		}).change(function(e){
			e.preventDefault();
        	$('#end_date').datepicker('setStartDate', $(this).val());
			
		});
		$('#end_date').datepicker({
			format:'yyyy-mm-dd',			
			autoclose: true, 
			todayHighlight: true,
		}).change(function(e){
			e.preventDefault();
        	$('#start_date').datepicker('setEndDate', $(this).val());

			// $('#start_date').datepicker({'endDate':$(this).val()});

		});
		$(document).on('change','input[type=radio][name=recurence_type]',function(e) {
			if($(this).val()=='0'){
				$('.recurence_type_0').attr('disabled',false);
				$('.recurence_type_1').attr('disabled',true);

			}
			if($(this).val()=='1'){
				$('.recurence_type_0').attr('disabled',true);
				$('.recurence_type_1').attr('disabled',false);



			}

		});

		function set_date(){
			var start = moment($('#start_date').val()), // Sept. 1st
				end   = moment($('#end_date').val()), // Nov. 2nd
				daydiff =end.diff(start, 'days');

				if($('#recurence_type').val()==0){
					var repeat_every = $('select#repeat_every option:selected').val();
					var repeat_day = $('select#repeat_day option:selected').val();

					if(daydiff == 0){
						$('#repeat_day').attr('disabled','true')
					}else{
						$('#repeat_day').attr('disabled','false')
					}
				}else{
					var repeat_on_the = $('select#repeat_on_the option:selected').val();
					var days_id = $('select#days_id option:selected').val();
					var repeat_on_the_year = $('select#repeat_on_the_year option:selected').val();
					
				}
			// day   = 0;                    // Sunday

			// var result = [];
		}
		// $('#end_date').datepicker();

		// $().datpicker();


	});

	
</script>
@stop