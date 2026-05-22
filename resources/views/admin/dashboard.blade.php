@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Welcome OPOD_Agent')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; border-radius: 1rem; background: #eef2ff; color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Total Users</div>
            <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['users'] }}</div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; border-radius: 1rem; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-file-medical"></i>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">ICD10 Lookups</div>
            <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['lookup_icd10'] }}</div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; border-radius: 1rem; background: #fff7ed; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-hospital"></i>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Hospcode Lookups</div>
            <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['lookup_hospcode'] }}</div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; border-radius: 1rem; background: #fdf2f8; color: #ec4899; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-bed"></i>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Ward Lookups</div>
            <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['lookup_ward'] }}</div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <h2 style="font-size: 1.25rem; margin-bottom: 1rem;">System Activity</h2>
    <p style="color: var(--text-muted);">Welcome to the OPOD Agent management system. Use the sidebar to manage users and system lookup tables.</p>
</div>
@endsection
