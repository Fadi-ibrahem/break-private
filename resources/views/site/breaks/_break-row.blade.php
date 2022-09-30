<tr @if (is_null($break->is_approved))
    class="table-warning"
    @else
    class="table-{{$break->is_approved ? 'success': 'danger'}}"
    @endif
    id="{{$break->id}}"
    >
    <th>{{ $break->date }}</th>
    <th>{{ $break->reason }}</th>
    @if (is_null($break->is_approved))
    <th>waiting supervisor</th>
    @else
    <th>{{$break->is_approved ? 'yes': 'no'}}</th>
    @endif
    <th>{{ $break->time }} minutes</th>
    <th>{{ $break->actual_time }} minutes</th>
    <th>{{ $break->start_time ? $break->start_time->format('h:i A') :'ـــــــــــــــــــــ'}}</th>
    <th>{{ $break->end_time ? $break->end_time->format('h:i A') : 'ـــــــــــــــــــــ' }}</th>
</tr>