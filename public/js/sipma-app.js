/**
 * SIPMA - Sistem Pendataan Pelanggaran Mahasiswa
 * Core JavaScript Engine & LocalStorage Manager
 */

(function () {
  'use strict';

  // --- Safe Storage Helpers ---
  function safeGetStorage(key, fallback) {
    try {
      const val = localStorage.getItem(key);
      return val !== null ? val : fallback;
    } catch (e) {
      return fallback;
    }
  }

  function safeSetStorage(key, value) {
    try {
      localStorage.setItem(key, value);
    } catch (e) {}
  }

  function getActiveRole() {
    try {
      const urlParams = new URLSearchParams(window.location.search);
      const urlRole = urlParams.get('role');
      if (urlRole && ['mahasiswa', 'kaprodi', 'admin'].includes(urlRole)) {
        safeSetStorage('sipma_active_role', urlRole);
        return urlRole;
      }
    } catch (e) {}

    return safeGetStorage('sipma_active_role', 'mahasiswa');
  }

  function setActiveRole(role) {
    safeSetStorage('sipma_active_role', role);
  }

  // --- Seed Data Initializers ---
  const INITIAL_STUDENTS = [
    { nim: '2388010016', name: 'Faqih Firansyah', program: 'Teknik Informatika', angkatan: '2023', ipk: '3.65', status: 'Aktif' },
    { nim: '2388010017', name: 'Ahmad Rizky', program: 'Sistem Informasi', angkatan: '2023', ipk: '3.40', status: 'Aktif' },
    { nim: '2388010018', name: 'Siti Aulia', program: 'Teknik Elektro', angkatan: '2024', ipk: '3.80', status: 'Aktif' },
    { nim: '2388010019', name: 'Dewa Pratama', program: 'Manajemen', angkatan: '2022', ipk: '2.95', status: 'Aktif' },
    { nim: '2388010020', name: 'Rina Kartika', program: 'Akuntansi', angkatan: '2023', ipk: '3.55', status: 'Aktif' },
    { nim: '2388010021', name: 'Budi Santoso', program: 'Teknik Sipil', angkatan: '2024', ipk: '3.10', status: 'Aktif' }
  ];

  const DEFAULT_POINTS = {
    ringan: 10,
    sedang: 25,
    berat: 50
  };

  const THRESHOLD_POINTS = 100;

  function determineSeverityAndPoints(type, subtype) {
    const sub = (subtype || '').toLowerCase();
    const typ = (type || '').toLowerCase();

    // Heavy violations (Berat -> 50 Poin)
    if (
      sub.includes('menyontek') || 
      sub.includes('plagiarisme') || 
      sub.includes('joki') || 
      sub.includes('vandalism') || 
      sub.includes('perusakan') || 
      sub.includes('bullying') || 
      sub.includes('perundungan') || 
      sub.includes('pelecehan')
    ) {
      return { severity: 'berat', points: 50 };
    }

    // Medium violations (Sedang -> 25 Poin)
    if (
      sub.includes('tanda tangan') || 
      sub.includes('ucapan') || 
      sub.includes('bahasa') || 
      sub.includes('kegaduhan') || 
      typ.includes('etika')
    ) {
      return { severity: 'sedang', points: 25 };
    }

    // Light violations (Ringan -> 10 Poin)
    if (
      sub.includes('pakaian') || 
      sub.includes('merokok') || 
      sub.includes('parkir') || 
      typ.includes('tertib')
    ) {
      return { severity: 'ringan', points: 10 };
    }

    // Default fallback based on type
    if (typ.includes('akademik')) return { severity: 'berat', points: 50 };
    if (typ.includes('etika')) return { severity: 'sedang', points: 25 };
    return { severity: 'ringan', points: 10 };
  }

  function triggerAutoSystemDetermination() {
    const role = getActiveRole();
    if (role === 'mahasiswa') {
      const inputType = document.getElementById('inputType');
      const inputSubtype = document.getElementById('inputSubtype');
      const inputSeverity = document.getElementById('inputSeverity');
      const inputPoints = document.getElementById('inputPoints');

      const typeVal = inputType ? inputType.value : '';
      const subVal = inputSubtype ? inputSubtype.value : '';
      const result = determineSeverityAndPoints(typeVal, subVal);
      if (inputSeverity) inputSeverity.value = result.severity;
      if (inputPoints) inputPoints.value = result.points;
    }
  }

  const INITIAL_REPORTS = [
    {
      id: 'LPM-20260926-001',
      nim: '2388010016',
      name: 'Faqih Firansyah',
      program: 'Teknik Informatika / 2023',
      reporterRole: 'Mahasiswa (Pelapor)',
      reporterName: 'Ahmad Fauzi',
      type: 'akademik',
      typeName: 'Kecurangan Akademik',
      subtype: 'Plagiarisme Tugas Akhir',
      severity: 'berat',
      points: 50,
      date: '2026-09-26',
      time: '09:15',
      location: 'Laboratorium Komputer 3',
      chronology: 'Kedapatan menyalin seluruh kode program tugas praktikum milik mahasiswa angkatan atas tanpa modifikasi.',
      evidence: [],
      status: 'diverifikasi_kaprodi',
      rejectionReason: '',
      sanksi: '',
      sanksiPoints: 50,
      logs: [
        { time: '2026-09-26 09:30', actor: 'Mahasiswa (Pelapor)', action: 'Laporan dibuat & dikirim ke Kaprodi' },
        { time: '2026-09-26 10:00', actor: 'Kaprodi Informatika', action: 'Laporan diverifikasi & bobot ditetapkan (50 Poin)' }
      ]
    },
    {
      id: 'LPM-20260925-002',
      nim: '2388010016',
      name: 'Faqih Firansyah',
      program: 'Teknik Informatika / 2023',
      reporterRole: 'Kaprodi Informatika',
      reporterName: 'Dr. Hendra Wijaya, M.Kom',
      type: 'akademik',
      typeName: 'Kecurangan Akademik',
      subtype: 'Menyontek saat UTS',
      severity: 'berat',
      points: 50,
      date: '2026-09-25',
      time: '11:00',
      location: 'Ruang Ujian 204',
      chronology: 'Terbukti membawa catatan ringkasan dan menggunakan ponsel saat ujian UTS berlangsung.',
      evidence: [],
      status: 'diverifikasi_kaprodi',
      rejectionReason: '',
      sanksi: '',
      sanksiPoints: 50,
      logs: [
        { time: '2026-09-25 11:30', actor: 'Kaprodi Informatika', action: 'Laporan direct dibuat & diverifikasi (50 Poin)' }
      ]
    },
    {
      id: 'LPM-20260920-003',
      nim: '2388010017',
      name: 'Ahmad Rizky',
      program: 'Sistem Informasi / 2023',
      reporterRole: 'Mahasiswa (Pelapor)',
      reporterName: 'Siti Rahma',
      type: 'etika',
      typeName: 'Pelanggaran Etika & Perilaku',
      subtype: 'Ucapan Tidak Pantas / Sharp Language',
      severity: 'sedang',
      points: 25,
      date: '2026-09-20',
      time: '10:30',
      location: 'Grup WhatsApp Perkuliahan',
      chronology: 'Mengirimkan ucapan tidak pantas dan kasar kepada pengurus kelas dan staf ruang dosen.',
      evidence: [
        { name: 'bukti_screenshot_chat.jpg', type: 'image', url: 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=500&auto=format&fit=crop&q=60' }
      ],
      status: 'dilaporkan',
      rejectionReason: '',
      sanksi: '',
      sanksiPoints: 25,
      logs: [
        { time: '2026-09-20 10:45', actor: 'Mahasiswa (Pelapor)', action: 'Laporan dibuat & dikirimkan ke Kaprodi' }
      ]
    },
    {
      id: 'LPM-20260918-004',
      nim: '2388010019',
      name: 'Dewa Pratama',
      program: 'Manajemen / 2022',
      reporterRole: 'Kaprodi Manajemen',
      reporterName: 'Siti Rahmawati, S.E., M.M.',
      type: 'tertib',
      typeName: 'Pelanggaran Tata Tertib',
      subtype: 'Merokok di Area Terlarang',
      severity: 'ringan',
      points: 10,
      date: '2026-09-18',
      time: '14:15',
      location: 'Koridor Lantai 3 Gedung Rektorat',
      chronology: 'Kedapatan merokok di area bebas asap rokok koridor lantai 3 setelah diperingatkan oleh petugas keamanan.',
      evidence: [],
      status: 'diteruskan_kemahasiswaan',
      rejectionReason: '',
      sanksi: '',
      sanksiPoints: 10,
      logs: [
        { time: '2026-09-18 14:30', actor: 'Kaprodi Manajemen', action: 'Laporan dibuat & diverifikasi' },
        { time: '2026-09-19 09:00', actor: 'Kaprodi Manajemen', action: 'Kasus diteruskan ke Bagian Kemahasiswaan untuk penanganan sanksi resmi' }
      ]
    },
    {
      id: 'LPM-20260915-005',
      nim: '2388010021',
      name: 'Budi Santoso',
      program: 'Teknik Sipil / 2024',
      reporterRole: 'Kaprodi Teknik Sipil',
      reporterName: 'Ir. Bambang Sugiarto, M.T.',
      type: 'tertib',
      typeName: 'Pelanggaran Tata Tertib',
      subtype: 'Vandalism / Perusakan Fasilitas',
      severity: 'berat',
      points: 50,
      date: '2026-09-15',
      time: '16:00',
      location: 'Ruang Laboratorium Struktur',
      chronology: 'Mencoret-coret meja praktikum dan merusak fasilitas pengukur beban tanpa izin.',
      evidence: [],
      status: 'selesai',
      rejectionReason: '',
      sanksi: 'Teguran Tertulis II & Kewajiban Penggantian Fasilitas',
      sanksiPoints: 50,
      logs: [
        { time: '2026-09-15 16:30', actor: 'Kaprodi Teknik Sipil', action: 'Laporan dibuat & diverifikasi (50 Poin)' },
        { time: '2026-09-16 08:30', actor: 'Kaprodi Teknik Sipil', action: 'Kasus diteruskan ke Kemahasiswaan' },
        { time: '2026-09-17 11:00', actor: 'Admin Kemahasiswaan', action: 'Sanksi Ditetapkan: Teguran Tertulis II & Penggantian Biaya' },
        { time: '2026-09-18 10:00', actor: 'Admin Kemahasiswaan', action: 'Sanksi telah dilaksanakan. Kasus resmi ditutup (Selesai).' }
      ]
    }
  ];

  const SUBTYPE_VARIANTS = {
    akademik: [
      'Menyontek saat UTS/UAS',
      'Plagiarisme Tugas / Tugas Akhir',
      'Perjokian Tugas / Ujian',
      'Pemalsuan Tanda Tangan Dosen'
    ],
    tertib: [
      'Pakaian Tidak Sesuai Aturan Kampus',
      'Merokok di Area Bebas Asap Rokok',
      'Vandalism / Perusakan Fasilitas Kampus',
      'Parkir Sembarangan / Mengganggu Akses'
    ],
    etika: [
      'Ucapan Tidak Pantas / Sharp Language',
      'Perilaku Perundungan (Bullying) / Intimidasi',
      'Kegaduhan Mengganggu Perkuliahan',
      'Pelecehan / Perilaku Tidak Etis'
    ]
  };

  // State Management
  let currentPageId = 'buat-laporan';
  let currentActiveModalReportId = null;
  let currentUploadedFiles = [];

  function getStoredStudents() {
    const data = safeGetStorage('sipma_students', null);
    if (!data) {
      safeSetStorage('sipma_students', JSON.stringify(INITIAL_STUDENTS));
      return INITIAL_STUDENTS;
    }
    try {
      return JSON.parse(data);
    } catch (e) {
      return INITIAL_STUDENTS;
    }
  }

  function getStoredReports() {
    const data = safeGetStorage('sipma_reports', null);
    if (data === null) {
      safeSetStorage('sipma_reports', JSON.stringify([]));
      return [];
    }
    try {
      return JSON.parse(data);
    } catch (e) {
      return [];
    }
  }

  function saveReports(reports) {
    safeSetStorage('sipma_reports', JSON.stringify(reports));
  }

  // --- Helper Poin & Status ---
  function calculateStudentTotalPoints(nim) {
    const reports = getStoredReports().filter(r => r.nim === nim && r.status !== 'ditolak');
    return reports.reduce((acc, curr) => acc + (parseInt(curr.points, 10) || 0), 0);
  }

  window.handleBrandClick = function () {
    const role = getActiveRole();
    if (role === 'mahasiswa') {
      showPage('dashboard-mahasiswa');
    } else {
      showPage('dashboard');
    }
  };

  // --- Global Role Switcher Function ---
  window.switchRole = function (role) {
    if (!role) return;
    setActiveRole(role);
    updateRoleUI();

    if (role === 'mahasiswa') {
      showPage('dashboard-mahasiswa');
      sipmaToast('Mode Mahasiswa Aktif: Dashboard Saya, Buat Laporan & Riwayat Laporan Saya.', 'success');
    } else if (role === 'kaprodi') {
      showPage('dashboard');
      sipmaToast('Mode Kaprodi Aktif: Akses Verifikasi Laporan, Edit Poin & Eskalasi.', 'info');
    } else if (role === 'admin') {
      showPage('dashboard');
      sipmaToast('Mode Admin Kemahasiswaan Aktif: Akses Penanganan Kasus Escalated.', 'info');
    }
  };

  // --- Page Router Engine ---
  window.showPage = function (pageId, optionalFilter = null) {
    const role = getActiveRole();

    // STRICT ACCESSIBILITY RESTRICTIONS BY ROLE
    if (role === 'mahasiswa') {
      // Mahasiswa can ONLY access: dashboard-mahasiswa, buat-laporan, riwayat-saya
      if (!['dashboard-mahasiswa', 'buat-laporan', 'riwayat-saya'].includes(pageId)) {
        pageId = 'dashboard-mahasiswa';
      }
    } else if (role === 'kaprodi') {
      if (['verifikasi-admin', 'dashboard-mahasiswa', 'riwayat-saya'].includes(pageId)) {
        pageId = 'dashboard';
      }
    } else if (role === 'admin') {
      if (['verifikasi-kaprodi', 'dashboard-mahasiswa', 'riwayat-saya'].includes(pageId)) {
        pageId = 'dashboard';
      }
    }

    currentPageId = pageId;

    // Hide all pages (remove active class)
    document.querySelectorAll('.app-page').forEach(page => {
      page.classList.remove('active');
    });

    // Show target page
    const targetPage = document.getElementById(`page-${pageId}`);
    if (targetPage) {
      targetPage.classList.add('active');
    } else {
      const fallbackId = role === 'mahasiswa' ? 'dashboard-mahasiswa' : 'dashboard';
      const fallbackPage = document.getElementById(`page-${fallbackId}`);
      if (fallbackPage) fallbackPage.classList.add('active');
      currentPageId = fallbackId;
    }

    // Highlight active sidebar item
    document.querySelectorAll('#accordionSidebar .nav-item').forEach(item => {
      item.classList.remove('active');
      const link = item.querySelector('.nav-link');
      if (link && link.getAttribute('onclick') && link.getAttribute('onclick').includes(currentPageId)) {
        item.classList.add('active');
      }
    });

    // Render page specific data
    if (currentPageId === 'dashboard-mahasiswa') {
      renderMahasiswaDashboard();
    } else if (currentPageId === 'riwayat-saya') {
      renderMahasiswaRiwayatTable();
    } else if (currentPageId === 'dashboard') {
      renderDashboardStats();
      renderDashboardAnalytics();
      populateQuickStudentLookupOptions();
    } else if (currentPageId === 'daftar-laporan') {
      const filterSelect = document.getElementById('filterStatusSelect');
      if (filterSelect && optionalFilter) {
        filterSelect.value = optionalFilter;
      }
      renderReportsTable();
    } else if (currentPageId === 'verifikasi-kaprodi') {
      renderKaprodiVerificationQueue();
    } else if (currentPageId === 'verifikasi-admin') {
      renderAdminVerificationQueue();
    } else if (currentPageId === 'riwayat') {
      renderStudentHistoryOptions();
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // --- Toast Notification Engine ---
  window.sipmaToast = function (message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toastContainer';
      document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `sipma-toast toast-${type}`;
    let iconClass = 'fa-check-circle text-success';
    if (type === 'warning') iconClass = 'fa-exclamation-triangle text-warning';
    if (type === 'danger') iconClass = 'fa-times-circle text-danger';
    if (type === 'info') iconClass = 'fa-info-circle text-info';

    toast.innerHTML = `
      <i class="fas ${iconClass} fa-lg"></i>
      <div style="flex: 1;">
        <div style="font-weight:700; font-size: 0.88rem;">SIPMA System</div>
        <div style="font-size: 0.83rem; color: #475569;">${message}</div>
      </div>
      <button onclick="this.parentElement.remove()" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(toast);
    setTimeout(() => {
      if (toast.parentElement) toast.remove();
    }, 4500);
  };

  // --- Mahasiswa Specific Rendering ---
  function getMahasiswaReports() {
    const allReports = getStoredReports();
    return allReports.filter(r => {
      if (r.reporterRole && r.reporterRole.toLowerCase().includes('mahasiswa')) return true;
      if (r.reporterName && r.reporterName.toLowerCase().includes('ahmad fauzi')) return true;
      return false;
    });
  }

  function renderMahasiswaDashboard() {
    const mhsReports = getMahasiswaReports();
    const total = mhsReports.length;
    const pending = mhsReports.filter(r => r.status === 'dilaporkan').length;
    const approved = mhsReports.filter(r => r.status !== 'dilaporkan' && r.status !== 'ditolak').length;
    const rejected = mhsReports.filter(r => r.status === 'ditolak').length;

    const elTotal = document.getElementById('statMhsTotal');
    const elPending = document.getElementById('statMhsPending');
    const elApproved = document.getElementById('statMhsApproved');
    const elRejected = document.getElementById('statMhsRejected');

    if (elTotal) elTotal.textContent = total;
    if (elPending) elPending.textContent = pending;
    if (elApproved) elApproved.textContent = approved;
    if (elRejected) elRejected.textContent = rejected;

    // Render Recent 5 Reports Table for Mahasiswa
    const tbody = document.getElementById('mhsRecentReportsTableBody');
    if (!tbody) return;

    if (mhsReports.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center py-4 text-muted">
            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
            Anda belum pernah membuat laporan pelanggaran. Klik <strong>Buat Laporan Baru</strong> untuk melapor.
          </td>
        </tr>
      `;
      return;
    }

    const recent = mhsReports.slice(0, 5);
    tbody.innerHTML = recent.map(r => {
      let statusBadge = '';
      if (r.status === 'dilaporkan') {
        statusBadge = '<span class="badge badge-warning py-1 px-2 font-weight-bold"><i class="fas fa-clock mr-1"></i> Menunggu Verifikasi Kaprodi</span>';
      } else if (r.status === 'ditolak') {
        statusBadge = '<span class="badge badge-danger py-1 px-2 font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Ditolak Kaprodi</span>';
      } else {
        statusBadge = '<span class="badge badge-success py-1 px-2 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Disetujui (ACC Kaprodi)</span>';
      }

      return `
        <tr>
          <td class="font-weight-bold text-primary">${r.id}</td>
          <td>
            <div class="font-weight-bold text-dark">${r.name}</div>
            <div class="small text-muted">NIM: ${r.nim}</div>
          </td>
          <td>
            <div class="font-weight-bold text-dark">${r.typeName || r.type}</div>
            <div class="small text-muted">${r.subtype || '-'}</div>
          </td>
          <td>${r.date}</td>
          <td>${statusBadge}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold" onclick="viewReportDetail('${r.id}')">
              <i class="fas fa-eye mr-1"></i> Detail
            </button>
          </td>
        </tr>
      `;
    }).join('');
  }

  window.renderMahasiswaRiwayatTable = function () {
    const mhsReports = getMahasiswaReports();
    const tbody = document.getElementById('mhsRiwayatTableBody');
    if (!tbody) return;

    const filterStatus = document.getElementById('mhsFilterStatusSelect')?.value || 'all';
    const filterSearch = (document.getElementById('mhsFilterSearchInput')?.value || '').toLowerCase();

    let filtered = mhsReports.filter(r => {
      // Filter Status
      if (filterStatus === 'dilaporkan' && r.status !== 'dilaporkan') return false;
      if (filterStatus === 'acc' && (r.status === 'dilaporkan' || r.status === 'ditolak')) return false;
      if (filterStatus === 'ditolak' && r.status !== 'ditolak') return false;

      // Search Filter
      if (filterSearch) {
        const matchId = r.id.toLowerCase().includes(filterSearch);
        const matchName = r.name.toLowerCase().includes(filterSearch);
        const matchNim = r.nim.toLowerCase().includes(filterSearch);
        const matchType = (r.typeName || r.type || '').toLowerCase().includes(filterSearch);
        return matchId || matchName || matchNim || matchType;
      }
      return true;
    });

    if (filtered.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center py-4 text-muted">
            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
            Tidak ada data laporan yang cocok dengan kriteria filter.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = filtered.map(r => {
      let statusBadge = '';
      let notes = '-';

      if (r.status === 'dilaporkan') {
        statusBadge = '<span class="badge badge-warning py-1 px-2 font-weight-bold"><i class="fas fa-clock mr-1"></i> Menunggu Verifikasi Kaprodi</span>';
        notes = '<span class="small text-muted">Laporan sedang dalam antrean periksa Kaprodi.</span>';
      } else if (r.status === 'ditolak') {
        statusBadge = '<span class="badge badge-danger py-1 px-2 font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Ditolak Kaprodi</span>';
        notes = `<span class="small text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Alasan: ${r.rejectionReason || 'Bukti kurang lengkap'}</span>`;
      } else if (r.status === 'diverifikasi_kaprodi') {
        statusBadge = '<span class="badge badge-success py-1 px-2 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Disetujui (ACC Kaprodi)</span>';
        notes = `<span class="small text-success font-weight-bold"><i class="fas fa-award mr-1"></i> Disetujui (${r.points} Poin)</span>`;
      } else if (r.status === 'diteruskan_kemahasiswaan') {
        statusBadge = '<span class="badge badge-purple text-white py-1 px-2 font-weight-bold" style="background:#7c3aed;"><i class="fas fa-arrow-right mr-1"></i> Diteruskan ke Kemahasiswaan</span>';
        notes = '<span class="small text-purple" style="color:#7c3aed;"><i class="fas fa-gavel mr-1"></i> Diteruskan untuk penanganan sanksi</span>';
      } else if (r.status === 'sedang_diproses') {
        statusBadge = '<span class="badge badge-info py-1 px-2 font-weight-bold"><i class="fas fa-spinner fa-spin mr-1"></i> Diproses Kemahasiswaan</span>';
        notes = '<span class="small text-info"><i class="fas fa-user-clock mr-1"></i> Proses pembinaan mahasiswa</span>';
      } else if (r.status === 'sanksi_ditetapkan' || r.status === 'selesai') {
        statusBadge = '<span class="badge badge-dark py-1 px-2 font-weight-bold"><i class="fas fa-check-double mr-1"></i> Sanksi Ditetapkan / Selesai</span>';
        notes = `<span class="small text-dark font-weight-bold">${r.sanksi || 'Sanksi resmi telah diproses'}</span>`;
      }

      return `
        <tr>
          <td class="font-weight-bold text-primary">${r.id}</td>
          <td>
            <div class="font-weight-bold text-dark">${r.name}</div>
            <div class="small text-muted">NIM: ${r.nim} (${r.program || '-'})</div>
          </td>
          <td>
            <div class="font-weight-bold text-dark">${r.typeName || r.type}</div>
            <div class="small text-muted">${r.subtype || '-'}</div>
          </td>
          <td>
            <div>${r.date}</div>
            <div class="text-xs text-muted">${r.time || ''}</div>
          </td>
          <td>${statusBadge}</td>
          <td style="max-width:240px;">${notes}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold px-3" onclick="viewReportDetail('${r.id}')">
              <i class="fas fa-eye mr-1"></i> Detail
            </button>
          </td>
        </tr>
      `;
    }).join('');
  };

  // --- Core Application State & UI Sync ---
  function updateRoleUI() {
    const role = getActiveRole();
    const btnMahasiswa = document.getElementById('btnRoleMahasiswa');
    const btnKaprodi = document.getElementById('btnRoleKaprodi');
    const btnAdmin = document.getElementById('btnRoleAdmin');
    const roleBadge = document.getElementById('activeRoleBadge');
    const userNameDisplay = document.getElementById('userNameDisplay');

    const formRoleBadge = document.getElementById('formRoleBadge');
    const formSubTitleText = document.getElementById('formSubTitleText');
    const inputPointsHelpText = document.getElementById('inputPointsHelpText');
    const formSubmitBtnText = document.getElementById('formSubmitBtnText');
    const inputPoints = document.getElementById('inputPoints');
    const inputSeverity = document.getElementById('inputSeverity');
    const inputPointsContainer = document.getElementById('inputPointsContainer');

    if (btnMahasiswa && btnKaprodi && btnAdmin) {
      btnMahasiswa.className = 'role-btn';
      btnKaprodi.className = 'role-btn';
      btnAdmin.className = 'role-btn';

      // Reset visibility on element classes (except app-page wrapper)
      document.querySelectorAll('.mahasiswa-only, .kaprodi-only, .admin-only, .pelapor-only, .management-only').forEach(el => {
        if (!el.classList.contains('app-page')) {
          el.classList.remove('d-none');
        }
      });

      if (role === 'mahasiswa') {
        btnMahasiswa.classList.add('active-mahasiswa');
        if (roleBadge) {
          roleBadge.textContent = 'Mode Mahasiswa: Pelapor & Riwayat Saya';
          roleBadge.className = 'role-badge-indicator role-badge-mahasiswa';
        }
        if (userNameDisplay) userNameDisplay.textContent = 'Mahasiswa (Ahmad Fauzi)';

        if (inputSeverity) {
          inputSeverity.disabled = false;
          inputSeverity.classList.remove('bg-light');
        }
        if (inputPointsContainer) {
          inputPointsContainer.classList.add('d-none');
        }

        if (formRoleBadge) {
          formRoleBadge.textContent = 'Mode Form Mahasiswa';
          formRoleBadge.className = 'badge badge-success font-weight-bold px-3 py-1.5 rounded-pill ml-2';
        }
        if (formSubTitleText) {
          formSubTitleText.textContent = 'Silakan pilih jenis & tingkat pelanggaran (Ringan, Sedang, Berat) serta wajib mengunggah bukti foto/video/PDF.';
        }
        if (formSubmitBtnText) {
          formSubmitBtnText.textContent = 'Kirim Laporan ke Kaprodi';
        }

        document.querySelectorAll('.kaprodi-only, .admin-only, .management-only').forEach(el => {
          if (!el.classList.contains('app-page')) {
            el.classList.add('d-none');
          }
        });

      } else if (role === 'kaprodi') {
        btnKaprodi.classList.add('active-kaprodi');
        if (roleBadge) {
          roleBadge.textContent = 'Mode Kaprodi (Verifikasi & Poin)';
          roleBadge.className = 'role-badge-indicator role-badge-kaprodi';
        }
        if (userNameDisplay) userNameDisplay.textContent = 'Kaprodi Informatika';

        if (inputPoints) {
          inputPoints.readOnly = false;
          inputPoints.classList.remove('bg-light');
        }
        if (inputSeverity) {
          inputSeverity.disabled = false;
          inputSeverity.classList.remove('bg-light');
        }
        if (inputPointsContainer) {
          inputPointsContainer.classList.remove('d-none');
        }
        if (formRoleBadge) {
          formRoleBadge.textContent = 'Mode Form Kaprodi (Edit Poin Manual)';
          formRoleBadge.className = 'badge badge-warning text-dark font-weight-bold px-3 py-1.5 rounded-pill ml-2';
        }
        if (formSubTitleText) {
          formSubTitleText.textContent = 'Kaprodi dapat membuat laporan pelanggaran langsung dan menyesuaikan bobot poin sanksi secara manual.';
        }
        if (inputPointsHelpText) {
          inputPointsHelpText.innerHTML = '<i class="fas fa-pen text-primary mr-1"></i> Mode Kaprodi: Anda berhak mengatur / mengedit bobot poin sanksi secara manual (1 s.d 100 Poin).';
        }
        if (formSubmitBtnText) {
          formSubmitBtnText.textContent = 'Simpan & Direct Verifikasi Laporan';
        }

        document.querySelectorAll('.mahasiswa-only, .admin-only').forEach(el => {
          if (!el.classList.contains('app-page')) {
            el.classList.add('d-none');
          }
        });

      } else {
        btnAdmin.classList.add('active-admin');
        if (roleBadge) {
          roleBadge.textContent = 'Mode Admin Kemahasiswaan';
          roleBadge.className = 'role-badge-indicator role-badge-admin';
        }
        if (userNameDisplay) userNameDisplay.textContent = 'Admin Kemahasiswaan';

        document.querySelectorAll('.mahasiswa-only, .kaprodi-only, .pelapor-only').forEach(el => {
          if (!el.classList.contains('app-page')) {
            el.classList.add('d-none');
          }
        });
      }
    }
  }

  function renderDashboardStats() {
    const reports = getStoredReports();
    const total = reports.length;
    const pendingKaprodi = reports.filter(r => r.status === 'dilaporkan').length;
    const escalatedKemahasiswaan = reports.filter(r => r.status === 'diteruskan_kemahasiswaan' || r.status === 'sedang_diproses' || r.status === 'sanksi_ditetapkan').length;
    const completed = reports.filter(r => r.status === 'selesai').length;

    const elTotal = document.getElementById('statTotal');
    const elPending = document.getElementById('statPending');
    const elProcessing = document.getElementById('statProcessing');
    const elCompleted = document.getElementById('statCompleted');
    const elBadgeKaprodiQueueCount = document.getElementById('badgeKaprodiQueueCount');
    const elBadgeAdminQueueCount = document.getElementById('badgeAdminQueueCount');

    if (elTotal) elTotal.textContent = total;
    if (elPending) elPending.textContent = pendingKaprodi;
    if (elProcessing) elProcessing.textContent = escalatedKemahasiswaan;
    if (elCompleted) elCompleted.textContent = completed;

    if (elBadgeKaprodiQueueCount) {
      if (pendingKaprodi > 0) {
        elBadgeKaprodiQueueCount.textContent = pendingKaprodi;
        elBadgeKaprodiQueueCount.classList.remove('d-none');
      } else {
        elBadgeKaprodiQueueCount.classList.add('d-none');
      }
    }

    if (elBadgeAdminQueueCount) {
      const adminQueue = reports.filter(r => r.status === 'diteruskan_kemahasiswaan').length;
      if (adminQueue > 0) {
        elBadgeAdminQueueCount.textContent = adminQueue;
        elBadgeAdminQueueCount.classList.remove('d-none');
      } else {
        elBadgeAdminQueueCount.classList.add('d-none');
      }
    }
  }

  function renderDashboardAnalytics() {
    const reports = getStoredReports();

    // 1. Calculate Category Progress Breakdown
    const total = reports.length || 1;
    const countAkademik = reports.filter(r => r.type === 'akademik' || (r.typeName && r.typeName.toLowerCase().includes('akademik'))).length;
    const countTertib = reports.filter(r => r.type === 'tertib' || (r.typeName && r.typeName.toLowerCase().includes('tertib'))).length;
    const countEtika = reports.filter(r => r.type === 'etika' || (r.typeName && r.typeName.toLowerCase().includes('etika'))).length;
    const countLainnya = Math.max(0, total - (countAkademik + countTertib + countEtika));

    const pctAkademik = Math.round((countAkademik / total) * 100);
    const pctTertib = Math.round((countTertib / total) * 100);
    const pctEtika = Math.round((countEtika / total) * 100);
    const pctLainnya = Math.round((countLainnya / total) * 100);

    const elBarAkademik = document.getElementById('dashBarAkademik');
    const elBarTertib = document.getElementById('dashBarTertib');
    const elBarEtika = document.getElementById('dashBarEtika');
    const elBarLainnya = document.getElementById('dashBarLainnya');

    if (elBarAkademik) { elBarAkademik.style.width = `${pctAkademik}%`; elBarAkademik.textContent = `${pctAkademik}% (${countAkademik})`; }
    if (elBarTertib) { elBarTertib.style.width = `${pctTertib}%`; elBarTertib.textContent = `${pctTertib}% (${countTertib})`; }
    if (elBarEtika) { elBarEtika.style.width = `${pctEtika}%`; elBarEtika.textContent = `${pctEtika}% (${countEtika})`; }
    if (elBarLainnya) { elBarLainnya.style.width = `${pctLainnya}%`; elBarLainnya.textContent = `${pctLainnya}% (${countLainnya})`; }

    // 2. Render Recent Activity Stream
    const activityFeed = document.getElementById('dashActivityFeed');
    if (activityFeed) {
      let allLogs = [];
      reports.forEach(r => {
        (r.logs || []).forEach(log => {
          allLogs.push({ ...log, reportId: r.id, studentName: r.name });
        });
      });

      allLogs.sort((a, b) => b.time.localeCompare(a.time));
      const latestLogs = allLogs.slice(0, 5);

      if (latestLogs.length === 0) {
        activityFeed.innerHTML = '<div class="text-muted small py-3 text-center">Belum ada riwayat aktivitas.</div>';
      } else {
        let html = '<ul class="list-group list-group-flush small">';
        latestLogs.forEach(log => {
          html += `
            <li class="list-group-item px-0 py-2.5 border-0 d-flex justify-content-between align-items-start">
              <div>
                <span class="font-weight-bold text-dark">${log.actor}</span>: 
                <span class="text-secondary">${log.action}</span>
                <span class="badge badge-light border ml-1">${log.reportId}</span>
              </div>
              <small class="text-muted font-weight-bold ml-2 text-nowrap">${log.time}</small>
            </li>
          `;
        });
        html += '</ul>';
        activityFeed.innerHTML = html;
      }
    }

    // 3. Hotspots
    const hotspotsContainer = document.getElementById('dashHotspotsContainer');
    if (hotspotsContainer) {
      const locMap = {};
      reports.forEach(r => {
        const loc = r.location || 'Area Kampus';
        locMap[loc] = (locMap[loc] || 0) + 1;
      });

      const sortedLocs = Object.keys(locMap).sort((a, b) => locMap[b] - locMap[a]).slice(0, 4);

      let html = '<div class="d-flex flex-wrap gap-2">';
      sortedLocs.forEach(loc => {
        html += `
          <div class="p-2.5 bg-light rounded border flex-fill text-center">
            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
            <span class="font-weight-bold text-dark small">${loc}</span>
            <span class="badge badge-primary rounded-pill ml-2">${locMap[loc]} Kasus</span>
          </div>
        `;
      });
      html += '</div>';
      hotspotsContainer.innerHTML = html;
    }
  }

  function getStatusBadgeHTML(status) {
    const badges = {
      dilaporkan: '<span class="badge-status badge-dilaporkan"><i class="fas fa-clock"></i> Dilaporkan (Verifikasi Kaprodi)</span>',
      diverifikasi_kaprodi: '<span class="badge-status badge-diverifikasi"><i class="fas fa-check-circle"></i> Diverifikasi Kaprodi</span>',
      diteruskan_kemahasiswaan: '<span class="badge-status badge-diteruskan"><i class="fas fa-arrow-right"></i> Diteruskan ke Kemahasiswaan</span>',
      sedang_diproses: '<span class="badge-status badge-sedang_diproses"><i class="fas fa-spinner fa-spin"></i> Diproses Kemahasiswaan</span>',
      sanksi_ditetapkan: '<span class="badge-status badge-sanksi_ditetapkan"><i class="fas fa-gavel"></i> Sanksi Ditetapkan</span>',
      selesai: '<span class="badge-status badge-selesai"><i class="fas fa-check-double"></i> Selesai</span>',
      ditolak: '<span class="badge-status badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>'
    };
    return badges[status] || `<span class="badge-status">${status}</span>`;
  }

  function getSeverityBadgeHTML(severity) {
    const maps = {
      ringan: '<span class="badge-severity severity-ringan">Ringan (10 Poin)</span>',
      sedang: '<span class="badge-severity severity-sedang">Sedang (25 Poin)</span>',
      berat: '<span class="badge-severity severity-berat">Berat (50 Poin)</span>'
    };
    return maps[severity] || severity;
  }

  function renderReportsTable() {
    const tableBody = document.getElementById('reportsTableBody');
    if (!tableBody) return;

    const reports = getStoredReports();
    const role = getActiveRole();
    const filterStatus = document.getElementById('filterStatusSelect')?.value || 'all';
    const filterSearch = document.getElementById('filterSearchInput')?.value.toLowerCase() || '';

    // Scope reports according to active user role
    let roleFilteredReports = reports;
    const titleEl = document.getElementById('daftarLaporanTitle');
    const subTitleEl = document.getElementById('daftarLaporanSubTitle');

    if (role === 'kaprodi') {
      if (titleEl) titleEl.innerHTML = '<i class="fas fa-clipboard-list text-primary mr-2"></i>Daftar Laporan Pelanggaran (Kaprodi)';
      if (subTitleEl) subTitleEl.textContent = 'Daftar seluruh laporan masuk dan terverifikasi pada tingkat Program Studi.';

      roleFilteredReports = reports.filter(r => 
        r.status === 'dilaporkan' || 
        r.status === 'diverifikasi_kaprodi' || 
        r.status === 'ditolak' ||
        (r.reporterRole && r.reporterRole.toLowerCase().includes('kaprodi'))
      );
    } else if (role === 'admin') {
      if (titleEl) titleEl.innerHTML = '<i class="fas fa-shield-alt text-purple mr-2" style="color:#7c3aed;"></i>Daftar Kasus Escalated & Sanksi (Kemahasiswaan)';
      if (subTitleEl) subTitleEl.textContent = 'Daftar kasus pelanggaran yang diteruskan ke Bagian Kemahasiswaan (Akumulasi Poin ≥ 100 Poin).';

      roleFilteredReports = reports.filter(r => 
        r.status === 'diteruskan_kemahasiswaan' || 
        r.status === 'sedang_diproses' || 
        r.status === 'sanksi_ditetapkan' || 
        r.status === 'selesai'
      );
    } else if (role === 'mahasiswa') {
      if (titleEl) titleEl.innerHTML = '<i class="fas fa-file-alt text-success mr-2"></i>Riwayat Laporan Saya';
      if (subTitleEl) subTitleEl.textContent = 'Daftar laporan pelanggaran yang telah Anda buat.';

      roleFilteredReports = getMahasiswaReports();
    }

    let filtered = roleFilteredReports.filter(r => {
      if (filterStatus !== 'all') {
        if (filterStatus === 'sedang_diproses' && (r.status === 'sedang_diproses' || r.status === 'sanksi_ditetapkan')) return true;
        if (r.status !== filterStatus) return false;
      }
      if (filterSearch) {
        const matchId = r.id.toLowerCase().includes(filterSearch);
        const matchName = r.name.toLowerCase().includes(filterSearch);
        const matchNim = r.nim.toLowerCase().includes(filterSearch);
        const matchType = (r.typeName || '').toLowerCase().includes(filterSearch);
        if (!matchId && !matchName && !matchNim && !matchType) return false;
      }
      return true;
    });

    if (filtered.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">
            <i class="fas fa-folder-open fa-2x mb-2 d-block text-gray-400"></i>
            Tidak ada laporan yang sesuai dengan hak akses peran dan kriteria pencarian saat ini.
          </td>
        </tr>
      `;
      return;
    }

    let html = '';
    filtered.forEach((r) => {
      html += `
        <tr>
          <td class="font-weight-bold text-primary">${r.id}</td>
          <td>
            <div style="font-weight:700;">${r.name}</div>
            <div class="small text-muted">${r.nim} (${r.program})</div>
          </td>
          <td>
            <div>${r.typeName}</div>
            <div class="small text-muted">${r.subtype}</div>
          </td>
          <td>${getSeverityBadgeHTML(r.severity)}</td>
          <td><span class="badge badge-secondary font-weight-bold">${r.points || 0} Poin</span></td>
          <td>${r.date} <span class="small text-muted">${r.time || ''}</span></td>
          <td>${getStatusBadgeHTML(r.status)}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-3" onclick="openDetailModal('${r.id}')">
              <i class="fas fa-eye mr-1"></i> Detail
            </button>
          </td>
        </tr>
      `;
    });

    tableBody.innerHTML = html;
  }

  // --- Queue 1: Kaprodi Verification Queue ---
  function renderKaprodiVerificationQueue() {
    const queueContainer = document.getElementById('kaprodiVerificationQueue');
    if (!queueContainer) return;

    const allReports = getStoredReports();
    const students = getStoredStudents();

    // 1. Check for notification banner: students whose total points >= 100
    let bannersHtml = '';
    students.forEach(s => {
      const totalPts = calculateStudentTotalPoints(s.nim);
      if (totalPts >= THRESHOLD_POINTS) {
        const hasUnescalated = allReports.some(r => r.nim === s.nim && r.status !== 'diteruskan_kemahasiswaan' && r.status !== 'sedang_diproses' && r.status !== 'sanksi_ditetapkan' && r.status !== 'selesai' && r.status !== 'ditolak');
        if (hasUnescalated) {
          bannersHtml += `
            <div class="alert alert-danger border-danger p-3.5 mb-4 rounded-lg shadow-sm">
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                  <div class="font-weight-bold text-danger h6 mb-1">
                    <i class="fas fa-exclamation-triangle fa-lg mr-2"></i> PEMBERITAHUAN POIN MELEBIHI BATAS (100 POIN)
                  </div>
                  <div class="small text-dark font-weight-bold">
                    Mahasiswa: <strong class="text-danger">${s.name} (${s.nim})</strong> - ${s.program} | Total Akumulasi Poin: <span class="badge badge-danger p-1.5">${totalPts} Poin</span>
                  </div>
                  <div class="small text-muted mt-1">Akumulasi poin terverifikasi telah mencapai/melebihi 100 Poin. Silakan teruskan rekam jejak mahasiswa ini ke Bagian Kemahasiswaan.</div>
                </div>
                <button class="btn btn-danger font-weight-bold px-3 py-2 rounded-pill shadow-sm" onclick="kaprodiForwardStudentToAdmin('${s.nim}')">
                  <i class="fas fa-paper-plane mr-1"></i> Teruskan
                </button>
              </div>
            </div>
          `;
        }
      }
    });

    const pendingReports = allReports.filter(r => r.status === 'dilaporkan' || r.status === 'diverifikasi_kaprodi');

    if (pendingReports.length === 0 && !bannersHtml) {
      queueContainer.innerHTML = `
        <div class="alert alert-light border text-center py-5 text-muted mb-0 rounded-lg">
          <i class="fas fa-check-double text-success fa-3x mb-3 d-block"></i>
          <h6 class="font-weight-bold text-dark mb-1">Semua Laporan Telah Diverifikasi Kaprodi</h6>
          <p class="small text-muted mb-0">Tidak ada antrean laporan baru dari Mahasiswa yang memerlukan verifikasi saat ini.</p>
        </div>
      `;
      return;
    }

    let html = bannersHtml + '<div class="row">';
    pendingReports.forEach(r => {
      const totalPoints = calculateStudentTotalPoints(r.nim);
      const isOverThreshold = totalPoints >= THRESHOLD_POINTS;

      html += `
        <div class="col-md-6 mb-4">
          <div class="card border-left-primary shadow-sm h-100 py-2">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <span class="badge badge-primary font-weight-bold mr-1">${r.id}</span>
                  ${getStatusBadgeHTML(r.status)}
                </div>
                <small class="text-muted"><i class="far fa-clock"></i> ${r.date}</small>
              </div>

              <h6 class="font-weight-bold text-gray-900 mb-1">${r.name} (${r.nim})</h6>
              <div class="small text-muted mb-2">Pelapor: <strong>${r.reporterRole}</strong></div>

              <div class="p-2.5 bg-light rounded border mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="small font-weight-bold text-dark">${r.typeName} (${r.subtype})</span>
                  <span class="badge badge-warning text-dark font-weight-bold">${r.points} Poin</span>
                </div>
                <div class="small text-secondary" style="max-height:60px; overflow:hidden;">"${r.chronology}"</div>
              </div>

              <div class="p-2 bg-gradient-light rounded border mb-3 d-flex justify-content-between align-items-center">
                <span class="small font-weight-bold text-dark"><i class="fas fa-calculator text-primary mr-1"></i> Akumulasi Poin Mahasiswa:</span>
                <span class="font-weight-bold ${isOverThreshold ? 'text-danger' : 'text-primary'}">${totalPoints} / 100 Poin</span>
              </div>

              ${r.sanksi ? `
                <div class="alert alert-success p-2 small mb-2 font-weight-bold rounded">
                  <i class="fas fa-gavel mr-1"></i> Sanksi/Tindakan Internal Kaprodi: ${r.sanksi}
                </div>
              ` : ''}

              <div class="d-flex gap-2 flex-wrap justify-content-end">
                <button class="btn btn-sm btn-outline-info shadow-sm" onclick="openDetailModal('${r.id}')">
                  <i class="fas fa-eye mr-1"></i> Detail
                </button>
                <button class="btn btn-sm btn-outline-warning shadow-sm" onclick="openEditPointModal('${r.id}')">
                  <i class="fas fa-edit mr-1"></i> Edit Poin
                </button>
                ${r.status === 'dilaporkan' ? `
                  <button class="btn btn-sm btn-outline-danger shadow-sm" onclick="kaprodiRejectReport('${r.id}')">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                  <button class="btn btn-sm btn-success shadow-sm font-weight-bold" onclick="kaprodiOpenVerifyModal('${r.id}')">
                    <i class="fas fa-check mr-1"></i> Setujui (ACC)
                  </button>
                ` : ''}
              </div>

            </div>
          </div>
        </div>
      `;
    });
    html += '</div>';
    queueContainer.innerHTML = html;
  }

  // --- Queue 2: Admin Kemahasiswaan Queue ---
  function renderAdminVerificationQueue() {
    const queueContainer = document.getElementById('adminVerificationQueue');
    if (!queueContainer) return;

    const allReports = getStoredReports();
    const escalatedReports = allReports.filter(r => r.status === 'diteruskan_kemahasiswaan' || r.status === 'sedang_diproses' || r.status === 'sanksi_ditetapkan');

    if (escalatedReports.length === 0) {
      queueContainer.innerHTML = `
        <div class="alert alert-light border text-center py-5 text-muted mb-0 rounded-lg">
          <i class="fas fa-shield-alt text-purple fa-3x mb-3 d-block" style="color:#7c3aed;"></i>
          <h6 class="font-weight-bold text-dark mb-1">Tidak Ada Kasus Escalated dalam Antrean</h6>
          <p class="small text-muted mb-0">Semua kasus yang diteruskan dari Kaprodi (Poin ≥ 100) telah diproses atau diselesaikan.</p>
        </div>
      `;
      return;
    }

    // Group escalated reports by NIM
    const studentMap = {};
    escalatedReports.forEach(r => {
      if (!studentMap[r.nim]) {
        studentMap[r.nim] = {
          nim: r.nim,
          name: r.name,
          program: r.program,
          reports: []
        };
      }
      studentMap[r.nim].reports.push(r);
    });

    let html = '';
    Object.values(studentMap).forEach(s => {
      const studentTotalPoints = calculateStudentTotalPoints(s.nim);
      const latestReport = s.reports[0];

      html += `
        <div class="card border-left-purple shadow-sm mb-4" style="border-left: 5px solid #7c3aed; border-radius: 12px;">
          <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <div>
              <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-graduate text-purple mr-2" style="color:#7c3aed;"></i> ${s.name}</h5>
              <div class="small text-muted">NIM: <strong>${s.nim}</strong> | Prodi: <strong>${s.program}</strong></div>
            </div>
            <div class="text-right">
              <span class="badge badge-danger p-2 font-weight-bold h6 mb-0">Akumulasi: ${studentTotalPoints} / 100 Poin</span>
            </div>
          </div>
          <div class="card-body p-4">
            <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-list text-primary mr-1"></i> Rincian Detail Pelanggaran Mahasiswa (${s.reports.length} Laporan Escalated):</h6>
            
            <div class="table-responsive mb-3">
              <table class="table table-bordered table-sm small align-middle">
                <thead class="thead-light">
                  <tr>
                    <th>ID Laporan</th>
                    <th>Jenis & Subjenis</th>
                    <th>Tingkat / Poin</th>
                    <th>Tanggal & Lokasi</th>
                    <th>Kronologi Kejadian</th>
                    <th>Bukti</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  ${s.reports.map(r => `
                    <tr>
                      <td class="font-weight-bold text-primary">${r.id}</td>
                      <td><strong>${r.typeName}</strong><br><span class="text-muted">${r.subtype}</span></td>
                      <td>${getSeverityBadgeHTML(r.severity)}<br><span class="badge badge-dark">${r.points} Poin</span></td>
                      <td>${r.date} ${r.time || ''}<br><span class="text-muted">${r.location}</span></td>
                      <td style="max-width:260px; white-space:pre-wrap;">${r.chronology}</td>
                      <td>${(r.evidence && r.evidence.length > 0) ? `<span class="badge badge-info"><i class="fas fa-paperclip"></i> ${r.evidence.length} File</span>` : '<span class="text-muted">-</span>'}</td>
                      <td>${getStatusBadgeHTML(r.status)}</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>

            ${latestReport.sanksi ? `
              <div class="alert alert-purple p-3 mb-3 font-weight-bold rounded" style="background:#f3e8ff; color:#6d28d9; border: 1px solid #e9d5ff;">
                <i class="fas fa-gavel mr-1"></i> Sanksi Resmi Kampus Ditetapkan: ${latestReport.sanksi}
              </div>
            ` : ''}

            <div class="d-flex gap-2 flex-wrap justify-content-end align-items-center">
              <span class="small text-muted mr-auto"><i class="fas fa-bullhorn text-warning mr-1"></i> Status Kemahasiswaan: Pemanggilan & SK Sanksi</span>
              
              ${latestReport.status === 'diteruskan_kemahasiswaan' ? `
                <button class="btn btn-sm btn-info shadow-sm font-weight-bold px-3 rounded-pill" onclick="adminProcessStudentReports('${s.nim}')">
                  <i class="fas fa-user-clock mr-1"></i> Proses
                </button>
              ` : ''}
              
              ${(latestReport.status === 'diteruskan_kemahasiswaan' || latestReport.status === 'sedang_diproses') ? `
                <button class="btn btn-sm btn-primary shadow-sm font-weight-bold px-3 rounded-pill" style="background:#7c3aed; border-color:#7c3aed;" onclick="openSanksiModal('${latestReport.id}')">
                  <i class="fas fa-gavel mr-1"></i> Sanksi
                </button>
              ` : ''}
              
              ${latestReport.status === 'sanksi_ditetapkan' ? `
                <button class="btn btn-sm btn-success shadow-sm font-weight-bold px-3 rounded-pill" onclick="adminCloseStudentCases('${s.nim}')">
                  <i class="fas fa-check-circle mr-1"></i> Selesai
                </button>
              ` : ''}
            </div>

          </div>
        </div>
      `;
    });

    queueContainer.innerHTML = html;
  }

  // --- Detail Modal Handler ---
  window.openDetailModal = function (reportId) {
    currentActiveModalReportId = reportId;
    const reports = getStoredReports();
    const r = reports.find(item => item.id === reportId);
    if (!r) return;

    document.getElementById('modalReportId').textContent = r.id;
    document.getElementById('modalStudentName').textContent = r.name;
    document.getElementById('modalStudentNim').textContent = r.nim;
    document.getElementById('modalStudentProgram').textContent = r.program;
    document.getElementById('modalReporterRole').textContent = r.reporterRole;
    document.getElementById('modalCategory').textContent = `${r.typeName} (${r.subtype})`;
    document.getElementById('modalSeverity').innerHTML = `${getSeverityBadgeHTML(r.severity)} <span class="badge badge-dark ml-1">${r.points || 0} Poin</span>`;
    document.getElementById('modalDateTime').textContent = `${r.date} Pukul ${r.time || '-'}`;
    document.getElementById('modalLocation').textContent = r.location;
    document.getElementById('modalChronology').textContent = r.chronology;
    document.getElementById('modalStatusBadge').innerHTML = getStatusBadgeHTML(r.status);

    // Evidence
    const elEvidence = document.getElementById('modalEvidenceContainer');
    if (r.evidence && r.evidence.length > 0) {
      let evHtml = '<div class="d-flex flex-wrap gap-2">';
      r.evidence.forEach(ev => {
        if (ev.url) {
          evHtml += `
            <a href="${ev.url}" target="_blank" class="border rounded p-1 bg-white d-inline-block text-center shadow-sm">
              <img src="${ev.url}" style="width:100px; height:80px; object-fit:cover; border-radius:6px;" alt="Bukti">
              <div class="small text-truncate mt-1" style="max-width:100px;">${ev.name}</div>
            </a>
          `;
        } else {
          evHtml += `<span class="badge badge-light border"><i class="fas fa-file-alt mr-1"></i> ${ev.name}</span>`;
        }
      });
      evHtml += '</div>';
      elEvidence.innerHTML = evHtml;
    } else {
      elEvidence.innerHTML = '<span class="text-muted small">Tidak ada lampiran foto/berkas.</span>';
    }

    // Role Actions (Strictly Restricted for Mahasiswa)
    const role = getActiveRole();
    const elActions = document.getElementById('modalAdminActions');
    let actHtml = '';

    if (role === 'mahasiswa') {
      actHtml = `
        <span class="text-muted small align-self-center"><i class="fas fa-info-circle mr-1 text-primary"></i> Mode Mahasiswa: Hanya dapat mengisi laporan pelanggaran.</span>
      `;
    } else if (role === 'kaprodi') {
      actHtml += `
        <button class="btn btn-warning mr-2 shadow-sm" onclick="openEditPointModal('${r.id}')"><i class="fas fa-edit mr-1"></i> Edit Poin</button>
      `;
      if (r.status === 'dilaporkan') {
        actHtml += `
          <button class="btn btn-danger mr-2 shadow-sm" onclick="kaprodiRejectReport('${r.id}')"><i class="fas fa-times mr-1"></i> Tolak</button>
          <button class="btn btn-success mr-2 shadow-sm" onclick="kaprodiVerifyReport('${r.id}')"><i class="fas fa-check mr-1"></i> Verifikasi Laporan</button>
        `;
      }
      if (r.status === 'dilaporkan' || r.status === 'diverifikasi_kaprodi') {
        actHtml += `
          <button class="btn btn-primary shadow-sm" onclick="kaprodiForwardToAdmin('${r.id}')"><i class="fas fa-paper-plane mr-1"></i> Teruskan ke Kemahasiswaan</button>
        `;
      }
    } else if (role === 'admin') {
      if (r.status === 'diteruskan_kemahasiswaan') {
        actHtml += `
          <button class="btn btn-info mr-2 shadow-sm" onclick="adminProcessReport('${r.id}')"><i class="fas fa-play mr-1"></i> Proses Penanganan</button>
        `;
      } else if (r.status === 'sedang_diproses') {
        actHtml += `
          <button class="btn btn-primary mr-2 shadow-sm" style="background:#7c3aed; border-color:#7c3aed;" onclick="openSanksiModal('${r.id}')"><i class="fas fa-gavel mr-1"></i> Tetapkan Sanksi Resmi</button>
        `;
      } else if (r.status === 'sanksi_ditetapkan') {
        actHtml += `
          <button class="btn btn-success mr-2 shadow-sm" onclick="adminCloseCase('${r.id}')"><i class="fas fa-check-circle mr-1"></i> Selesaikan Kasus</button>
        `;
      }
    }

    actHtml += `
      <button class="btn btn-outline-secondary ml-auto shadow-sm" onclick="window.printReport('${r.id}')"><i class="fas fa-print mr-1"></i> Cetak Laporan</button>
    `;

    elActions.innerHTML = actHtml;

    // Timeline
    const elTimeline = document.getElementById('modalTimeline');
    let tlHtml = '';
    (r.logs || []).forEach(log => {
      tlHtml += `
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-content">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="font-weight-bold text-dark small">${log.actor}</span>
              <span class="text-muted small" style="font-size:0.75rem;">${log.time}</span>
            </div>
            <div class="small text-slate-700">${log.action}</div>
          </div>
        </div>
      `;
    });
    elTimeline.innerHTML = tlHtml;

    $('#reportDetailModal').modal('show');
  };

  // --- Modal Edit Poin (Kaprodi Only) ---
  window.openEditPointModal = function (reportId) {
    if (getActiveRole() !== 'kaprodi') {
      sipmaToast('Hanya Kaprodi yang berhak mengubah poin sanksi.', 'danger');
      return;
    }
    currentActiveModalReportId = reportId;
    const reports = getStoredReports();
    const r = reports.find(item => item.id === reportId);
    if (!r) return;

    document.getElementById('editPointReportIdDisplay').textContent = r.id;
    document.getElementById('editPointInput').value = r.points || DEFAULT_POINTS[r.severity] || 10;
    document.getElementById('editPointReasonInput').value = '';

    $('#reportDetailModal').modal('hide');
    $('#editPointModal').modal('show');
  };

  window.submitEditPointForm = function (e) {
    e.preventDefault();
    if (getActiveRole() !== 'kaprodi') {
      alert('Akses Ditolak: Hanya Kaprodi yang berhak mengubah poin sanksi.');
      return;
    }
    const reportId = currentActiveModalReportId;
    const newPoints = parseInt(document.getElementById('editPointInput').value || '0', 10);
    const reason = document.getElementById('editPointReasonInput').value;

    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      const oldPoints = reports[index].points;
      reports[index].points = newPoints;

      const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
      reports[index].logs.push({
        time: now,
        actor: 'Kaprodi Informatika',
        action: `Bobot poin diubah dari ${oldPoints} Poin menjadi ${newPoints} Poin. Catatan: ${reason}`
      });

      saveReports(reports);
      sipmaToast(`Bobot poin laporan ${reportId} berhasil diubah menjadi ${newPoints} Poin!`, 'success');
      $('#editPointModal').modal('hide');
      updateRoleUI();
    }
  };

  // --- Kaprodi Handlers ---
  window.kaprodiOpenVerifyModal = function (reportId) {
    if (getActiveRole() !== 'kaprodi') return;
    currentActiveModalReportId = reportId;
    document.getElementById('kaprodiVerifyReportIdDisplay').textContent = reportId;
    document.getElementById('kaprodiSanksiInput').value = '';
    $('#reportDetailModal').modal('hide');
    $('#kaprodiVerifyModal').modal('show');
  };

  window.submitKaprodiVerifyForm = function (e) {
    e.preventDefault();
    if (getActiveRole() !== 'kaprodi') return;
    const reportId = currentActiveModalReportId;
    const sanksiVal = document.getElementById('kaprodiSanksiInput').value.trim();

    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      reports[index].status = 'diverifikasi_kaprodi';
      if (sanksiVal) {
        reports[index].sanksi = sanksiVal;
      }
      const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
      reports[index].logs.push({
        time: now,
        actor: 'Kaprodi Informatika',
        action: `Laporan disetujui (ACC) oleh Kaprodi.${sanksiVal ? ' Tindakan/Sanksi Internal: ' + sanksiVal : ''}`
      });
      saveReports(reports);
      sipmaToast(`Laporan ${reportId} berhasil disetujui (ACC) oleh Kaprodi!`, 'success');
      $('#kaprodiVerifyModal').modal('hide');
      updateRoleUI();
    }
  };

  window.kaprodiVerifyReport = function (reportId) {
    kaprodiOpenVerifyModal(reportId);
  };

  window.kaprodiRejectReport = function (reportId) {
    if (getActiveRole() !== 'kaprodi') return;
    const reason = prompt('Masukkan alasan penolakan laporan pelanggaran ini:');
    if (reason === null) return;
    if (!reason.trim()) {
      alert('Alasan penolakan wajib diisi!');
      return;
    }

    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      reports[index].status = 'ditolak';
      reports[index].rejectionReason = reason;
      const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
      reports[index].logs.push({
        time: now,
        actor: 'Kaprodi Informatika',
        action: `Laporan Ditolak. Alasan: ${reason}`
      });
      saveReports(reports);
      sipmaToast(`Laporan ${reportId} telah ditolak oleh Kaprodi.`, 'danger');
      $('#reportDetailModal').modal('hide');
      updateRoleUI();
    }
  };

  window.kaprodiForwardStudentToAdmin = function (nim) {
    if (getActiveRole() !== 'kaprodi') return;
    const reports = getStoredReports();
    let count = 0;
    const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
    const totalPts = calculateStudentTotalPoints(nim);

    reports.forEach(r => {
      if (r.nim === nim && r.status !== 'ditolak' && r.status !== 'selesai') {
        r.status = 'diteruskan_kemahasiswaan';
        r.logs.push({
          time: now,
          actor: 'Kaprodi Informatika',
          action: `Seluruh rekam jejak diteruskan ke Bagian Kemahasiswaan (Poin Mahasiswa: ${totalPts} Poin, Melebihi Batas 100 Poin).`
        });
        count++;
      }
    });

    saveReports(reports);
    sipmaToast(`Seluruh rekam jejak mahasiswa (NIM: ${nim}) berhasil diteruskan ke Bagian Kemahasiswaan!`, 'success');
    updateRoleUI();
  };

  window.kaprodiForwardToAdmin = function (reportId) {
    if (getActiveRole() !== 'kaprodi') return;
    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      const nim = reports[index].nim;
      kaprodiForwardStudentToAdmin(nim);
    }
  };

  // --- Admin Kemahasiswaan Handlers ---
  window.adminProcessStudentReports = function (nim) {
    if (getActiveRole() !== 'admin') return;
    const reports = getStoredReports();
    const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
    reports.forEach(r => {
      if (r.nim === nim && r.status === 'diteruskan_kemahasiswaan') {
        r.status = 'sedang_diproses';
        r.logs.push({
          time: now,
          actor: 'Admin Kemahasiswaan',
          action: 'Kasus diterima & proses pemanggilan mahasiswa secara langsung.'
        });
      }
    });
    saveReports(reports);
    sipmaToast(`Proses penanganan & pemanggilan mahasiswa (NIM: ${nim}) dimulai!`, 'info');
    updateRoleUI();
  };

  window.adminCloseStudentCases = function (nim) {
    if (getActiveRole() !== 'admin') return;
    if (!confirm('Apakah Anda yakin sanksi telah dijalankan dan seluruh kasus mahasiswa ini siap ditutup?')) return;
    const reports = getStoredReports();
    const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
    reports.forEach(r => {
      if (r.nim === nim && (r.status === 'sanksi_ditetapkan' || r.status === 'sedang_diproses')) {
        r.status = 'selesai';
        r.logs.push({
          time: now,
          actor: 'Admin Kemahasiswaan',
          action: 'Sanksi resmi & pemanggilan mahasiswa telah dilaksanakan. Kasus resmi ditutup (Selesai).'
        });
      }
    });
    saveReports(reports);
    sipmaToast(`Kasus mahasiswa (NIM: ${nim}) resmi diselesaikan dan ditutup!`, 'success');
    updateRoleUI();
  };

  // --- Admin Kemahasiswaan Handlers ---
  window.adminProcessReport = function (reportId) {
    if (getActiveRole() !== 'admin') return;
    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      reports[index].status = 'sedang_diproses';
      const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
      reports[index].logs.push({
        time: now,
        actor: 'Admin Kemahasiswaan',
        action: 'Laporan diterima dan sedang diproses (Pemanggilan / Pembinaan Mahasiswa).'
      });
      saveReports(reports);
      sipmaToast(`Laporan ${reportId} mulai diproses oleh Admin Kemahasiswaan!`, 'info');
      $('#reportDetailModal').modal('hide');
      updateRoleUI();
    }
  };

  window.openSanksiModal = function (reportId) {
    if (getActiveRole() !== 'admin') return;
    currentActiveModalReportId = reportId;
    document.getElementById('sanksiReportIdDisplay').textContent = reportId;
    $('#reportDetailModal').modal('hide');
    $('#sanksiModal').modal('show');
  };

  window.submitSanksiForm = function (e) {
    e.preventDefault();
    if (getActiveRole() !== 'admin') return;
    const reportId = currentActiveModalReportId;
    const sanksiType = document.getElementById('sanksiTypeInput').value;
    const sanksiNotes = document.getElementById('sanksiNotesInput').value;

    if (!sanksiType) {
      alert('Pilih jenis sanksi terlebih dahulu!');
      return;
    }

    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      reports[index].status = 'sanksi_ditetapkan';
      reports[index].sanksi = `${sanksiType} - ${sanksiNotes}`;
      const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
      reports[index].logs.push({
        time: now,
        actor: 'Admin Kemahasiswaan',
        action: `Sanksi Resmi Ditetapkan: ${sanksiType} (${sanksiNotes})`
      });
      saveReports(reports);
      sipmaToast(`Sanksi resmi untuk laporan ${reportId} berhasil ditetapkan!`, 'success');
      $('#sanksiModal').modal('hide');
      updateRoleUI();
    }
  };

  window.adminCloseCase = function (reportId) {
    if (getActiveRole() !== 'admin') return;
    if (!confirm('Apakah Anda yakin sanksi telah dijalankan dan kasus ini siap ditutup?')) return;

    const reports = getStoredReports();
    const index = reports.findIndex(r => r.id === reportId);
    if (index !== -1) {
      reports[index].status = 'selesai';
      const now = new Date().toISOString().replace('T', ' ').substring(0, 16);
      reports[index].logs.push({
        time: now,
        actor: 'Admin Kemahasiswaan',
        action: 'Sanksi telah dilaksanakan. Kasus resmi ditutup (Selesai).'
      });
      saveReports(reports);
      sipmaToast(`Kasus ${reportId} resmi ditutup (Selesai)!`, 'success');
      $('#reportDetailModal').modal('hide');
      updateRoleUI();
    }
  };

  // --- Form Creation (Mahasiswa / Kaprodi) ---
  function setupCreateReportForm() {
    const form = document.getElementById('reportForm');
    if (!form) return;

    const inputNim = document.getElementById('inputNim');
    const inputName = document.getElementById('inputName');
    const inputProgram = document.getElementById('inputProgram');
    const inputType = document.getElementById('inputType');
    const inputSubtype = document.getElementById('inputSubtype');
    const inputSeverity = document.getElementById('inputSeverity');
    const inputPoints = document.getElementById('inputPoints');
    const customTypeContainer = document.getElementById('customTypeContainer');
    const customSubtypeContainer = document.getElementById('customSubtypeContainer');
    const inputCustomType = document.getElementById('inputCustomType');
    const inputCustomSubtype = document.getElementById('inputCustomSubtype');
    const btnFindStudent = document.getElementById('btnFindStudent');

    if (inputSeverity && inputPoints) {
      inputSeverity.addEventListener('change', (e) => {
        const sev = e.target.value;
        inputPoints.value = DEFAULT_POINTS[sev] || 50;
      });
    }

    const doFindStudent = () => {
      const nim = inputNim.value.trim();
      const students = getStoredStudents();
      const found = students.find(s => s.nim === nim);
      if (found) {
        inputName.value = found.name;
        inputProgram.value = `${found.program} / ${found.angkatan}`;
        sipmaToast(`Mahasiswa ditemukan: ${found.name}`, 'success');
      } else {
        if (nim === '') {
          inputNim.value = '2388010016';
          inputName.value = 'Faqih Firansyah';
          inputProgram.value = 'Teknik Informatika / 2023';
          sipmaToast('Mahasiswa sampel diisi secara otomatis.', 'warning');
        } else {
          alert('NIM Mahasiswa tidak ditemukan di database master!');
        }
      }
    };

    if (btnFindStudent) btnFindStudent.addEventListener('click', doFindStudent);
    if (inputNim) {
      inputNim.addEventListener('change', () => {
        if (inputNim.value.length >= 8) doFindStudent();
      });
    }

    if (inputType) {
      inputType.addEventListener('change', (e) => {
        const val = e.target.value;
        if (val === 'lainnya') {
          if (customTypeContainer) customTypeContainer.classList.remove('d-none');
          if (customSubtypeContainer) customSubtypeContainer.classList.remove('d-none');
          inputSubtype.innerHTML = '<option value="lainnya">Lainnya (Ketik Sendiri)</option>';
        } else {
          if (customTypeContainer) customTypeContainer.classList.add('d-none');
          if (customSubtypeContainer) customSubtypeContainer.classList.add('d-none');
          
          inputSubtype.innerHTML = '<option value="">-- Pilih Subjenis --</option>';
          const list = SUBTYPE_VARIANTS[val] || [];
          list.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub;
            opt.textContent = sub;
            inputSubtype.appendChild(opt);
          });
          inputSubtype.innerHTML += '<option value="lainnya">Lainnya (Ketik Sendiri)</option>';
        }
        triggerAutoSystemDetermination();
      });
    }

    if (inputSubtype) {
      inputSubtype.addEventListener('change', (e) => {
        if (e.target.value === 'lainnya') {
          if (customSubtypeContainer) customSubtypeContainer.classList.remove('d-none');
        } else if (inputType.value !== 'lainnya') {
          if (customSubtypeContainer) customSubtypeContainer.classList.add('d-none');
        }
        triggerAutoSystemDetermination();
      });
    }

    const inputEvidence = document.getElementById('inputEvidence');
    const evidencePreview = document.getElementById('evidencePreviewContainer');
    currentUploadedFiles = [];

    if (inputEvidence) {
      inputEvidence.addEventListener('change', (e) => {
        currentUploadedFiles = [];
        if (evidencePreview) evidencePreview.innerHTML = '';
        const files = Array.from(e.target.files);
        files.forEach(file => {
          const reader = new FileReader();
          reader.onload = (event) => {
            const url = event.target.result;
            currentUploadedFiles.push({ name: file.name, type: file.type.includes('image') ? 'image' : 'doc', url: url });
            if (evidencePreview && file.type.includes('image')) {
              const imgWrap = document.createElement('div');
              imgWrap.className = 'd-inline-block position-relative mr-2 mb-2';
              imgWrap.innerHTML = `
                <img src="${url}" style="width:70px; height:70px; object-fit:cover; border-radius:8px; border:1px solid #cbd5e1;">
                <span class="small d-block text-truncate" style="max-width:70px;">${file.name}</span>
              `;
              evidencePreview.appendChild(imgWrap);
            }
          };
          reader.readAsDataURL(file);
        });
      });
    }

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const nim = inputNim.value.trim();
      const name = inputName.value.trim();
      const program = inputProgram.value.trim();
      const rawType = inputType.value;
      let rawSubtype = inputSubtype.value;
      const severity = inputSeverity.value;
      const points = parseInt(inputPoints.value || '10', 10);
      const date = document.getElementById('inputDate').value;
      const time = document.getElementById('inputTime').value;
      const location = document.getElementById('inputLocation').value;
      const chronology = document.getElementById('inputChronology').value;

      if (!nim || !rawType || !date || !location || !chronology) {
        alert('Mohon lengkapi semua kolom bertanda bintang (*)!');
        return;
      }

      let typeName = 'Pelanggaran Umum';
      let subtypeName = 'Pelanggaran Umum';

      const typeNamesMap = { akademik: 'Kecurangan Akademik', tertib: 'Pelanggaran Tata Tertib', etika: 'Pelanggaran Etika & Perilaku' };

      if (rawType === 'lainnya') {
        typeName = inputCustomType.value.trim() || 'Pelanggaran Lainnya';
        subtypeName = inputCustomSubtype.value.trim() || 'Subjenis Lainnya';
      } else {
        typeName = typeNamesMap[rawType] || rawType;
        if (rawSubtype === 'lainnya') {
          subtypeName = inputCustomSubtype.value.trim() || 'Subjenis Lainnya';
        } else {
          subtypeName = rawSubtype || 'Pelanggaran Umum';
        }
      }

      const activeRole = getActiveRole();
      const reporterRoleStr = activeRole === 'mahasiswa' ? 'Mahasiswa (Pelapor)' : 'Kaprodi Informatika';
      const reporterNameStr = activeRole === 'mahasiswa' ? 'Ahmad Fauzi (Mahasiswa)' : 'Dr. Hendra Wijaya, M.Kom (Kaprodi)';

      const dateStr = date.replace(/-/g, '');
      const reports = getStoredReports();
      const newIndex = (reports.length + 1).toString().padStart(3, '0');
      const newId = `LPM-${dateStr}-${newIndex}`;

      const newReport = {
        id: newId,
        nim: nim,
        name: name || 'Mahasiswa Terlapor',
        program: program || 'Program Studi',
        reporterRole: reporterRoleStr,
        reporterName: reporterNameStr,
        type: rawType,
        typeName: typeName,
        subtype: subtypeName,
        severity: severity || 'sedang',
        points: points,
        date: date,
        time: time || '10:00',
        location: location,
        chronology: chronology,
        evidence: currentUploadedFiles.length > 0 ? currentUploadedFiles : [],
        status: 'dilaporkan',
        rejectionReason: '',
        sanksi: '',
        sanksiPoints: points,
        logs: [
          {
            time: new Date().toISOString().replace('T', ' ').substring(0, 16),
            actor: reporterRoleStr,
            action: 'Laporan dibuat & dikirimkan ke Kaprodi untuk verifikasi'
          }
        ]
      };

      reports.unshift(newReport);
      saveReports(reports);

      sipmaToast(`Laporan ${newId} berhasil dikirim! Menunggu verifikasi Kaprodi.`, 'success');
      form.reset();
      inputName.value = '';
      inputProgram.value = '';
      if (customTypeContainer) customTypeContainer.classList.add('d-none');
      if (customSubtypeContainer) customSubtypeContainer.classList.add('d-none');
      currentUploadedFiles = [];
      if (evidencePreview) evidencePreview.innerHTML = '';
      triggerAutoSystemDetermination();

      updateRoleUI();
      if (activeRole === 'mahasiswa') {
        showPage('riwayat-saya');
      } else {
        showPage('buat-laporan');
      }
    });
  }

  // --- Student History View ---
  function renderStudentHistoryOptions() {
    const select = document.getElementById('selectStudentHistory');
    if (!select) return;

    const students = getStoredStudents();
    select.innerHTML = '<option value="">-- Pilih Mahasiswa --</option>';
    students.forEach(s => {
      select.innerHTML += `<option value="${s.nim}">${s.nim} - ${s.name} (${s.program})</option>`;
    });

    select.onchange = (e) => {
      const nim = e.target.value;
      renderStudentHistoryDetails(nim);
    };
  }

  // --- Quick Student Lookup on Dashboard ---
  window.populateQuickStudentLookupOptions = function () {
    const select = document.getElementById('quickStudentLookupSelect');
    if (!select) return;

    const students = getStoredStudents();
    select.innerHTML = '<option value="">-- Pilih / Cari Mahasiswa (NIM) --</option>';
    students.forEach(s => {
      const pts = calculateStudentTotalPoints(s.nim);
      select.innerHTML += `<option value="${s.nim}">${s.nim} - ${s.name} (${s.program}) [${pts} Poin]</option>`;
    });
  };

  window.handleQuickStudentLookup = function (nim) {
    const resultDiv = document.getElementById('quickStudentLookupResult');
    if (!resultDiv) return;

    if (!nim) {
      resultDiv.innerHTML = `
        <div class="p-2 bg-light rounded text-center text-muted small">
          Pilih mahasiswa pada menu di samping untuk melihat akumulasi poin & status eskalasi.
        </div>
      `;
      return;
    }

    const students = getStoredStudents();
    const s = students.find(item => item.nim === nim);
    const totalPoints = calculateStudentTotalPoints(nim);
    const isOver = totalPoints >= THRESHOLD_POINTS;

    resultDiv.innerHTML = `
      <div class="p-3 bg-white rounded border shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="font-weight-bold text-dark mb-0">${s ? s.name : 'Mahasiswa'} (${nim})</h6>
            <div class="small text-muted">${s ? s.program : '-'}</div>
          </div>
          <div class="text-right">
            <span class="badge ${isOver ? 'badge-danger' : 'badge-primary'} p-2 font-weight-bold">
              ${totalPoints} / 100 Poin
            </span>
          </div>
        </div>
        <div class="progress mt-2" style="height: 8px; border-radius: 10px;">
          <div class="progress-bar ${isOver ? 'bg-danger' : totalPoints >= 50 ? 'bg-warning' : 'bg-success'}" style="width: ${Math.min(100, (totalPoints / THRESHOLD_POINTS) * 100)}%;"></div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
          <small class="${isOver ? 'text-danger font-weight-bold' : 'text-muted'}">
            ${isOver ? '<i class="fas fa-exclamation-triangle mr-1"></i> MEMENUHI SYARAT ESKALASI KEMAHASISWAAN' : 'Status: Aman / Dipantau Prodi'}
          </small>
          <button class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold px-3 py-1" onclick="showPage('riwayat'); setTimeout(() => { const sel = document.getElementById('selectStudentHistory'); if(sel) { sel.value = '${nim}'; renderStudentHistoryDetails('${nim}'); } }, 200);">
            Detail Rekam Jejak <i class="fas fa-arrow-right ml-1"></i>
          </button>
        </div>
      </div>
    `;
  };

  // --- Student History View ---
  function renderStudentHistoryOptions() {
    const select = document.getElementById('selectStudentHistory');
    if (!select) return;

    const students = getStoredStudents();
    select.innerHTML = '<option value="">-- Pilih Mahasiswa --</option>';
    students.forEach(s => {
      const totalPoints = calculateStudentTotalPoints(s.nim);
      const isOver = totalPoints >= THRESHOLD_POINTS;
      select.innerHTML += `<option value="${s.nim}">${s.nim} - ${s.name} (${s.program}) ${isOver ? '⚠️ [100+ POIN]' : `[${totalPoints} Poin]`}</option>`;
    });

    select.onchange = (e) => {
      const nim = e.target.value;
      renderStudentHistoryDetails(nim);
    };
  }

  function renderStudentHistoryDetails(nim) {
    const container = document.getElementById('studentHistoryResult');
    if (!container) return;

    if (!nim) {
      container.innerHTML = `
        <div class="alert alert-light border text-center py-5 text-muted rounded-lg shadow-sm">
          <i class="fas fa-search-plus text-primary fa-3x mb-3 d-block"></i>
          <h6 class="font-weight-bold text-dark mb-1">Pencarian Rekam Jejak Mahasiswa</h6>
          <p class="small text-muted mb-0">Silakan pilih nama atau NIM mahasiswa dari menu di atas untuk menampilkan profil dan riwayat akumulasi poin.</p>
        </div>
      `;
      return;
    }

    const students = getStoredStudents();
    const student = students.find(s => s.nim === nim);
    const reports = getStoredReports().filter(r => r.nim === nim);
    const totalPoints = calculateStudentTotalPoints(nim);
    const isOverThreshold = totalPoints >= THRESHOLD_POINTS;
    const pct = Math.min(100, Math.round((totalPoints / THRESHOLD_POINTS) * 100));

    let statusColorClass = 'text-primary';
    let progressBg = 'bg-primary';
    let alertBanner = '';

    if (totalPoints >= 50 && totalPoints < 100) {
      statusColorClass = 'text-warning';
      progressBg = 'bg-warning';
      alertBanner = `
        <div class="alert alert-warning border-warning p-3 mt-3 mb-0 rounded-lg">
          <div class="d-flex align-items-center font-weight-bold text-dark">
            <i class="fas fa-exclamation-triangle text-warning fa-lg mr-2"></i>
            PERINGATAN POIN TINGGI: Akumulasi poin mahasiswa mencapai ${totalPoints} Poin. Dipantau ketat oleh Kaprodi.
          </div>
        </div>
      `;
    } else if (totalPoints >= 100) {
      statusColorClass = 'text-danger';
      progressBg = 'bg-danger';
      alertBanner = `
        <div class="alert alert-danger border-danger p-3 mt-3 mb-0 rounded-lg shadow-sm">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
              <div class="font-weight-bold text-danger h6 mb-1">
                <i class="fas fa-exclamation-triangle fa-lg mr-2"></i> PERINGATAN AMBANG BATAS: AKUMULASI POIN ≥ 100 POIN!
              </div>
              <p class="small text-danger mb-0">Total akumulasi poin terverifikasi telah melebihi batas 100 Poin. Mahasiswa wajib diteruskan ke Bagian Kemahasiswaan.</p>
            </div>
            <button class="btn btn-danger font-weight-bold px-3 rounded-pill kaprodi-only" onclick="showPage('verifikasi-kaprodi')">
              <i class="fas fa-paper-plane mr-1"></i> Buka Antrean Eskalasi Kaprodi
            </button>
          </div>
        </div>
      `;
    }

    let html = `
      <!-- Student Profile Card -->
      <div class="card card-custom mb-4 border-top-primary shadow-sm">
        <div class="card-body p-4">
          <div class="row align-items-center">
            
            <div class="col-md-7 border-right-md mb-3 mb-md-0">
              <div class="d-flex align-items-center">
                <div class="avatar-circle bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3 shadow-sm" style="width:56px; height:56px; font-size:1.5rem;">
                  <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                  <h4 class="font-weight-bold text-dark mb-1">${student ? student.name : 'Mahasiswa'}</h4>
                  <div class="text-muted small">
                    NIM: <strong class="text-dark">${nim}</strong> | Prodi: <span class="text-dark font-weight-bold">${student ? student.program : '-'}</span> (Angkatan ${student ? student.angkatan : '-'})
                  </div>
                  <div class="mt-2">
                    <span class="badge badge-success font-weight-bold px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Status: ${student ? student.status : 'Aktif'}</span>
                    <span class="badge badge-light border font-weight-bold px-2 py-1 ml-1 text-dark">IPK: ${student ? student.ipk : '3.50'}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-5 text-center text-md-right">
              <div class="p-3 bg-light rounded-lg border">
                <div class="text-xs text-uppercase font-weight-bold text-muted mb-1">Akumulasi Poin Pelanggaran Active</div>
                <div class="h2 mb-1 font-weight-bold ${statusColorClass}">${totalPoints} <span class="h5 font-weight-normal text-muted">/ 100 Poin</span></div>
                
                <div class="progress mb-1" style="height: 10px; border-radius: 10px;">
                  <div class="progress-bar ${progressBg} progress-bar-striped progress-bar-animated" role="progressbar" style="width: ${pct}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-xs text-muted font-weight-bold">
                  <span>0 Poin</span>
                  <span>Batas Eskalasi: 100 Poin</span>
                </div>
              </div>
            </div>

          </div>

          ${alertBanner}

        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-history mr-2 text-primary"></i>Riwayat Catatan Pelanggaran (${reports.length} Laporan)</h6>
        <button class="btn btn-sm btn-outline-primary" onclick="exportReportsCSV()">
          <i class="fas fa-download mr-1"></i> Cetak Rekap
        </button>
      </div>
    `;

    if (reports.length === 0) {
      html += `
        <div class="alert alert-success border text-center py-4 rounded-lg shadow-sm">
          <i class="fas fa-shield-alt fa-3x d-block mb-2 text-success"></i>
          <h6 class="font-weight-bold text-dark">Tidak Ada Catatan Pelanggaran</h6>
          <p class="small text-muted mb-0">Mahasiswa ini memiliki rekam jejak bersih (0 Poin Pelanggaran).</p>
        </div>
      `;
    } else {
      reports.forEach(r => {
        html += `
          <div class="card card-custom mb-3 shadow-sm">
            <div class="card-body p-3.5">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <span class="badge badge-primary font-weight-bold mr-1">${r.id}</span>
                  ${getStatusBadgeHTML(r.status)}
                  <span class="badge badge-warning text-dark font-weight-bold ml-1">${r.points || 0} Poin</span>
                </div>
                <div class="small text-muted"><i class="far fa-calendar-alt mr-1"></i> ${r.date} ${r.time || ''}</div>
              </div>
              <h6 class="font-weight-bold text-dark mb-1">${r.typeName} <span class="text-muted font-weight-normal">(${r.subtype})</span></h6>
              <div class="small text-muted mb-2"><i class="fas fa-user-edit mr-1"></i> Pelapor: <strong>${r.reporterRole}</strong> (${r.reporterName})</div>
              
              <div class="small text-dark bg-light p-2.5 rounded border mb-2" style="white-space: pre-wrap;">
                <strong>Kronologi:</strong> "${r.chronology}"
              </div>

              ${r.sanksi ? `
                <div class="alert alert-purple p-2.5 small mb-2 font-weight-bold rounded" style="background:#f3e8ff; color:#6d28d9; border: 1px solid #e9d5ff;">
                  <i class="fas fa-gavel mr-1"></i> Sanksi Resmi Kampus: ${r.sanksi}
                </div>
              ` : ''}

              <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold px-3" onclick="openDetailModal('${r.id}')">
                  <i class="fas fa-eye mr-1"></i> Detail Lengkap
                </button>
              </div>
            </div>
          </div>
        `;
      });
    }

    container.innerHTML = html;
  }

  // --- Export CSV & Print Functions ---
  window.exportReportsCSV = function () {
    const reports = getStoredReports();
    let csv = 'No. Laporan,NIM,Nama,Prodi,Jenis,Subjenis,Tingkat,Poin,Tanggal,Status,Sanksi\n';
    reports.forEach(r => {
      csv += `"${r.id}","${r.nim}","${r.name}","${r.program}","${r.typeName}","${r.subtype}","${r.severity}","${r.points || 0}","${r.date}","${r.status}","${r.sanksi || '-'}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.setAttribute('download', `Rekap_Pelanggaran_Mahasiswa_${new Date().toISOString().substring(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    sipmaToast('File CSV rekapitulasi poin berhasil diunduh!', 'success');
  };

  window.printReport = function (reportId) {
    openDetailModal(reportId);
    setTimeout(() => {
      window.print();
    }, 400);
  };

  window.resetDemoData = function () {
    if (confirm('Apakah Anda yakin ingin menghapus seluruh data laporan (mengosongkan database)?')) {
      try {
        localStorage.setItem('sipma_reports', JSON.stringify([]));
        localStorage.setItem('sipma_students', JSON.stringify(INITIAL_STUDENTS));
        localStorage.setItem('sipma_active_role', 'mahasiswa');
      } catch (e) {}
      alert('Seluruh data laporan telah berhasil dikosongkan!');
      location.reload();
    }
  };

  // --- Document Ready Initialization & Event Delegation ---
  document.addEventListener('DOMContentLoaded', () => {
    // Event delegation for role switcher buttons
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-role]');
      if (btn) {
        e.preventDefault();
        const role = btn.getAttribute('data-role');
        if (role && window.switchRole) {
          window.switchRole(role);
        }
      }
    });

    // Filter controls
    const filterStatusSelect = document.getElementById('filterStatusSelect');
    const filterSearchInput = document.getElementById('filterSearchInput');

    if (filterStatusSelect) filterStatusSelect.addEventListener('change', renderReportsTable);
    if (filterSearchInput) filterSearchInput.addEventListener('keyup', renderReportsTable);

    // Form Event Listeners
    const sanksiForm = document.getElementById('sanksiForm');
    if (sanksiForm) sanksiForm.addEventListener('submit', window.submitSanksiForm);

    const kaprodiVerifyForm = document.getElementById('kaprodiVerifyForm');
    if (kaprodiVerifyForm) kaprodiVerifyForm.addEventListener('submit', window.submitKaprodiVerifyForm);

    const editPointForm = document.getElementById('editPointForm');
    if (editPointForm) editPointForm.addEventListener('submit', window.submitEditPointForm);

    setupCreateReportForm();
    updateRoleUI();

    const activeRole = getActiveRole();
    if (activeRole === 'mahasiswa') {
      showPage('dashboard-mahasiswa');
    } else {
      showPage('dashboard');
    }
  });

})();
