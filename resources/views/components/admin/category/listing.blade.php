<div class="card-body table-responsive p-0">
    <table id='index' class="table table-hover table-bordered table-striped text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($category as $categ)
                <tr>
                    <td width="25%">{{ $categ->name }}</td>
                    {{-- <td width="25%">
                        @if (isset($categ->service) && !empty($categ->service))
                            <span class="badge bg-danger">{{ $categ->service->name }}</span>
                        @else
                            -
                        @endif
                    </td> --}}
                    <td width="25%">
                        @if (isset($categ->parentCategory) && !empty($categ->parentCategory))
                            <span class="badge bg-primary">{{ $categ->parentCategory->name }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="project-actions" width="25%">

                        <a class="btn btn-info btn-sm" href="{{ route('admin.category.edit', ['id' => $categ->id]) }}"
                            title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <a class="btn btn-danger btn-sm confirmation-modal"
                            data-url="{{ route('admin.category.delete', ['id' => $categ->id]) }}" title="Delete">
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
