
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
            </select>
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
