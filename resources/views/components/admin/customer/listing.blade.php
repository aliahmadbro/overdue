<div class="card-body table-responsive p-0">
    <table id='index' class="table table-hover table-bordered table-striped text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customer as $pro)
                <tr>
                    <td width="25%">{{ $pro->first_name }}</td>
                    {{-- <td width="25%">
                        @if (isset($categ->service) && !empty($categ->service))
                            <span class="badge bg-danger">{{ $categ->service->name }}</span>
                        @else
                            -
                        @endif
                    </td> --}}
                    <td width="25%">
                        {{$pro->phone_no}}
                    </td>
                    <td width="20%">
                        @if ($pro->verify == 0)
                            <i class="fas fa-lock-open text-primary" title="Active"></i>
                        @else
                            {{-- ($seller->status == PENDING) --}}
                            <i class="fas fa-lock text-secondary" title="Pending"></i>
                        @endif
                    </td>
                    <td class="project-actions" width="30%">
                        
                        @if ($pro->verify == 1)
                        <a class="btn btn-primary btn-sm confirmation-modal"
                        data-url="{{ route('admin.customer.status', ['id' => $pro->id, 'status' => 0]) }}">
                        <i class="fas fa-lock-open">
                        </i>
                        Activate
                        </a>
                        @else
                        
                        <a class="btn btn-secondary btn-sm confirmation-modal"
                        data-url="{{ route('admin.customer.status', ['id' => $pro->id, 'status' => 1]) }}">
                        <i class="fas fa-lock">
                        </i>
                        Inactivate
                        </a>

                        @endif

                        <a class="btn btn-danger btn-sm confirmation-modal"
                            data-url="{{ route('admin.customer.delete', ['id' => $pro->id]) }}" title="Delete">
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
