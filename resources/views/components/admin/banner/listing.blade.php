<div class="card-body table-responsive p-0">
    <table id='index' class="table table-hover table-bordered table-striped text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banner as $ban)
                <tr>
                    <td width="25%">{{ $ban->name }}</td>
                    {{-- <td width="25%">
                        @if (isset($ban->service) && !empty($ban->service))
                            <span class="badge bg-danger">{{ $ban->service->name }}</span>
                        @else
                            -
                        @endif
                    </td> --}}
                    <td class="project-actions" width="25%">

                        <a class="btn btn-info btn-sm" href="{{ route('admin.banner.edit', ['id' => $ban->id]) }}"
                            title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <a class="btn btn-danger btn-sm confirmation-modal"
                            data-url="{{ route('admin.banner.delete', ['id' => $ban->id]) }}" title="Delete">
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
