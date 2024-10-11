<?php

// app/Http/Controllers/CompanyController.php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    // Επιστροφή λίστας εταιρειών του συνδεδεμένου χρήστη
    public function index()
    {
        $companies = Auth::user()?->companies ?? collect([]);
        return view('company.index', compact('companies'));
    }

    // Εμφάνιση φόρμας δημιουργίας εταιρείας
    public function create()
    {
        return view('company.create');
    }

    // Αποθήκευση νέας εταιρείας
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'website' => 'required|url',
            'email' => 'required|email',
        ]);

        Auth::user()->companies()->create($request->all());

        return redirect()->route('company.index')
                         ->with('success', 'Η εταιρεία δημιουργήθηκε με επιτυχία!');
    }

    // Εμφάνιση φόρμας επεξεργασίας
    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        return view('company.edit', compact('company'));
    }

    // Ενημέρωση εταιρείας
    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'website' => 'required|url',
            'email' => 'required|email',
        ]);

        $company->update($request->all());

        return redirect()->route('company.index')
                         ->with('success', 'Η εταιρεία ενημερώθηκε με επιτυχία!');
    }

    // Διαγραφή εταιρείας
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);
        $company->delete();

        return redirect()->route('company.index')
                         ->with('success', 'Η εταιρεία διαγράφηκε με επιτυχία!');
    }
}