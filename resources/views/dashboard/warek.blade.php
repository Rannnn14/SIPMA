<x-layout>
    <x-slot:title>Dashboard Wakil Rektor III | SIPMA</x-slot:title>

    <div class="container-fluid px-0">
        <div class="card border-0 text-white shadow-sm mb-4 overflow-hidden sop-banner">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.15) !important;">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); font-size: 1.1rem;">
                            <i class="fas fa-route text-white"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-white mb-0" style="letter-spacing: -0.2px;">Alur Penanganan Pelanggaran Mahasiswa</h5>
                            <small class="text-white-50">Monitoring laporan dan rekapitulasi seluruh fakultas</small>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('warek.dashboard') }}" class="m-0 mt-2 mt-md-0">
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            <i class="fas fa-calendar-alt text-white-50 mr-2" style="font-size: 0.82rem;"></i>
                            <select name="academic_year" class="border-0 bg-transparent text-white font-weight-bold" style="font-size: 0.85rem; outline: none; cursor: pointer;" onchange="this.form.submit()">
                                <option value="all" style="color:#000;" {{ $selectedAcademicYear === 'all' ? 'selected' : '' }}>Semua Periode</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->year }}" style="color:#000;" {{ $selectedAcademicYear === $ay->year ? 'selected' : '' }}>
                                        T.A. {{ $ay->year }}{{ $ay->is_active ? ' (Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <div class="row text-white">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                            <div class="d-flex align-items-center mb-1"><span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2" style="width:26px;height:26px;background:#fff;color:#0d9488;font-size:.78rem;">1</span><span class="font-weight-bold small">Laporan Masuk</span></div>
                            <p class="mb-0 text-white-50" style="font-size:.78rem;line-height:1.45;">Mahasiswa atau Kemahasiswaan memasukkan data pelanggaran dan bukti digital.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                            <div class="d-flex align-items-center mb-1"><span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2" style="width:26px;height:26px;background:#fff;color:#0d9488;font-size:.78rem;">2</span><span class="font-weight-bold small">Verifikasi Data</span></div>
                            <p class="mb-0 text-white-50" style="font-size:.78rem;line-height:1.45;">Bagian Kemahasiswaan memvalidasi identitas terlapor dan keaslian bukti.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                            <div class="d-flex align-items-center mb-1"><span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2" style="width:26px;height:26px;background:#fff;color:#0d9488;font-size:.78rem;">3</span><span class="font-weight-bold small">Rekapitulasi Fakultas</span></div>
                            <p class="mb-0 text-white-50" style="font-size:.78rem;line-height:1.45;">Warek memantau tren dan rincian laporan dari setiap fakultas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow h-100" style="border-radius:16px;background:linear-gradient(135deg,#0f766e 0%,#0d9488 60%,#14b8a6 100%);">
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:rgba(255,255,255,.15);"><i class="fas fa-folder-open fa-xl text-white"></i></div>
                            <span class="badge" style="background:rgba(255,255,255,.18);color:#fff;border-radius:20px;font-size:.72rem;padding:5px 12px;border:1px solid rgba(255,255,255,.2);">UNIVERSITAS</span>
                        </div>
                        <div>
                            <div class="text-white-50 text-uppercase" style="font-size:.72rem;letter-spacing:.8px;font-weight:600;">Total Laporan Universitas</div>
                            <div class="text-white font-weight-bold" style="font-size:3rem;line-height:1.1;">{{ $totalReports }}</div>
                            <div class="text-white-50" style="font-size:.8rem;">{{ $selectedAcademicYear === 'all' ? 'Seluruh periode' : 'T.A. ' . $selectedAcademicYear }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3" id="warekDeptChartCard">
            <div class="card-header py-3 px-4 bg-white border-bottom d-flex flex-column flex-lg-row justify-content-between align-items-lg-center" style="gap:12px;">
                <div><h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-bar mr-2" style="color:#0d9488;"></i>Grafik Laporan per Jurusan</h6><small class="text-muted">Pilih fakultas untuk melihat grafik jurusannya.</small></div>
                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                    <div style="position:relative;width:clamp(280px,34vw,390px);max-width:100%;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#0d9488;pointer-events:none;z-index:1;"><i class="fas fa-university"></i></span>
                        <select id="warekFacultySelect" aria-label="Pilih fakultas" class="form-control font-weight-bold" style="appearance:none;-webkit-appearance:none;width:100%;height:46px;padding:9px 48px 9px 42px;border-radius:12px;border:1px solid #b9e5df;background-color:#f0fdfa;color:#115e59;box-shadow:0 3px 10px rgba(13,148,136,.08);cursor:pointer;white-space:nowrap;text-overflow:ellipsis;">
                            @foreach($facultyStats as $facultyName => $stats)
                                <option value="{{ \Illuminate\Support\Str::slug($facultyName) }}">{{ $facultyName }}</option>
                            @endforeach
                        </select>
                        <span aria-hidden="true" style="position:absolute;right:0;top:1px;bottom:1px;width:42px;border-left:1px solid #cce9e5;border-radius:0 11px 11px 0;background:#e7f8f5;display:flex;align-items:center;justify-content:center;color:#0d9488;pointer-events:none;"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <button type="button" id="expandWarekDeptChart" class="btn btn-sm font-weight-bold" style="border-radius:10px;padding:9px 13px;background:#0d9488;color:white;box-shadow:0 3px 10px rgba(13,148,136,.18);"><i class="fas fa-expand-alt mr-1"></i> Perbesar grafik</button>
                </div>
            </div>
            <div class="card-body"><div style="height:260px;position:relative;cursor:zoom-in;" title="Klik grafik untuk memperbesar"><canvas id="warekDeptChart"></canvas></div><small class="text-muted d-block text-right mt-2"><i class="fas fa-search-plus mr-1"></i>Klik grafik atau tombol Perbesar grafik untuk melihat ukuran penuh.</small></div>
        </div>

        <div class="card shadow-sm mb-4" id="warekFacultyRecap">
            <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                <h6 class="m-0 font-weight-bold" style="color:#0d9488;" id="warekRecapTitle"><i class="fas fa-table mr-1"></i> Rekapitulasi Jurusan</h6>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <select id="warekFilterStatus" class="form-control form-control-sm" style="width:auto;border-radius:8px;border-color:#e2e8f0;">
                        <option value="all">Semua Status</option><option value="tertib">Tertib (0 kasus)</option><option value="perhatian">Perhatian (1-2 kasus)</option><option value="evaluasi">Perlu Evaluasi (≥3 kasus)</option>
                    </select>
                    <input type="text" id="warekSearchDept" class="form-control form-control-sm" placeholder="Cari jurusan..." style="width:160px;border-radius:8px;border-color:#e2e8f0;">
                </div>
            </div>
            <div class="card-body p-0">
                @foreach($facultyStats as $facultyName => $stats)
                    @php $facultySlug = \Illuminate\Support\Str::slug($facultyName); @endphp
                    <div class="table-responsive warek-faculty-table" data-faculty="{{ $facultySlug }}" style="display:none;max-height:520px;overflow-y:auto;">
                        <table class="table table-hover mb-0 warek-dept-table">
                            <thead class="thead-light"><tr><th class="text-center" style="width:60px;">No</th><th>Nama Jurusan / Program Studi</th><th class="text-center" style="width:140px;">Total Kasus</th><th class="text-center" style="width:180px;">Ringan / Sedang / Berat</th><th class="text-center" style="width:160px;">Status Pantauan</th><th class="text-center" style="width:100px;">Aksi</th></tr></thead>
                            <tbody>
                                @foreach($stats['deptDetails'] as $department => $detail)
                                    @php $count = $detail['total']; $status = $count === 0 ? 'tertib' : ($count < 3 ? 'perhatian' : 'evaluasi'); @endphp
                                    <tr class="warek-dept-row" data-status="{{ $status }}" data-dept="{{ strtolower($department) }}" data-faculty="{{ $facultyName }}" data-dept-name="{{ $department }}" data-total="{{ $count }}" data-ringan="{{ $detail['ringan'] }}" data-sedang="{{ $detail['sedang'] }}" data-berat="{{ $detail['berat'] }}">
                                        <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td><td class="font-weight-bold text-dark">{{ $department }}</td>
                                        <td class="text-center"><span class="badge px-3 py-1 font-weight-bold" style="background:{{ $count > 0 ? '#0d9488' : '#f1f5f9' }};color:{{ $count > 0 ? '#fff' : '#64748b' }};border-radius:8px;">{{ $count }} Kasus</span></td>
                                        <td class="text-center"><span class="badge mr-1 px-2 py-1" style="background:#ecfdf5;color:#065f46;border-radius:6px;">{{ $detail['ringan'] }}</span><span class="badge mr-1 px-2 py-1" style="background:#fffbeb;color:#92400e;border-radius:6px;">{{ $detail['sedang'] }}</span><span class="badge px-2 py-1" style="background:#fef2f2;color:#991b1b;border-radius:6px;">{{ $detail['berat'] }}</span></td>
                                        <td class="text-center"><span class="badge px-3 py-1" style="{{ $count === 0 ? 'background:#ecfdf5;color:#059669;' : ($count < 3 ? 'background:#fffbeb;color:#d97706;' : 'background:#fef2f2;color:#dc2626;') }}border-radius:8px;">{{ $count === 0 ? 'Tertib' : ($count < 3 ? 'Perhatian' : 'Perlu Evaluasi') }}</span></td>
                                        <td class="text-center"><button type="button" class="btn btn-sm font-weight-bold btn-warek-dept-detail" style="background:linear-gradient(135deg,#0f766e,#0d9488);color:#fff;border-radius:8px;font-size:.75rem;padding:4px 12px;"><i class="fas fa-chart-bar mr-1"></i>Detail</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="warek-no-result text-center text-muted py-4 d-none">Tidak ada jurusan yang sesuai filter.</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="warekDeptModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.7);backdrop-filter:blur(4px);z-index:999998;align-items:center;justify-content:center;padding:16px;">
            <div style="background:#fff;border-radius:16px;width:92vw;max-width:520px;padding:26px;position:relative;box-shadow:0 25px 50px -12px rgba(0,0,0,.4);">
                <button type="button" id="closeWarekDeptModal" aria-label="Tutup" style="position:absolute;top:14px;right:14px;border:0;border-radius:50%;width:34px;height:34px;background:#f1f5f9;color:#64748b;"><i class="fas fa-times"></i></button>
                <h5 class="font-weight-bold mb-1 pr-4" id="warekModalTitle" style="color:#0f766e;"></h5><p class="text-muted small mb-3" id="warekModalSubtitle"></p>
                <div style="height:260px;position:relative;"><canvas id="warekDetailChart"></canvas></div>
            </div>
        </div>

        <div id="warekChartModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.72);backdrop-filter:blur(4px);z-index:999997;align-items:center;justify-content:center;padding:18px;">
            <div style="background:#fff;border-radius:16px;width:min(1100px,96vw);height:min(720px,90vh);padding:22px;position:relative;box-shadow:0 25px 50px -12px rgba(0,0,0,.4);display:flex;flex-direction:column;">
                <button type="button" id="closeWarekChartModal" aria-label="Tutup grafik" style="position:absolute;top:14px;right:14px;border:0;border-radius:50%;width:36px;height:36px;background:#f1f5f9;color:#64748b;z-index:1;"><i class="fas fa-times"></i></button>
                <h5 class="font-weight-bold mb-1 pr-5" id="warekExpandedChartTitle" style="color:#0f766e;"></h5><p class="text-muted small mb-3" id="warekExpandedChartSubtitle"></p>
                <div style="position:relative;flex:1;min-height:0;overflow-y:auto;"><div id="warekExpandedChartCanvasArea" style="position:relative;width:100%;"><canvas id="warekExpandedDeptChart"></canvas></div></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const facultyData = {};
            @foreach($facultyStats as $facultyName => $stats)
                facultyData[@json(\Illuminate\Support\Str::slug($facultyName))] = {name:@json($facultyName),labels:@json(array_keys($stats['deptBreakdown'])),values:@json(array_values($stats['deptBreakdown']))};
            @endforeach
            const facultySelect = document.getElementById('warekFacultySelect');
            const recapTitle = document.getElementById('warekRecapTitle');
            const visibleTable = () => document.querySelector('.warek-faculty-table:not([style*="display: none"])');
            const applyFilters = () => {
                const panel = visibleTable(); if (!panel) return;
                const status = document.getElementById('warekFilterStatus').value;
                const query = document.getElementById('warekSearchDept').value.trim().toLowerCase();
                let visible = 0;
                panel.querySelectorAll('.warek-dept-row').forEach(row => {
                    const show = (status === 'all' || row.dataset.status === status) && row.dataset.dept.includes(query);
                    row.style.display = show ? '' : 'none'; if (show) visible++;
                });
                panel.querySelector('.warek-no-result').classList.toggle('d-none', visible > 0);
            };
            let deptChart;
            const updateFaculty = () => {
                const slug = facultySelect.value; const data = facultyData[slug]; if (!data) return;
                document.querySelectorAll('.warek-faculty-table').forEach(panel => { panel.style.display = panel.dataset.faculty === slug ? '' : 'none'; });
                recapTitle.innerHTML = '<i class="fas fa-table mr-1"></i> Rekapitulasi Jurusan di Lingkungan ' + data.name;
                if (deptChart) {
                    const topLabels = data.labels.slice(0, 5);
                    const topValues = data.values.slice(0, 5);
                    deptChart.data.labels = topLabels;
                    deptChart.data.datasets[0].data = topValues;
                    deptChart.update();
                }
                applyFilters();
            };
            const initialFaculty = facultySelect.options[0]?.value;
            const initialData = facultyData[initialFaculty];
            deptChart = new Chart(document.getElementById('warekDeptChart'), {type:'bar',data:{labels:initialData?.labels.slice(0,5) || [],datasets:[{label:'Total Kasus',data:initialData?.values.slice(0,5) || [],backgroundColor:'#0d9488',borderRadius:6,barThickness:28}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,animation:{duration:900,easing:'easeOutQuart'},scales:{x:{beginAtZero:true,ticks:{stepSize:1}},y:{ticks:{autoSkip:false,font:{size:11,weight:'600'}}}},plugins:{legend:{display:false}}}});
            facultySelect.addEventListener('change', updateFaculty);
            document.getElementById('warekFilterStatus').addEventListener('change', applyFilters);
            document.getElementById('warekSearchDept').addEventListener('input', applyFilters);
            updateFaculty();

            let expandedChart;
            const chartModal = document.getElementById('warekChartModal');
            const expandChart = () => {
                const data = facultyData[facultySelect.value]; if (!data) return;
                document.getElementById('warekExpandedChartTitle').textContent = 'Laporan per Jurusan — ' + data.name;
                document.getElementById('warekExpandedChartSubtitle').textContent = 'Total laporan pada fakultas yang dipilih';
                if (expandedChart) expandedChart.destroy();
                document.getElementById('warekExpandedChartCanvasArea').style.height = Math.max(420, data.labels.length * 52) + 'px';
                expandedChart = new Chart(document.getElementById('warekExpandedDeptChart'), {type:'bar',data:{labels:data.labels,datasets:[{label:'Total Kasus',data:data.values,backgroundColor:'#0d9488',borderRadius:7,barThickness:26}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,animation:{duration:900,easing:'easeOutQuart'},scales:{x:{beginAtZero:true,ticks:{stepSize:1}},y:{ticks:{autoSkip:false,font:{size:13,weight:'600'}}}},plugins:{legend:{display:false},tooltip:{enabled:true}}}});
                chartModal.style.display = 'flex';
            };
            document.getElementById('expandWarekDeptChart').addEventListener('click', expandChart);
            document.getElementById('warekDeptChart').addEventListener('click', expandChart);
            const closeChartModal = () => { chartModal.style.display = 'none'; };
            document.getElementById('closeWarekChartModal').addEventListener('click', closeChartModal);
            chartModal.addEventListener('click', event => { if (event.target === chartModal) closeChartModal(); });
            document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeChartModal(); closeDetail(); } });

            let detailChart;
            const detailModal = document.getElementById('warekDeptModal');
            document.querySelectorAll('.btn-warek-dept-detail').forEach(button => button.addEventListener('click', function() {
                const row = this.closest('.warek-dept-row');
                document.getElementById('warekModalTitle').textContent = row.dataset.deptName;
                document.getElementById('warekModalSubtitle').textContent = 'Rincian tingkat pelanggaran — ' + row.dataset.faculty;
                if (detailChart) detailChart.destroy();
                const detailValues = [Number(row.dataset.ringan), Number(row.dataset.sedang), Number(row.dataset.berat)];
                const detailColors = ['#10b981','#f59e0b','#ef4444'];
                const detailLabels = ['Ringan','Sedang','Berat'];
                detailChart = new Chart(document.getElementById('warekDetailChart'), {type:'doughnut',data:{labels:detailLabels,datasets:[{data:detailValues,backgroundColor:detailColors,borderColor:'#fff',borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,animation:{duration:900,easing:'easeOutQuart'},plugins:{legend:{position:'bottom'}}}});
                const detailCanvas = document.getElementById('warekDetailChart');
                detailCanvas.style.cursor = 'zoom-in';
                detailCanvas.title = 'Klik untuk memperbesar grafik';
                detailCanvas.onclick = () => openChartModal('Rincian Pelanggaran — ' + row.dataset.deptName, 'doughnut', detailLabels, [{data:detailValues,backgroundColor:detailColors,borderColor:'#fff',borderWidth:2}], {plugins:{legend:{position:'bottom'}}});
                detailModal.style.display = 'flex';
            }));
            const closeDetail = () => { detailModal.style.display = 'none'; };
            document.getElementById('closeWarekDeptModal').addEventListener('click', closeDetail);
            detailModal.addEventListener('click', event => { if (event.target === detailModal) closeDetail(); });
        });
    </script>
</x-layout>
