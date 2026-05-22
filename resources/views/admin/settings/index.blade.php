@extends('layouts.admin')

@section('title', 'System Settings')
@section('header', 'Main Settings')

@section('content')
<div class="header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="font-size: 1.25rem;">System Configuration</h2>
    <div style="display: flex; gap: 0.5rem;">
        <button type="button" class="btn btn-primary" style="background: #6366f1;" onclick="toggleModal('sendDataModal')">
            <i class="fas fa-paper-plane"></i> ส่งข้อมูล OPOD
        </button>
        <form action="{{ route('settings.upgrade') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" style="background: #10b981;">
                <i class="fas fa-database"></i> Upgrade Structure
            </button>
        </form>
    </div>
</div>

<!-- Send Data Modal (Custom Modal System) -->
<div id="sendDataModal" class="modal">
    <div class="modal-content" style="padding: 0; max-width: 520px;">
        <div style="background: #6366f1; color: white; padding: 1.25rem 1.5rem; border-radius: 1.5rem 1.5rem 0 0; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 600; font-size: 1rem;"><i class="fas fa-paper-plane"></i> ส่งข้อมูล OPOD</span>
            <button type="button" onclick="toggleModal('sendDataModal')" style="background: transparent; border: none; color: white; font-size: 1.25rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form id="opodSendForm">
            @csrf
            <div style="padding: 1.5rem 1.75rem;">
                <p style="color: #6b7280; margin-bottom: 1.25rem; text-align: center;">กรุณาเลือกช่วงเวลาที่ต้องการส่งข้อมูลไปยังระบบกลาง</p>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="flex: 1;">
                        <input type="text" id="start_date_th" placeholder="วันที่เริ่มต้น" readonly style="width: 100%; border: 1px solid #6366f1; border-radius: 0.75rem; padding: 0.6rem 1rem; font-size: 0.95rem; background: white; cursor: pointer; color: #1e293b;">
                        <input type="hidden" id="start_date" name="start_date">
                    </div>
                    <span style="font-weight: 600; color: #6b7280; white-space: nowrap;">ถึง</span>
                    <div style="flex: 1;">
                        <input type="text" id="end_date_th" placeholder="วันที่สิ้นสุด" readonly style="width: 100%; border: 1px solid #6366f1; border-radius: 0.75rem; padding: 0.6rem 1rem; font-size: 0.95rem; background: white; cursor: pointer; color: #1e293b;">
                        <input type="hidden" id="end_date" name="end_date">
                    </div>
                </div>
            </div>
            <div style="padding: 0 1.75rem 1.75rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="toggleModal('sendDataModal')" style="padding: 0.6rem 1.5rem; border-radius: 2rem; background: #9ca3af; color: white; border: none; font-weight: 600; cursor: pointer;">ยกเลิก</button>
                <button type="button" onclick="submitOpodSend()" style="padding: 0.6rem 1.5rem; border-radius: 2rem; background: #6366f1; color: white; border: none; font-weight: 600; cursor: pointer;">ส่งข้อมูลทันที</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const thaiMonthsShort = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];

function formatThaiDisplay(date) {
    const d = date.getDate();
    const m = thaiMonthsShort[date.getMonth()];
    const y = date.getFullYear();
    return d + ' ' + m + ' ' + y;
}

function makeThaiFlatpickr(displayId, hiddenId) {
    const fp = flatpickr('#' + displayId, {
        locale: 'th',
        dateFormat: 'Y-m-d',
        allowInput: false,
        onReady: function(_, __, fp) {
            // Add Today button
            const todayBtn = document.createElement('button');
            todayBtn.type = 'button';
            todayBtn.textContent = 'วันนี้';
            todayBtn.style.cssText = 'display:block;width:100%;padding:0.4rem;margin-top:0.5rem;background:#6366f1;color:white;border:none;border-radius:0.5rem;cursor:pointer;font-size:0.9rem;font-family:inherit;';
            todayBtn.addEventListener('click', function() {
                fp.setDate(new Date(), true);
                fp.close();
            });
            fp.calendarContainer.appendChild(todayBtn);
            // Style calendar header
            const monthEl = fp.calendarContainer.querySelector('.flatpickr-month');
            if (monthEl) monthEl.style.cssText += 'background:#6366f1;color:white;border-radius:8px 8px 0 0;';
        },
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                const d = selectedDates[0];
                const iso = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
                document.getElementById(hiddenId).value = iso;
                document.getElementById(displayId).value = formatThaiDisplay(d);
            }
        }
    });
    return fp;
}

document.addEventListener('DOMContentLoaded', function() {
    makeThaiFlatpickr('start_date_th', 'start_date');
    makeThaiFlatpickr('end_date_th', 'end_date');
});
</script>
<script>
function submitOpodSend() {
    const start = document.getElementById('start_date').value;
    const end   = document.getElementById('end_date').value;
    if (!start || !end) {
        Swal.fire({ icon: 'warning', title: 'กรุณาเลือกวันที่', text: 'โปรดระบุวันที่เริ่มต้นและวันที่สิ้นสุดก่อนส่งข้อมูล' });
        return;
    }
    toggleModal('sendDataModal');
    Swal.fire({
        title: 'กำลังส่งข้อมูล...',
        text: 'โปรดรอสักครู่ ระบบกำลังประมวลผลและส่งข้อมูลไปยังระบบกลาง',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });
    const form = document.getElementById('opodSendForm');
    const csrf = form.querySelector('input[name=_token]').value;
    fetch("{{ url('/api/opod-send') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ start_date: start, end_date: end })
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            const r = data.received ?? {};
            Swal.fire({
                icon: 'success',
                title: 'ส่งข้อมูลสำเร็จ!',
                html: '<b>OPD:</b> ' + (r.opd ?? '-') + ' | <b>IPD:</b> ' + (r.ipd ?? '-') + ' | <b>Bed:</b> ' + (r.ipd_bed ?? '-'),
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'ตกลง'
            });
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message ?? 'ไม่สามารถส่งข้อมูลได้', confirmButtonColor: '#6366f1' });
        }
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'Connection Error', text: 'ไม่สามารถเชื่อมต่อกับ API ได้: ' + err.message, confirmButtonColor: '#6366f1' });
    });
}
</script>

<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Setting Name</th>
                <th>Value</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
            <tr>
                <td><strong>{{ $setting->name }}</strong></td>
                <td>
                    <form id="form-{{ $setting->id }}" action="{{ route('settings.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="value" value="{{ $setting->value }}" style="width: 100%; padding: 0.6rem 1rem; border: 1px solid var(--border); border-radius: 0.75rem; background: #f8fafc; transition: all 0.2s;">
                    </form>
                </td>
                <td>
                    <button type="submit" form="form-{{ $setting->id }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.6rem;">
                        <i class="fas fa-save"></i> Save
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card" style="margin-top: 2rem; padding: 1.5rem;">
    <h3 style="font-size: 1.1rem; margin-bottom: 1rem;"><i class="far fa-clock"></i> การตั้งค่า Windows Task Scheduler</h3>
    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
        <div class="row">
            <div class="col-md-3">
                <div style="background: white; padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border); height: 100%;">
                    <small style="color: #6b7280; display: block; margin-bottom: 0.5rem; font-weight: 600;">PROGRAM/SCRIPT:</small>
                    <code style="font-weight: bold; color: #1e293b; font-size: 1rem;">powershell.exe</code>
                </div>
            </div>
            <div class="col-md-9">
                <div style="background: white; padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border); position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="font-size: 0.9rem; font-weight: 600; color: #059669;"><i class="fas fa-sync-alt"></i> Trigger Send OPOD</span>
                        <button class="btn btn-sm" onclick="copyToClipboard('ps-cmd-1')" style="background: #f1f5f9; border-radius: 0.5rem; padding: 0.4rem 0.6rem;">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>
                    <code id="ps-cmd-1" style="font-size: 0.85rem; color: #475569; display: block; line-height: 1.6;">-WindowStyle Hidden -Command "Invoke-RestMethod -Uri '{{ url('/api/opod-send') }}' -Method Post"</code>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    var text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert('คัดลอกคำสั่งเรียบร้อยแล้ว!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection
