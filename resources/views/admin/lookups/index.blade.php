@extends('layouts.admin')

@section('title', 'Manage Lookups')
@section('header', 'Lookup Table Management')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"><i class="fas fa-file-medical"></i></div>
        <h3 style="margin-bottom: 0.5rem;">lookup_icd10</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Manage ICD10 codes and PP status.</p>
        <a href="{{ route('admin.lookups.show', 'icd10') }}" class="btn btn-primary" style="justify-content: center;">Manage Table</a>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 2.5rem; color: #f59e0b; margin-bottom: 1rem;"><i class="fas fa-hospital"></i></div>
        <h3 style="margin-bottom: 0.5rem;">lookup_hospcode</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Manage hospital codes and regions.</p>
        <a href="{{ route('admin.lookups.show', 'hospcode') }}" class="btn btn-primary" style="background: #f59e0b; justify-content: center;">Manage Table</a>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 2.5rem; color: #ec4899; margin-bottom: 1rem;"><i class="fas fa-bed"></i></div>
        <h3 style="margin-bottom: 0.5rem;">lookup_ward</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Manage ward and bed configurations.</p>
        <a href="{{ route('admin.lookups.show', 'ward') }}" class="btn btn-primary" style="background: #ec4899; justify-content: center;">Manage Table</a>
    </div>
</div>
@endsection
