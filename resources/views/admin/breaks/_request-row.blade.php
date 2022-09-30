<tr id="{{$request->id}}">
    <th>{{ $request->date }}</th>
    <th>{{ $request->employee->name }}</th>
    <th>{{ $request->employee->code }}</th>
    <th>{{ $request->reason }}</th>
    <th>{{ $request->time }} minutes</th>
    @php $employeeRequests = $request->employee->breaks()->today()->get() @endphp
    <th>{{$employeeRequests->count()}} times / {{$employeeRequests->sum('actual_time')}} minutes</th>
    <th>
        <form action="{{ route('admin.breaks.update', $request->id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
            <input type="hidden" value="1" name="approve">
            <button type="submit" class="btn btn-success btn-sm approve"><i class="fa fa-thumbs-up"></i> @lang('site.approve')</button>
        </form>

        <form action="{{ route('admin.breaks.update', $request->id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
            <button type="submit" class="btn btn-danger btn-sm decline"><i class="fa fa-thumbs-down"></i> @lang('site.decline')</button>
        </form>
    </th>
</tr>