<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRecurence;
use App\Models\Days;
use App\Models\EventDate;


class EventController extends Controller
{
	public function get_every_status($ev,$mod){

		// 0-Every,1-Other,2-Third,3-Four
		// 0-Day,1-week,2-month,3-year
		$every='';
		switch ($ev) {
			case '0':
				$every='Every';
				break;
				case '1':
				$every='Every Other';
				break;
				case '2':
				$every='Every Third';
				break;
				case '3':
				$every='Every Four';
				break;
			
			default:
				$every='';
				break;
		}

		$day='';
		switch ($mod) {
			case '0':
				$day='Day';
				break;
				case '1':
				$day='week';
				break;
				case '2':
				$day='month';
				break;
				case '3':
				$day='year';
				break;
			
			default:
				$day='';
				break;
		}
		return $every.' '.$day;
	}
	public function get_every_to_status($f,$d,$y){
		$numberofdays='';
    	switch ($f) {
    			case '0':
    				$numberofdays='First';
    			break;
    			case '1':
    				$numberofdays='Second';
    			break;
    			case '2':
    				$numberofdays='Third';
    			break;
    			case '3':
    				$numberofdays='Fourth';    				
    			break;    		
    		default:
    				$numberofdays='';    	
    			break;
    	}
		$months='';    	
    	switch ($y) {
    			case '0':
    				$months='of months';
    			break;
    			case '1':
    				$months='of 3 months';
    			break;
    			case '2':
    				$months='of 4 months';
    			break;
    			case '3':
    				$months='of years';
    			break;
    		default:
    			# code...
    			break;
    	}
    		
    	
    	$daysname = [
			'1'=>'Monday',
			'2'=>'Tuesday',
			'3'=>'Wednesday',
			'4'=>'Thursday',
			'5'=>'Friday',
			'6'=>'Saturday',
			'7'=>'Sunday',
		];

		return $numberofdays.' '.$daysname[$d].' '.$months;
	}
    public function index(Request $request){

    	return view('event.list');
    }
    public function list(Request $request){
    	$json=array();
    	$dir=$request->order[0]['dir'];
    	$sortBy=$request->columns[$request->order[0]['column']]['name'];
    	$sortOrder=($dir)? $dir : config('pager.sortOrder');
    	$skip=$request->input('start');
    	$sql=Event::with('get_reccurence')->withoutTrashed();
    	if($request->search['value']!=''){

    	}

    	$recordsTotal=$sql->count();
    	$data=$sql->limit($request->length)
    		->skip($skip)
    		->orderBy($sortBy,$sortOrder)
    		->get();
    		foreach ($data as $key => $value) {
    			$data[$key]->to_date=$value->start_date.' To '.$value->end_date;
    			$type='';
    			if(!empty($value->get_reccurence)){

    				if($value->recurence_type==0){
	    				$type=$this->get_every_status($value->get_reccurence->repeat_every,$value->get_reccurence->repeat_day);
	    			}else{
	    				$type=$this->get_every_to_status($value->get_reccurence->repeat_on_the,$value->get_reccurence->days_id,$value->get_reccurence->repeat_on_the_year);
	    			}

    			}
    			$data[$key]->rccurrence=$type;
    		}
    		
    	$recordsFiltered=$data->count();
		$json['data']=$data;
		$json['draw']=$request->draw;
		$json['recordsTotal']=$recordsTotal;
		$json['recordsFiltered']=$recordsFiltered;



    	return json_encode($json);
    }

    public function create(Request $request){
    	$days=Days::withoutTrashed()->get();
    	return view('event.create',compact('days'));
    }
    public function edit(Request $request){
    	$id=$request->id;
    	if(empty($id)){
    		return redirect()->route('event.list')->with('error','Event not found');
    	}
    	$data=Event::withoutTrashed()->find($request->id);
    	if(empty($data)){
    		return redirect()->route('event.list')->with('error','Event not found');

    	}
    	$days=Days::withoutTrashed()->get();

    	return view('event.edit',compact('data','days'));
    }
    public function view(Request $request){
    	$id=$request->id;
    	if(empty($id)){
    		return redirect()->route('event.list')->with('error','Event not found');
    	}
    	$data=Event::withoutTrashed()->find($request->id);
    	if(empty($data)){
    		return redirect()->route('event.list')->with('error','Event not found');

    	}
    	return view('event.view',compact('data'));
    }

    public function store(Request $request){
    	$rules=array(
			'title.required'=>'Title required',
			'start_date.required'=>'Startdate required',
			'end_date.required'=>'Enddate required',
			'recurence_type.required'=>'Recurencetype required',
		);

		$validations=app('validator')->make($request->all(),[
			'title'=>'required',
			'start_date'=>'required',
			'end_date'=>'required',
			'recurence_type'=>'required',
		],$rules);

		if(!$validations->passes()){
			return redirect()->back()->withInput()->withErrors($validations);
		}

		$store=new Event;

		$store->title=$request->title;
		$store->start_date=date('Y-m-d',strtotime($request->start_date));
		$store->end_date=date('Y-m-d',strtotime($request->end_date));
		$store->recurence_type=$request->recurence_type;
		$event=$store->save();


		$resource=new EventRecurence;
		$resource->event_id=$store->id;
		if($request->recurence_type=='0'){
			$resource->repeat_every=$request->repeat_every;
			$resource->repeat_day=$request->repeat_day;
			$arr=$this->date_range($request->start_date,$request->end_date,$request->repeat_every,$request->repeat_day);
			$this->store_date($arr,$store->id);
			


		}
		if($request->recurence_type=='1'){
			$resource->repeat_on_the=$request->repeat_on_the;
			$resource->repeat_on_the_year=$request->repeat_on_the_year;
			$resource->days_id=$request->days_id;
			$arr=$this->date_range_rec(
				$request->start_date,
				$request->end_date,
				$request->repeat_on_the,
				$request->days_id,
				$request->repeat_on_the_year);
			$this->store_date($arr,$store->id);



		}
		$resource->save();
		return redirect()->route('event.list')->with('succes','Event creaetd');




    }
    public function date_range($start_date,$end_date,$on,$mode=''){

    	$repeat_day='day';    	
    	switch ($mode) {
    			case '0':
    				$repeat_day='day';
    			break;
    			case '1':
    				$repeat_day='week';
    			break;
    			case '2':
    				$repeat_day='months';
    			break;
    			case '3':
    				$repeat_day='years';
    			break;
    		default:
    			# code...
    			break;
    	}
    		
    	$numberofdays='1';
    	switch ($on) {
    			case '0':
    				$numberofdays='1';
    			break;
    			case '1':
    				$numberofdays='2';
    			break;
    			case '2':
    				$numberofdays='3';
    			break;
    			case '3':
    				$numberofdays='4';    				
    			break;    		
    		default:
    				$numberofdays='1';    	
    			break;
    	}

	  	$startStamp = strtotime(  $start_date );
	    $endStamp   = strtotime(  $end_date );
	    if( $endStamp > $startStamp ){
	        while( $endStamp >= $startStamp ){
	            $dateArr[] = date( 'Y-m-d', $startStamp );
	            $startStamp = strtotime( ' +'.$numberofdays.' '.$repeat_day, $startStamp );
	        }
	        return $dateArr;    
	    }else{
	        return $startDate;
	    }
	   
    }
  	public function date_range_rec($start_date,$end_date,$on,$day,$mode=''){

    	$months='months';    	
    	switch ($mode) {
    			case '0':
    				$months='1 months';
    			break;
    			case '1':
    				$months='3 months';
    			break;
    			case '2':
    				$months='4 months';
    			break;
    			case '3':
    				$months='years';
    			break;
    		default:
    			# code...
    			break;
    	}
    		
    	$numberofdays='First';
    	switch ($on) {
    			case '0':
    				$numberofdays='First';
    			break;
    			case '1':
    				$numberofdays='Second';
    			break;
    			case '2':
    				$numberofdays='Third';
    			break;
    			case '3':
    				$numberofdays='Fourth';    				
    			break;    		
    		default:
    				$numberofdays='First';    	
    			break;
    	}
    	$daysname = [
			'1'=>'Monday',
			'2'=>'Tuesday',
			'3'=>'Wednesday',
			'4'=>'Thursday',
			'5'=>'Friday',
			'6'=>'Saturday',
			'7'=>'Sunday',
		];

	  	$startStamp = strtotime(  $start_date );
	    $endStamp   = strtotime(  $end_date );
	    if( $endStamp > $startStamp ){
	        while( $endStamp >= $startStamp ){
	        	$daynum=date('N', $startStamp);
	        	$y=date('Y',$startStamp);
	        	$m=date('F',$startStamp);
	        	$find=$numberofdays.' '.$daysname[$day].' Of '.$m.' '.$y;
    			$dtsStart = date('Y-m-d', strtotime($find));
        		
    			$dateArr[] =$dtsStart;
	        	// if($daynum==$day){
        		// 	echo "$dtsStart";
	        	// 	if($dtsStart==$startStamp){

	        	// 	}
	        	// }else{

	        	// }
	        	
	            $startStamp = strtotime( ' +'.$months, $startStamp ); 
	        }
	        return (isset($dateArr)) ?  $dateArr :array();    
	    }else{
	        return $startDate;
	    }
	   
    }

    public function store_date($arr,$event_id){
    
    	if(!empty($arr) && $event_id){
    		EventDate::withoutTrashed()->where('event_id',$event_id)->delete();
    		foreach ($arr as $key => $value) {
    			$obj=new EventDate;
    			$obj->event_id=$event_id;
    			$obj->date=$value;
    			$obj->save();
    		}
    	}
    }
    public function delete(Request $request){
    	$id=$request->id;
    	if(empty($id)){
    		$flashArr=array('status'=>'fail');
			return $flashArr;
    	}
    	$data=Event::withoutTrashed()->find($request->id);
    	if(empty($data)){
			$flashArr=array('status'=>'fail');
			return $flashArr;

    	}
    	$data->delete();
    	$flashArr=array('status'=>'succes');
    	return $flashArr;
		// return redirect()->route('event.list')->with('succes','Event creaetd');

    }
    public function update(Request $request){    	
    	$rules=array(
			'title.required'=>'Title required',
			'start_date.required'=>'Startdate required',
			'end_date.required'=>'Enddate required',
			'recurence_type.required'=>'Recurencetype required',
		);

		$validations=app('validator')->make($request->all(),[
			'title'=>'required',
			'start_date'=>'required',
			'end_date'=>'required',
			'recurence_type'=>'required',
		],$rules);

		if(!$validations->passes()){
			return redirect()->back()->withInput()->withErrors($validations);
		}

		$store=Event::withoutTrashed()->find($request->id);
		if(empty($store)){
    		return redirect()->route('event.list')->with('error','Event not found');

		}
		$store->title=$request->title;
		$store->start_date=date('Y-m-d',strtotime($request->start_date));
		$store->end_date=date('Y-m-d',strtotime($request->end_date));
		$store->recurence_type=$request->recurence_type;
		$event=$store->save();


		$resource=new EventRecurence;
		$resource->event_id=$store->id;
		if($request->recurence_type=='0'){
			$resource->repeat_every=$request->repeat_every;
			$resource->repeat_day=$request->repeat_day;
			$arr=$this->date_range($request->start_date,$request->end_date,$request->repeat_every,$request->repeat_day);
			$this->store_date($arr,$store->id);
			


		}
		if($request->recurence_type=='1'){
			$resource->repeat_on_the=$request->repeat_on_the;
			$resource->repeat_on_the_year=$request->repeat_on_the_year;
			$resource->days_id=$request->days_id;
			$arr=$this->date_range_rec(
				$request->start_date,
				$request->end_date,
				$request->repeat_on_the,
				$request->days_id,
				$request->repeat_on_the_year);
			$this->store_date($arr,$store->id);



		}
		$resource->save();
		return redirect()->route('event.list')->with('succes','Event creaetd');




    }

// 	public function returnBetweenDates( $startDate, $endDate ){

// 	    $startStamp = strtotime(  $startDate );
// 	    $endStamp   = strtotime(  $endDate );

// 	    if( $endStamp > $startStamp ){
// 	        while( $endStamp >= $startStamp ){
// 	            $dateArr[] = date( 'Y-m-d', $startStamp );
// 	            $startStamp = strtotime( ' +1 day ', $startStamp );
// 	        }
// 	        return $dateArr;    
// 	    }else{
// 	        return $startDate;
// 	    }

// }

}
