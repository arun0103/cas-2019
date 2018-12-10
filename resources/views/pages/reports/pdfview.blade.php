
<style>
	table{
		border: 1px solid black;
	}
</style>	
<div class="container">
	<br/>
	<table>
		<tr>
			<th>No</th>
			<th>Leave Name</th>
			<th>Approved By</th>
		</tr>
		@foreach ($leaves as $key => $leave)
		<tr>
			<td>{{ ++$key }}</td>
			<td>{{ $leave->leave_id }}</td>
			<td>{{ $leave->approved_by }}</td>
		</tr>
		@endforeach
	</table>
</div>
