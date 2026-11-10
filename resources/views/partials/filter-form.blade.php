{{--
    Filter form shared by the customer list and the filtered view.
    Submits with GET to /customers/filter, e.g. /customers/filter?gender=Female&birthday=custom&from=1990-01-01&to=1999-12-31
    Expects $selected (gender, birthday, from, to) from CustomerController to keep the chosen values.
--}}

<form method="GET" action="{{ route('customers.filter') }}" class="card-soft p-3 mb-3">
    <div class="row g-3 align-items-end">
        <div class="col-sm-6 col-lg">
            <label for="gender" class="filter-label"><i class="bi bi-gender-ambiguous me-1"></i> Gender</label>
            {{-- "selected" keeps the chosen value after the form is submitted --}}
            <select name="gender" id="gender" class="form-select filter-select">
                <option value="">All</option>
                <option value="Male" @selected($selected['gender'] === 'Male')>Male</option>
                <option value="Female" @selected($selected['gender'] === 'Female')>Female</option>
            </select>
        </div>
        <div class="col-sm-6 col-lg">
            <label for="birthday" class="filter-label"><i class="bi bi-calendar-event me-1"></i> Birthday</label>
            <select name="birthday" id="birthday" class="form-select filter-select">
                <option value="">All</option>
                <option value="after2000" @selected($selected['birthday'] === 'after2000')>Born after 2000</option>
                <option value="custom" @selected($selected['birthday'] === 'custom')>Custom date range</option>
            </select>
        </div>

        {{-- Date range inputs: only visible when "Custom date range" is selected (see script below) --}}
        <div class="col-sm-6 col-lg date-range">
            <label for="from" class="filter-label"><i class="bi bi-calendar3 me-1"></i> Born from</label>
            <input type="date" name="from" id="from" class="form-control filter-select" value="{{ $selected['from'] }}">
        </div>
        <div class="col-sm-6 col-lg date-range">
            <label for="to" class="filter-label"><i class="bi bi-calendar3 me-1"></i> Born to</label>
            <input type="date" name="to" id="to" class="form-control filter-select" value="{{ $selected['to'] }}">
        </div>

        <div class="col-lg-auto d-flex gap-2">
            <button type="submit" class="btn btn-accent flex-grow-1">
                <i class="bi bi-funnel me-1"></i> Apply Filter
            </button>
            <a href="{{ route('customers.index') }}" class="btn btn-soft">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </a>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Show the date inputs only when "Custom date range" is selected
    (function () {
        const select = document.getElementById('birthday');
        const rangeFields = document.querySelectorAll('.date-range');

        function toggleRange() {
            const isCustom = select.value === 'custom';
            rangeFields.forEach(function (field) {
                field.classList.toggle('d-none', !isCustom);
                // Disabled inputs are not sent, so the URL stays clean for other options
                field.querySelector('input').disabled = !isCustom;
            });
        }

        select.addEventListener('change', toggleRange);
        toggleRange(); // run once on page load so a selected "custom" keeps the fields visible
    })();
</script>
@endpush
