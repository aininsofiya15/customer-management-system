<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Show every customer, ordered by ID (no filtering here).
     */
    public function index()
    {
        $customers = Customer::orderBy('id')->get();

        // Empty form values: nothing is selected on the full list
        $selected = ['gender' => null, 'birthday' => null];

        return view('customers.index', compact('customers', 'selected'));
    }

    /**
     * Show customers that match the filter form.
     */
    public function filter(Request $request)
    {
        // Start a query; filters are added only if they are valid
        $query = Customer::query();

        // Gender filter: accept only Male or Female (any letter case), ignore anything else
        $gender = ucfirst(strtolower((string) $request->query('gender')));
        if (! in_array($gender, ['Male', 'Female'])) {
            $gender = null;
        }
        if ($gender) {
            $query->where('gender', $gender);
        }

        // Birthday filter: only "after2000" is supported (born in 2001 or later)
        $after2000 = $request->query('birthday') === 'after2000';
        if ($after2000) {
            $query->whereYear('birthday', '>', 2000);
        }

        // Nothing valid to filter by: go back to the full list
        $hasFilter = $gender || $after2000;
        if (! $hasFilter) {
            return redirect()->route('customers.index');
        }

        // Both filters can apply together; results stay ordered by ID
        $customers = $query->orderBy('id')->get();

        // Title, description and removable chips for the active filters
        ['title' => $title, 'description' => $description, 'chips' => $chips]
            = $this->buildFilterSummary($gender, $after2000);

        // Values that keep the filter form filled in after submitting
        $selected = [
            'gender'   => $gender,
            'birthday' => $after2000 ? 'after2000' : null,
        ];

        return view('customers.filter', compact(
            'customers', 'title', 'description', 'chips', 'hasFilter', 'selected'
        ));
    }

    /**
     * Build the page title, description and filter chips from the active filters.
     */
    private function buildFilterSummary(?string $gender, bool $after2000): array
    {
        // Start of the title and description, depending on whether a gender is chosen
        $who     = $gender ? "$gender Customers" : 'Customers';
        $subject = $gender ? "Customers whose gender is $gender and who were" : 'Customers who were';

        if ($after2000) {
            $title       = "$who Born After 2000";
            $description = "$subject born in 2001 or later.";
        } else {
            $title       = $who;
            $description = "Customers whose gender is $gender.";
        }

        // Query-string pieces, so removing one filter keeps the other
        $genderParams   = array_filter(['gender' => $gender]);
        $birthdayParams = $after2000 ? ['birthday' => 'after2000'] : [];

        // Each chip links to the page without that filter (/customers when nothing is left)
        $chips = [];
        if ($gender) {
            $chips[] = ['label' => "Gender: $gender", 'tooltip' => 'Remove gender filter', 'url' => $this->filterUrl($birthdayParams)];
        }
        if ($after2000) {
            $chips[] = ['label' => 'Born after 2000', 'tooltip' => 'Remove birthday filter', 'url' => $this->filterUrl($genderParams)];
        }

        return compact('title', 'description', 'chips');
    }

    /**
     * URL for the given filters, or the full list when there are none.
     */
    private function filterUrl(array $params): string
    {
        return $params ? route('customers.filter', $params) : route('customers.index');
    }
}
