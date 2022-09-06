<div class="card-body table-responsive p-0">
    <table id='index' class="table table-hover table-bordered table-striped text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Sub Category</th>
                <th>Parent Service</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td width="25%">{{ $service->name }}</td>
                    <td width="25%">
                        @if (isset($service->subCategory) && !empty($service->subCategory))
                            <span class="badge bg-danger">{{ $service->subCategory->name }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td width="25%">
                        @if (isset($service->parentService) && !empty($service->parentService))
                            <span class="badge bg-danger">{{ $service->parentService->name }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="project-actions" width="25%">
                        <a class="btn btn-info btn-sm" href="{{ route('admin.services.edit', ['id' => $service->id]) }}"
                            title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-danger btn-sm confirmation-modal"
                            data-url="{{ route('admin.services.delete', ['id' => $service->id]) }}" title="Delete">
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
