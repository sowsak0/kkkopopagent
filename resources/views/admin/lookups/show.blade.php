@extends('layouts.admin')

@section('title', 'Manage ' . $title)
@section('header', 'Managing ' . $title)

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('admin.lookups.index') }}" style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-weight: 500;">
        <i class="fas fa-arrow-left"></i> Back to Lookups
    </a>
</div>

<div class="header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="font-size: 1.25rem;">Data Records</h2>
    <div style="display: flex; gap: 0.5rem;">
        @if($type === 'ward')
        <form action="{{ route('admin.lookups.ward.import') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" style="background: #059669;" onclick="return confirm('นำเข้าข้อมูล Ward จาก HOSxP ใช่หรือไม่?')">
                <i class="fas fa-file-import"></i> นำเข้า Ward
            </button>
        </form>
        @endif
        <button onclick="toggleModal('addRecordModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Record
        </button>
    </div>
</div>

@if($type === 'icd10')
<div style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; background: #e2e8f0; padding: 0.25rem; border-radius: 0.75rem; width: fit-content;">
    <button onclick="filterType('all')" id="btn-all" class="tab-btn active">All Records</button>
    <button onclick="filterType('PP')" id="btn-pp" class="tab-btn">PP Only</button>
    <button onclick="filterType('ODS')" id="btn-ods" class="tab-btn">ODS Only</button>
</div>
@endif

<div class="card">
    <div style="overflow-x: auto;">
        <table id="lookupTable" class="table table-hover">
            <thead>
                <tr>
                    @if($type === 'icd10')
                        <th>ICD10</th><th>PP</th><th>ODS</th>
                    @elseif($type === 'hospcode')
                        <th>Hospcode</th><th>Name</th><th>main ucs</th><th>main sss</th><th>In Province</th>
                    @else
                        <th>Ward</th><th>Name</th><th>Normal</th><th>ชาย</th><th>หญิง</th><th>VIP</th><th>LR</th><th>Homeward</th><th>Bed Qty</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    @if($type === 'icd10')
                        <td><strong>{{ $row->icd10 }}</strong></td>
                        <td><span class="badge {{ $row->pp === 'Y' ? 'bg-success' : 'bg-secondary' }}">{{ $row->pp ?? 'N' }}</span></td>
                        <td><span class="badge {{ $row->ods === 'Y' ? 'bg-primary' : 'bg-secondary' }}">{{ $row->ods ?? 'N' }}</span></td>
                    @elseif($type === 'hospcode')
                        <td><strong>{{ $row->hospcode }}</strong></td>
                        <td>{{ $row->hospcode_name }}</td>
                        <td><span class="badge {{ $row->hmain_ucs === 'Y' ? 'bg-info' : 'bg-secondary' }}">{{ $row->hmain_ucs ?? 'N' }}</span></td>
                        <td><span class="badge {{ $row->hmain_sss === 'Y' ? 'bg-primary' : 'bg-secondary' }}">{{ $row->hmain_sss ?? 'N' }}</span></td>
                        <td><span class="badge {{ $row->in_province === 'Y' ? 'bg-success' : 'bg-secondary' }}">{{ $row->in_province ?? 'N' }}</span></td>
                    @else
                        <td><strong>{{ $row->ward }}</strong></td>
                        <td>{{ $row->ward_name }}</td>
                        @foreach(['ward_normal','ward_m','ward_f','ward_vip','ward_lr','ward_homeward'] as $col)
                        <td>
                            <div class="form-check form-switch" style="margin:0;">
                                <input class="form-check-input ward-toggle" type="checkbox" role="switch"
                                    data-ward="{{ $row->ward }}"
                                    data-col="{{ $col }}"
                                    {{ ($row->$col ?? 'N') === 'Y' ? 'checked' : '' }}
                                    style="cursor:pointer; width:2.2em; height:1.2em;">
                            </div>
                        </td>
                        @endforeach
                        <td>{{ $row->bed_qty }}</td>
                    @endif
                    <td>
                        @php 
                            $id = $type === 'ward' ? $row->ward : ($type === 'icd10' ? $row->icd10 : $row->hospcode);
                        @endphp
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick='openEditModal(@json($row))' class="btn btn-primary" style="padding: 0.5rem; background: #f59e0b;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.lookups.destroy', [$type, $id]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.5rem;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        window.table = $('#lookupTable').DataTable({
            "pageLength": 10,
            "order": [[0, "asc"]],
            "language": {
                "search": "ค้นหา:",
                "lengthMenu": "แสดง _MENU_ รายการ",
                "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });
    });

    function filterType(type) {
        $('.tab-btn').removeClass('active');
        $(`#btn-${type.toLowerCase()}`).addClass('active');

        if (type === 'all') {
            window.table.columns(1).search('').columns(2).search('').draw();
        } else if (type === 'PP') {
            window.table.columns(1).search('Y').columns(2).search('').draw();
        } else if (type === 'ODS') {
            window.table.columns(1).search('').columns(2).search('Y').draw();
        }
    }

    function openEditModal(data) {
        const type = "{{ $type }}";
        const form = document.getElementById('editRecordForm');
        let id = '';

        if (type === 'icd10') {
            id = data.icd10;
            form.elements['icd10'].value = data.icd10;
            form.elements['pp'].checked = data.pp === 'Y';
            form.elements['ods'].checked = data.ods === 'Y';
        } else if (type === 'hospcode') {
            id = data.hospcode;
            form.elements['hospcode'].value = data.hospcode;
            form.elements['hospcode_name'].value = data.hospcode_name || '';
            form.elements['hmain_ucs'].checked = data.hmain_ucs === 'Y';
            form.elements['hmain_sss'].checked = data.hmain_sss === 'Y';
            form.elements['in_province'].checked = data.in_province === 'Y';
        } else {
            id = data.ward;
            form.elements['ward'].value = data.ward;
            form.elements['ward_name'].value = data.ward_name || '';
            form.elements['bed_qty'].value = data.bed_qty || 0;
            const cols = ['ward_normal', 'ward_m', 'ward_f', 'ward_vip', 'ward_lr', 'ward_homeward'];
            cols.forEach(c => {
                if(form.elements[c]) form.elements[c].checked = data[c] === 'Y';
            });
        }

        form.action = `{{ url('/admin/lookups') }}/${type}/${id}`;
        toggleModal('editRecordModal');
    }
</script>
@endsection

@section('styles')
<style>
    .bg-success { background: #10b981; color: white; }
    .bg-primary { background: #6366f1; color: white; }
    .bg-info { background: #0ea5e9; color: white; }
    .bg-secondary { background: #e2e8f0; color: #64748b; }
    
    .tab-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    .tab-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.25rem;
    }
    .dataTables_wrapper .dataTables_paginate ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.25rem;
    }
    .dataTables_wrapper .dataTables_paginate li {
        display: inline-block;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: block !important;
        padding: 0.5rem 1rem !important;
        border: 1px solid var(--border) !important;
        border-radius: 0.5rem !important;
        background: white !important;
        color: var(--text-main) !important;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none !important;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        padding: 0.6rem 1rem;
        outline: none;
        width: 250px;
        transition: all 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 1.5rem;
    }
</style>
@endsection

<!-- Add Modal -->
<div id="addRecordModal" class="modal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem;">Add New Record</h2>
            <button onclick="toggleModal('addRecordModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="{{ route('admin.lookups.store', $type) }}" method="POST">
            @csrf
            @if($type === 'icd10')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">ICD10 Code</label>
                    <input type="text" name="icd10" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">PP Status</label>
                    <label class="switch">
                        <input type="checkbox" name="pp" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">ODS Status</label>
                    <label class="switch">
                        <input type="checkbox" name="ods" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
            @elseif($type === 'hospcode')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Hospcode</label>
                    <input type="text" name="hospcode" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Hospital Name</label>
                    <input type="text" name="hospcode_name" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">HMAIN UCS</label>
                    <label class="switch">
                        <input type="checkbox" name="hmain_ucs" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">HMAIN SSS</label>
                    <label class="switch">
                        <input type="checkbox" name="hmain_sss" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">In Province</label>
                    <label class="switch">
                        <input type="checkbox" name="in_province" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
            @else
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Ward Code</label>
                    <input type="text" name="ward" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Ward Name</label>
                    <input type="text" name="ward_name" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Bed Quantity</label>
                    <input type="number" name="bed_qty" value="0" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                @foreach(['ward_normal' => 'Normal', 'ward_m' => 'ชาย', 'ward_f' => 'หญิง', 'ward_vip' => 'VIP', 'ward_lr' => 'LR', 'ward_homeward' => 'Homeward'] as $col => $label)
                <div style="margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">{{ $label }}</label>
                    <label class="switch">
                        <input type="checkbox" name="{{ $col }}" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                @endforeach
            @endif
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                <i class="fas fa-save"></i> Save Record
            </button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editRecordModal" class="modal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem;">Edit Record</h2>
            <button onclick="toggleModal('editRecordModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="editRecordForm" action="" method="POST">
            @csrf
            @method('PUT')
            @if($type === 'icd10')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">ICD10 Code</label>
                    <input type="text" name="icd10" readonly style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem; background: #f1f5f9;">
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">PP Status</label>
                    <label class="switch">
                        <input type="checkbox" name="pp" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">ODS Status</label>
                    <label class="switch">
                        <input type="checkbox" name="ods" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
            @elseif($type === 'hospcode')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Hospcode</label>
                    <input type="text" name="hospcode" readonly style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem; background: #f1f5f9;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Hospital Name</label>
                    <input type="text" name="hospcode_name" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">HMAIN UCS</label>
                    <label class="switch">
                        <input type="checkbox" name="hmain_ucs" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">HMAIN SSS</label>
                    <label class="switch">
                        <input type="checkbox" name="hmain_sss" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">In Province</label>
                    <label class="switch">
                        <input type="checkbox" name="in_province" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
            @else
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Ward Code</label>
                    <input type="text" name="ward" readonly style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem; background: #f1f5f9;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Ward Name</label>
                    <input type="text" name="ward_name" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Bed Quantity</label>
                    <input type="number" name="bed_qty" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
                </div>
                @foreach(['ward_normal' => 'Normal', 'ward_m' => 'ชาย', 'ward_f' => 'หญิง', 'ward_vip' => 'VIP', 'ward_lr' => 'LR', 'ward_homeward' => 'Homeward'] as $col => $label)
                <div style="margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                    <label style="font-size: 0.875rem;">{{ $label }}</label>
                    <label class="switch">
                        <input type="checkbox" name="{{ $col }}" value="Y">
                        <span class="slider"></span>
                    </label>
                </div>
                @endforeach
            @endif
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem; background: #f59e0b;">
                <i class="fas fa-save"></i> Update Record
            </button>
        </form>
    </div>
</div>
@endsection

@if($type === 'ward')
@section('scripts')
<script>
document.querySelectorAll('.ward-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const ward = this.dataset.ward;
        const col  = this.dataset.col;
        const self = this;

        fetch('{{ route("admin.lookups.ward.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') 
                    ? document.querySelector('meta[name="csrf-token"]').content 
                    : '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ward: ward, col: col })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) {
                self.checked = !self.checked; // revert
            }
        })
        .catch(() => { self.checked = !self.checked; });
    });
});
</script>
@endsection
@endif
