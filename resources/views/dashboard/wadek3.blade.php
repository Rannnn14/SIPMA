<x-layout>
    <x-slot:title>Dashboard Wadek III | SIPMA</x-slot:title>

    <div class="container-fluid px-0">
        <!-- ── Banner Alur SOP Teal (3 Langkah) ── -->
        <div class="card border-0 text-white shadow-sm mb-4 overflow-hidden sop-banner">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.15) !important;">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); font-size: 1.1rem;">
                            <i class="fas fa-route text-white"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-white mb-0" style="letter-spacing: -0.2px;">Alur Penanganan Pelanggaran Mahasiswa</h5>
                            <small class="text-white-50">Monitoring laporan mahasiswa di lingkungan {{ $myFaculty }} — hanya tampilan grafik</small>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('wadek3.dashboard') }}" class="m-0 mt-2 mt-md-0">
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            <i class="fas fa-calendar-alt text-white-50 mr-2" style="font-size: 0.82rem;"></i>
                            <select name="academic_year" aria-label="Pilih tahun akademik" class="border-0 bg-transparent text-white font-weight-bold" style="font-size: 0.85rem; outline: none; cursor: pointer;" onchange="this.form.submit()">
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
                <!-- 3 Langkah -->
                <div class="row text-white">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                            <div class="d-flex align-items-center mb-1">
                                <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">1</span>
                                <span class="font-weight-bold small text-white">Laporan Masuk</span>
                            </div>
                            <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">Mahasiswa atau Kemahasiswaan memasukkan data pelanggaran & bukti digital terlapor.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                            <div class="d-flex align-items-center mb-1">
                                <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">2</span>
                                <span class="font-weight-bold small text-white">Verifikasi Data</span>
                            </div>
                            <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">Bagian Kemahasiswaan memvalidasi identitas terlapor dan keaslian bukti yang diunggah.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                            <div class="d-flex align-items-center mb-1">
                                <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">3</span>
                                <span class="font-weight-bold small text-white">Laporan ke Kajur</span>
                            </div>
                            <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">Laporan terverifikasi otomatis masuk ke akun Ketua Jurusan terkait untuk ditindaklanjuti.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Total laporan fakultas ── -->
        <div class="row mb-4 align-items-stretch">
            <!-- Total Laporan Fakultas -->
            <div class="col-12 mb-3">
                <div class="card border-0 shadow h-100" style="border-radius: 16px; background: linear-gradient(135deg, #0f766e 0%, #0d9488 60%, #14b8a6 100%);">
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(255,255,255,0.15);">
                                <i class="fas fa-folder-open fa-xl text-white"></i>
                            </div>
                            <span class="badge" style="background: rgba(255,255,255,0.18); color: #fff; border-radius: 20px; font-size: 0.72rem; padding: 5px 12px; border: 1px solid rgba(255,255,255,0.2);">
                                {{ $myFaculty }}
                            </span>
                        </div>
                        <div>
                            <div class="text-white-50 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.8px; font-weight: 600;">Total Laporan Fakultas</div>
                            <div class="text-white font-weight-bold" style="font-size: 3rem; line-height: 1.1;">{{ $totalFacultyReports }}</div>
                            <div class="text-white-50" style="font-size: 0.8rem;">{{ $selectedAcademicYear === 'all' ? 'seluruh periode' : 'T.A. ' . $selectedAcademicYear }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Bar Chart: Kasus per Jurusan di Fakultas -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between border-bottom">
                        <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                            <i class="fas fa-chart-bar mr-1"></i> Rekap Kasus per Jurusan ({{ $myFaculty }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-light border font-weight-bold px-2 py-1"
                                id="expandWadekDeptBtn"
                                style="font-size: 0.75rem; border-radius: 8px; color: #0d9488;">
                            <i class="fas fa-expand-alt mr-1"></i> Perbesar Grafik
                        </button>
                    </div>
                    @php
                        $wadekDeptCount = count($deptStats);
                        $wadekChartH = max(240, $wadekDeptCount * 28);
                    @endphp
                    <div class="card-body">
                        <div class="chart-bar" style="height: {{ $wadekChartH }}px; position: relative;">
                            <canvas id="deptChart" onclick="document.getElementById('expandWadekDeptBtn').click()" style="cursor: pointer;" title="Klik untuk memperbesar grafik"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donut Chart: Tingkat Pelanggaran -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header py-3 bg-white border-bottom">
                        <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                            <i class="fas fa-chart-pie mr-1"></i> Tingkat Pelanggaran
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div style="height: 220px; position: relative;">
                        <canvas id="severityChart" style="cursor:zoom-in;" title="Klik untuk memperbesar grafik"></canvas>
                        </div>
                        <div class="mt-3 text-center small">
                            <span class="mr-3"><i class="fas fa-circle text-success mr-1"></i> Ringan ({{ $severityCounts['Ringan'] ?? 0 }})</span>
                            <span class="mr-3"><i class="fas fa-circle text-warning mr-1"></i> Sedang ({{ $severityCounts['Sedang'] ?? 0 }})</span>
                            <span><i class="fas fa-circle text-danger mr-1"></i> Berat ({{ $severityCounts['Berat'] ?? 0 }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Rekapitulasi Jurusan dengan Filter & Detail -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                    <i class="fas fa-table mr-1"></i> Rekapitulasi Jurusan di Lingkungan {{ $myFaculty }}
                </h6>
                <!-- Filter -->
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                    <select id="filterStatus" class="form-control form-control-sm" style="width: auto; border-radius: 8px; font-size: 0.82rem; border-color: #e2e8f0;">
                        <option value="all">Semua Status</option>
                        <option value="tertib">Tertib (0 kasus)</option>
                        <option value="perhatian">Perhatian (1-2 kasus)</option>
                        <option value="evaluasi">Perlu Evaluasi (≥3 kasus)</option>
                    </select>
                    <input type="text" id="filterJurusan" class="form-control form-control-sm" placeholder="Cari jurusan..." style="width: 160px; border-radius: 8px; font-size: 0.82rem; border-color: #e2e8f0;">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height:520px;overflow-y:auto;">
                    <table class="table table-hover mb-0" id="deptTable">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">No</th>
                                <th>Nama Jurusan / Program Studi</th>
                                <th style="width: 140px;" class="text-center">Total Kasus</th>
                                <th style="width: 180px;" class="text-center">Ringan / Sedang / Berat</th>
                                <th style="width: 160px;" class="text-center">Status Pantauan</th>
                                <th style="width: 100px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @forelse($deptStats as $dept => $cnt)
                            @php
                                $bd = $deptBreakdown[$dept] ?? ['ringan'=>0,'sedang'=>0,'berat'=>0];
                                if ($cnt == 0) $statusVal = 'tertib';
                                elseif ($cnt < 3) $statusVal = 'perhatian';
                                else $statusVal = 'evaluasi';
                            @endphp
                            <tr class="dept-row"
                                data-status="{{ $statusVal }}"
                                data-dept="{{ strtolower($dept) }}"
                                data-dept-name="{{ $dept }}"
                                data-total="{{ $cnt }}"
                                data-ringan="{{ $bd['ringan'] }}"
                                data-sedang="{{ $bd['sedang'] }}"
                                data-berat="{{ $bd['berat'] }}">
                                <td class="text-center font-weight-bold text-muted">{{ $i++ }}</td>
                                <td class="font-weight-bold text-dark">{{ $dept }}</td>
                                <td class="text-center">
                                    <span class="badge px-3 py-1 font-weight-bold" style="background-color: {{ $cnt > 0 ? '#0d9488' : '#f1f5f9' }} !important; color: {{ $cnt > 0 ? '#fff' : '#64748b' }}; border-radius: 8px;">
                                        {{ $cnt }} Kasus
                                    </span>
                                </td>
                                <td class="text-center" style="font-size: 0.82rem;">
                                    <span class="badge mr-1 px-2 py-1" style="background: #ecfdf5; color: #065f46; border-radius: 6px;">{{ $bd['ringan'] }}</span>
                                    <span class="badge mr-1 px-2 py-1" style="background: #fffbeb; color: #92400e; border-radius: 6px;">{{ $bd['sedang'] }}</span>
                                    <span class="badge px-2 py-1" style="background: #fef2f2; color: #991b1b; border-radius: 6px;">{{ $bd['berat'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-3 py-1" style="{{ $cnt == 0 ? 'background:#ecfdf5; color:#059669;' : ($cnt < 3 ? 'background:#fffbeb; color:#d97706;' : 'background:#fef2f2; color:#dc2626;') }} border-radius: 8px;">
                                        {{ $cnt == 0 ? 'Tertib' : ($cnt < 3 ? 'Perhatian' : 'Perlu Evaluasi') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm font-weight-bold btn-detail-dept" style="background: linear-gradient(135deg, #0f766e, #0d9488); color: #fff; border-radius: 8px; font-size: 0.75rem; padding: 4px 12px;">
                                        <i class="fas fa-chart-bar mr-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada jurusan tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- No results message -->
                <div id="noFilterResult" class="text-center text-muted py-4 d-none">
                    <i class="fas fa-search fa-2x mb-2 d-block text-light"></i>
                    Tidak ada jurusan yang sesuai filter.
                </div>
            </div>
        </div>
    </div>

    <!-- ── Modal Detail Jurusan ── -->
    <div id="deptDetailModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background: rgba(15,23,42,0.7); backdrop-filter: blur(4px); z-index: 999998; align-items: center; justify-content: center;">
        <div style="background: #fff; border-radius: 20px; width: 92vw; max-width: 520px; padding: 28px; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);">
            <button id="closeDeptModal" style="position:absolute; top:16px; right:16px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:50%; width:36px; height:36px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1rem; color:#64748b;">
                <i class="fas fa-times"></i>
            </button>
            <h5 class="font-weight-bold mb-1 pr-4" id="deptModalTitle" style="color: #0f766e;"></h5>
            <p class="text-muted small mb-4" id="deptModalSubtitle"></p>

            <!-- Mini stat badges -->
            <div class="d-flex justify-content-around mb-4">
                <div class="text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:52px; height:52px; background:#ecfdf5;">
                        <span class="font-weight-bold" style="color:#059669; font-size:1.3rem;" id="modalRingan">0</span>
                    </div>
                    <div class="text-xs font-weight-bold text-muted">Ringan</div>
                </div>
                <div class="text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:52px; height:52px; background:#fffbeb;">
                        <span class="font-weight-bold" style="color:#d97706; font-size:1.3rem;" id="modalSedang">0</span>
                    </div>
                    <div class="text-xs font-weight-bold text-muted">Sedang</div>
                </div>
                <div class="text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:52px; height:52px; background:#fef2f2;">
                        <span class="font-weight-bold" style="color:#dc2626; font-size:1.3rem;" id="modalBerat">0</span>
                    </div>
                    <div class="text-xs font-weight-bold text-muted">Berat</div>
                </div>
            </div>

            <!-- Doughnut chart breakdown -->
            <div style="position: relative; height: 220px;">
                <canvas id="deptDetailChart"></canvas>
            </div>
            <div class="text-center small mt-3">
                <span class="mr-3"><i class="fas fa-circle text-success mr-1"></i> Ringan</span>
                <span class="mr-3"><i class="fas fa-circle text-warning mr-1"></i> Sedang</span>
                <span><i class="fas fa-circle text-danger mr-1"></i> Berat</span>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const deptLabels = @json(array_slice(array_keys($deptStats), 0, 5));
        const deptData   = @json(array_slice(array_values($deptStats), 0, 5));
        const allDeptLabels = @json(array_keys($deptStats));
        const allDeptData = @json(array_values($deptStats));

        const wadekDeptChart = new Chart(document.getElementById('deptChart'), {
            type: 'bar',
            data: {
                labels: deptLabels,
                datasets: [{
                    label: 'Jumlah Kasus',
                    data: deptData,
                    backgroundColor: '#0d9488',
                    borderRadius: 6,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    },
                    y: {
                        ticks: {
                            autoSkip: false,
                            font: { size: 11, weight: '600' },
                            color: '#334155'
                        },
                        grid: { display: false }
                    }
                }
            }
        });

        // Expand button for Dept Chart
        function openWadekDeptModal() {
            openChartModal(
                'Rekap Kasus per Jurusan — {{ $myFaculty }}',
                'bar',
                allDeptLabels,
                [{ label: 'Jumlah Kasus', data: allDeptData, backgroundColor: '#0d9488', borderRadius: 6 }],
                { indexAxis: 'y', animation: { duration: 900, easing: 'easeOutQuart' }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } }, y: { ticks: { autoSkip: false } } }, plugins: { legend: { display: false } } }
            );
        }
        const expandBtn = document.getElementById('expandWadekDeptBtn');
        if (expandBtn) expandBtn.addEventListener('click', openWadekDeptModal);
        const deptCanvas = document.getElementById('deptChart');
        if (deptCanvas) {
            deptCanvas.style.cursor = 'pointer';
            deptCanvas.addEventListener('click', openWadekDeptModal);
        }

        const severityLabels = ['Ringan', 'Sedang', 'Berat'];
        const severityValues = [
            {{ $severityCounts['Ringan'] ?? 0 }},
            {{ $severityCounts['Sedang'] ?? 0 }},
            {{ $severityCounts['Berat'] ?? 0 }}
        ];
        const severityColors = ['#10b981', '#f59e0b', '#ef4444'];
        new Chart(document.getElementById('severityChart'), {
            type: 'doughnut',
            data: {
                labels: severityLabels,
                datasets: [{
                    data: severityValues,
                    backgroundColor: severityColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeOutQuart' },
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
        const severityCanvas = document.getElementById('severityChart');
        severityCanvas.addEventListener('click', function () {
            openChartModal('Komposisi Tingkat Pelanggaran', 'doughnut', severityLabels, [{data:severityValues,backgroundColor:severityColors,borderColor:'#fff',borderWidth:2}], {plugins:{legend:{position:'bottom'}},cutout:'70%'});
        });

        // ── Filter tabel jurusan ──
        const filterStatus  = document.getElementById('filterStatus');
        const filterJurusan = document.getElementById('filterJurusan');
        const noResult      = document.getElementById('noFilterResult');

        function applyFilter() {
            const status = filterStatus.value;
            const search = filterJurusan.value.toLowerCase().trim();
            let visible  = 0;
            document.querySelectorAll('.dept-row').forEach(function(row) {
                const matchStatus = (status === 'all') || (row.dataset.status === status);
                const matchSearch = (search === '') || row.dataset.dept.includes(search);
                if (matchStatus && matchSearch) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });
            noResult.classList.toggle('d-none', visible > 0);
        }

        filterStatus.addEventListener('change', applyFilter);
        filterJurusan.addEventListener('input', applyFilter);

        // ── Modal Detail Jurusan ──
        const detailModal = document.getElementById('deptDetailModal');
        let   detailChart = null;

        document.querySelectorAll('.btn-detail-dept').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const name   = row.dataset.deptName;
                const total  = parseInt(row.dataset.total);
                const ringan = parseInt(row.dataset.ringan);
                const sedang = parseInt(row.dataset.sedang);
                const berat  = parseInt(row.dataset.berat);

                document.getElementById('deptModalTitle').textContent   = name;
                document.getElementById('deptModalSubtitle').textContent = 'Breakdown ' + total + ' kasus di jurusan ini';
                document.getElementById('modalRingan').textContent = ringan;
                document.getElementById('modalSedang').textContent = sedang;
                document.getElementById('modalBerat').textContent  = berat;

                detailModal.style.display = 'flex';

                // Destroy old chart
                if (detailChart) { try { detailChart.destroy(); } catch(e){} detailChart = null; }

                // Re-create canvas (avoid chart reuse bug)
                const wrapper = document.getElementById('deptDetailChart').parentNode;
                wrapper.innerHTML = '<canvas id="deptDetailChart"></canvas>';

                const hasData = (ringan + sedang + berat) > 0;
                detailChart = new Chart(document.getElementById('deptDetailChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Ringan', 'Sedang', 'Berat'],
                        datasets: [{
                            data: hasData ? [ringan, sedang, berat] : [1, 0, 0],
                            backgroundColor: hasData ? ['#10b981', '#f59e0b', '#ef4444'] : ['#e2e8f0', '#e2e8f0', '#e2e8f0'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        if (!hasData) return 'Belum ada kasus';
                                        return ctx.label + ': ' + ctx.raw + ' kasus';
                                    }
                                }
                            }
                        }
                    }
                });
                const detailCanvas = document.getElementById('deptDetailChart');
                detailCanvas.style.cursor = 'zoom-in';
                detailCanvas.title = 'Klik untuk memperbesar grafik';
                detailCanvas.addEventListener('click', function() {
                    openChartModal('Rincian Pelanggaran — ' + name, 'doughnut', ['Ringan', 'Sedang', 'Berat'], [{data:[ringan,sedang,berat],backgroundColor:['#10b981','#f59e0b','#ef4444'],borderColor:'#fff',borderWidth:2}], {plugins:{legend:{position:'bottom'}},cutout:'65%'});
                });
            });
        });

        document.getElementById('closeDeptModal').addEventListener('click', function() {
            detailModal.style.display = 'none';
        });
        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) detailModal.style.display = 'none';
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') detailModal.style.display = 'none';
        });
    });
    </script>

</x-layout>
