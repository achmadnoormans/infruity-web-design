@if(Session::has('success'))
<div class="alert alert-success">
	<strong>PERHATIAN !</strong> 
	<br>
	<h6> <b>{{ Session::get('success') }}</b> </h6>
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
	<strong>PERHATIAN !</strong> 
	<br>
	<b>{{  Session::get('error') }}</b>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger" style="margin-top: 10px">
	<ul>
		<strong>PERHATIAN !</strong> 
		
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif