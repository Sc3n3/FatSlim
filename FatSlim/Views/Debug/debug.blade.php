<div style="width:100%;height:110px;position:fixed;left:0;bottom:0;overflow:hidden;background-image: linear-gradient(to bottom, rgb(245, 245, 245) 0px, rgb(232, 232, 232) 100%);border-top: 1px solid rgb(221, 221, 221);clear:both;font-family:Tahoma,Verdana;font-size:14px;color:#494949">
	<div style="float:left;width:19%;margin-left:1%">
		<h3>System</h3>
		Load Time: {{ $___debugProfile___['exec_time'] }}
		<br />
		Peak Memory Usage: {{ $___debugProfile___['peak_memory'] }}
	</div>
	<div style="float:left;width:80%;height:110px;overflow:auto;">
		<h3>Query Log</h3>
		<ul>
			@forelse($___debugProfile___['query_log'] as $query)
				<li>
					{{ $query['query'] }} ( {{ $query['time'] }} )<br />
					<ul style="margin-top:5px;margin-bottom:15px">
						@foreach($query['bindings'] as $key => $val)
							<li>{{ $key }} => {{ $val }}</li>
						@endforeach
					</ul>
				</li>
			@empty
				<li>No Query Found</li>
			@endforelse
		</ul>
	</div>
</div>
<div style="height:110px">&nbsp;</div>