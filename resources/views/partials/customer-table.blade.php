{{-- Reusable customer table with live search. Expects a $customers collection. --}}
<div class="card-soft">
    {{-- Toolbar: result count and search box --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-bottom">
        <span class="text-muted-sm">
            Showing <strong id="visibleCount">{{ $customers->count() }}</strong> of {{ $customers->count() }} customers
        </span>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="search" id="customerSearch" class="form-control" placeholder="Search name, email, phone..."
                   aria-label="Search customers">
        </div>
    </div>

    <div class="table-wrap">
        <table class="table customer-table">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 18%;">Name</th>
                    <th style="width: 21%;">Email</th>
                    <th style="width: 25%;">Address</th>
                    <th style="width: 12%;">Phone Number</th>
                    <th style="width: 9%;">Gender</th>
                    <th style="width: 11%;">Birthday</th>
                </tr>
            </thead>
            <tbody id="customerRows">
                {{-- @forelse loops the rows; @empty runs when there are no customers --}}
                @forelse ($customers as $customer)
                    <tr class="customer-row">
                        <td class="text-muted-sm">{{ $loop->iteration }}</td>
                        <td class="fw-semibold" style="min-width: 140px;">{{ $customer->name }}</td>
                        <td class="text-muted-sm text-break" style="min-width: 240px;"><i class="bi bi-envelope me-1"></i>{{ $customer->email }}</td>
                        <td class="text-muted-sm" style="min-width: 180px;">{{ $customer->address }}</td>
                        <td class="text-muted-sm text-nowrap"><i class="bi bi-telephone me-1"></i>{{ $customer->phoneNumber }}</td>
                        <td class="text-nowrap">
                            <span class="pill {{ $customer->gender === 'Male' ? 'bg-male-soft' : 'bg-female-soft' }}">
                                <i class="bi {{ $customer->gender === 'Male' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>{{ $customer->gender }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <div class="fw-semibold">{{ $customer->birthday->format('d M Y') }}</div>
                            <div class="text-muted-sm">{{ $customer->birthday->age }} years old</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-person-x"></i>
                                <div class="fw-semibold">No customers found</div>
                                <div class="text-muted-sm">There are no records for this filter yet.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse

                {{-- Hidden row shown by JavaScript when the search matches nothing --}}
                <tr id="noMatchRow" class="d-none">
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-search"></i>
                            <div class="fw-semibold">No matching customers</div>
                            <div class="text-muted-sm">Try a different name, email or phone number.</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Instant search: hide table rows whose text does not contain the search term
    (function () {
        const input = document.getElementById('customerSearch');
        const rows = document.querySelectorAll('#customerRows .customer-row');
        const noMatch = document.getElementById('noMatchRow');
        const counter = document.getElementById('visibleCount');

        input.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach(function (row) {
                const match = row.textContent.toLowerCase().includes(term);
                row.classList.toggle('d-none', !match);
                if (match) visible++;
            });

            counter.textContent = visible;
            // Only show "no matches" when there were rows to search in the first place
            noMatch.classList.toggle('d-none', visible > 0 || rows.length === 0);
        });
    })();
</script>
@endpush
