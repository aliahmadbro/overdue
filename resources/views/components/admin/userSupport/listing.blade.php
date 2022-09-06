<div class="card-body table-responsive p-0">
    <table id='index' class="table table-hover table-bordered table-striped text-nowrap">
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user as $u)
                <tr>
                    <td width="25%">{{ $u->question}}</td>
                    <td width="50%">{{$u->answer}}
                    </td>
                    <td class="project-actions" width="25%">

                        <a class="btn btn-info btn-sm" href="{{ route('admin.userSupport.edit', ['id' => $u->id]) }}"
                            title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <a class="btn btn-danger btn-sm confirmation-modal"
                            data-url="{{ route('admin.userSupport.delete', ['id' => $u->id]) }}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('custom_modals')
    @component('components.modals.confirmation')
    @endcomponent
@endpush
