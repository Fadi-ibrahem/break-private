<tr id="b-{{$break->id}}">
    <th>{{ $break->date }}</th>
    <th>{{ $break->employee->name }}</th>
    <th>{{ $break->employee->code }}</th>
    <th>{{ $break->reason }}</th>
    <th>{{ $break->start_time ? $break->start_time->format('h:i A'): '' }}</th>
    <th>{{ $break->time }} minutes</th>
    <th data-timestamp="{{$break->start_time->timestamp}}"></th>
</tr>