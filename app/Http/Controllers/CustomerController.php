<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Show every customer, ordered by ID (no filtering here).
     */
    public function index()
    {
        $customers = Customer::orderBy('id')->get();

        // Empty form values: nothing is selected on the full list
        $selected = ['gender' => null, 'birthday' => null, 'from' => null, 'to' => null];

        return view('customers.index', compact('customers', 'selected'));
    }

    /**
     * Show customers that match the filter form.
     * Examples: /customers/filter?gender=Female&birthday=after2000
     *           /customers/filter?birthday=custom&from=1990-01-01&to=1999-12-31
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

        // Birthday filter: "after2000" or a "custom" date range; anything else is ignored
        $birthday = $request->query('birthday');
        $from     = $request->query('from');   // e.g. 1990-01-01
        $to       = $request->query('to');     // e.g. 1999-12-31
        $error    = null;

        if ($birthday === 'after2000') {
            // Born in 2001 or later
            $query->whereYear('birthday', '>', 2000);
        } elseif ($birthday === 'custom') {
            // Check the dates first; the format must be a real Y-m-d date
            $rules = [
                'from' => ['nullable', 'date_format:Y-m-d'],
                'to'   => ['nullable', 'date_format:Y-m-d'],
            ];
            // Only compare the two dates when both are given
            if ($from && $to) {
                $rules['to'][] = 'after_or_equal:from';
            }

            $validator = Validator::make(['from' => $from, 'to' => $to], $rules, [
                'from.date_format'  => 'The "Born from" date is not a valid date.',
                'to.date_format'    => 'The "Born to" date is not a valid date.',
                'to.after_or_equal' => 'The "Born from" date cannot be after the "Born to" date.',
            ]);

            if ($validator->fails()) {
                // Do not crash: skip the date filter and show a friendly message instead
                $error = $validator->errors()->first();
            } else {
                // Each side is optional, so "from" only or "to" only also works
                if ($from) {
                    $query->whereDate('birthday', '>=', $from);
                }
                if ($to) {
                    $query->whereDate('birthday', '<=', $to);
                }
            }
        }

        // The filters that were actually applied
        $after2000 = $birthday === 'after2000';
        $custom    = $birthday === 'custom' && ! $error && ($from || $to);
        $hasFilter = $gender || $after2000 || $custom;

        // Nothing valid to filter by and no error to show: go back to the full list
        if (! $hasFilter && ! $error) {
            return redirect()->route('customers.index');
        }

        // All filters can apply together; results stay ordered by ID
        $customers = $query->orderBy('id')->get();

        // Title, description and removable chips for the active filters
        ['title' => $title, 'description' => $description, 'chips' => $chips]
            = $this->buildFilterSummary($gender, $after2000, $custom, $from, $to);

        // Values that keep the filter form filled in after submitting
        $selected = [
            'gender'   => $gender,
            'birthday' => $birthday,
            'from'     => $from,
            'to'       => $to,
        ];

        return view('customers.filter', compact(
            'customers', 'title', 'description', 'chips', 'hasFilter', 'error', 'selected'
        ));
    }

    /**
     * Build the page title, description and filter chips from the active filters.
     */
    private function buildFilterSummary(?string $gender, bool $after2000, bool $custom, ?string $from, ?string $to): array
    {
        // Readable date range, e.g. "01 Jan 1990 to 31 Dec 1999"
        $rangeText = null;
        if ($custom) {
            $fromText = $from ? Carbon::parse($from)->format('d M Y') : null;
            $toText   = $to ? Carbon::parse($to)->format('d M Y') : null;

            if ($fromText && $toText) {
                $rangeText = "$fromText to $toText";
            } elseif ($fromText) {
                $rangeText = "from $fromText";
            } else {
                $rangeText = "up to $toText";
            }
        }

        // Start of the title and description, depending on whether a gender is chosen
        $who     = $gender ? "$gender Customers" : 'Customers';
        $subject = $gender ? "Customers whose gender is $gender and who were" : 'Customers who were';

        if ($after2000) {
            $title       = "$who Born After 2000";
            $description = "$subject born in 2001 or later.";
        } elseif ($custom) {
            $title       = "$who Born " . ucfirst($rangeText);
            $description = "$subject born $rangeText.";
        } elseif ($gender) {
            $title       = $who;
            $description = "Customers whose gender is $gender.";
        } else {
            // Only reached when the date range was invalid and no other filter is active
            $title       = 'Filtered Customers';
            $description = 'No filter could be applied, so all customers are shown.';
        }

        // Query-string pieces, so removing one filter keeps the other
        $genderParams   = array_filter(['gender' => $gender]);
        $birthdayParams = $after2000
            ? ['birthday' => 'after2000']
            : ($custom ? array_filter(['birthday' => 'custom', 'from' => $from, 'to' => $to]) : []);

        // Each chip links to the page without that filter (/customers when nothing is left)
        $chips = [];
        if ($gender) {
            $chips[] = ['label' => "Gender: $gender", 'tooltip' => 'Remove gender filter', 'url' => $this->filterUrl($birthdayParams)];
        }
        if ($after2000) {
            $chips[] = ['label' => 'Born after 2000', 'tooltip' => 'Remove birthday filter', 'url' => $this->filterUrl($genderParams)];
        }
        if ($custom) {
            $chips[] = ['label' => "Born: $rangeText", 'tooltip' => 'Remove date range filter', 'url' => $this->filterUrl($genderParams)];
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
