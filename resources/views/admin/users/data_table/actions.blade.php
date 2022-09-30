@if (auth()->user()->hasPermission('update_users'))
    <a href="{{ route('admin.users.edit', $id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
        @lang('site.edit')</a>
@endif

@if (auth()->user()->hasPermission('delete_users'))
    <form action="{{ route('admin.users.destroy', $id) }}" class="my-1 my-xl-0" method="post"
        style="display: inline-block;">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> @lang('site.delete')</button>
    </form>
@endif

@if (auth()->user()->type == 'supervisor')
    <input type="checkbox" class="check" @if ($is_assist) {{ 'checked' }} @endif
        class="assist-checkbox" id="{{ $id }}">
    <label> Is Assistant ?</label>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        $("input:checkbox").click(function() {

            let status = null;

            if ($(this).prop('checked') == true) {
                status = 1;
            } else if ($(this).prop('checked') == false) {
                status = 0;
            }

            $.ajax({
                url: "{{ route('admin.users.assist') }}",
                type: 'POST',
                data: {
                    id: $(this).attr("id"),
                    status: status
                },
                success: function(result) {
                    new Noty({
                        layout: 'topRight',
                        type: 'alert',
                        text: result.message,
                        killer: true,
                        timeout: 2000,
                    }).show();
                }
            });
        });
    });
</script>
