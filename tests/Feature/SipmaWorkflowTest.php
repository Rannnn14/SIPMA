<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ViolationReport;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

class SipmaWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->createTestFixtures();
    }

    private function createTestFixtures(): void
    {
        $now = Carbon::now();
        $student = Student::create([
            'nim' => 'TEST0001',
            'name' => 'Mahasiswa Test',
            'study_program' => 'Informatika',
            'entry_year' => 2024,
        ]);

        $mahasiswaId = DB::table('users')->insertGetId([
            'name' => 'Mahasiswa Test', 'email' => 'student@test.invalid',
            'nim' => 'TEST0001', 'username' => 'TEST0001',
            'password' => Hash::make('password'), 'role' => 'mahasiswa',
            'department' => 'Informatika', 'faculty' => 'FITK',
            'student_id' => $student->id, 'created_at' => $now, 'updated_at' => $now,
        ]);

        foreach ([
            ['Admin Test', 'kemahasiswaan@test.invalid', 'kemahasiswaan', 'kemahasiswaan', null],
            ['Kajur Test', 'kajur@sipma.test', 'kajur_if', 'kajur', 'Informatika'],
            ['Dekan Test', 'dekan@test.invalid', 'dekan', 'dekan', null],
            ['Warek Test', 'warek@test.invalid', 'warek', 'warek', null],
        ] as [$name, $email, $username, $role, $department]) {
            DB::table('users')->insert([
                'name' => $name, 'email' => $email, 'username' => $username,
                'password' => Hash::make('password'), 'role' => $role,
                'department' => $department, 'faculty' => null, 'student_id' => null,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $pendingTypeId = DB::table('violation_types')->value('id');
        DB::table('violation_reports')->insert([
            'report_number' => 'TEST-REPORT-001', 'student_id' => null,
            'reported_name' => 'Mahasiswa Terlapor Test', 'reported_nim' => 'TEST0002',
            'reported_faculty' => 'FITK', 'reported_department' => 'Informatika',
            'target_department' => 'Informatika', 'violation_type_id' => $pendingTypeId,
            'reporter_id' => $mahasiswaId, 'severity' => 'Berat',
            'incident_date' => $now->toDateString(), 'incident_time' => '10:30',
            'incident_location' => 'Lokasi Test', 'chronology' => 'Laporan fixture untuk pengujian alur.',
            'status' => 'pending', 'academic_year' => '2026/2027', 'semester' => 'Ganjil',
            'created_at' => $now, 'updated_at' => $now,
        ]);
    }

    public function test_local_mahasiswa_demo_account_is_not_seeded(): void
    {
        $this->assertDatabaseMissing('users', ['email' => 'mahasiswa@sipma.test']);
        $this->assertDatabaseCount('students', 1);
    }

    public function test_login_with_username_and_redirects_to_kemahasiswaan_dashboard(): void
    {
        // Login using username
        $response = $this->post(route('login'), [
            'login' => 'kemahasiswaan',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('kemahasiswaan.dashboard'));
        $this->assertAuthenticated();

        $dashboard = $this->get(route('kemahasiswaan.dashboard'));
        $dashboard->assertStatus(200);
    }

    public function test_login_with_email_and_redirects_to_kajur_dashboard(): void
    {
        // Login using email
        $response = $this->post(route('login'), [
            'login' => 'kajur@sipma.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('kajur.dashboard'));
        $this->assertAuthenticated();

        $dashboard = $this->get(route('kajur.dashboard'));
        $dashboard->assertStatus(200);
    }

    public function test_login_with_username_dekan_and_warek(): void
    {
        // Dekan
        $this->post(route('login'), [
            'login' => 'dekan',
            'password' => 'password',
        ])->assertRedirect(route('dekan.dashboard'));

        $this->get(route('dekan.dashboard'))->assertStatus(200);

        // Warek
        $this->post(route('login'), [
            'login' => 'warek',
            'password' => 'password',
        ])->assertRedirect(route('warek.dashboard'));

        $this->get(route('warek.dashboard'))->assertStatus(200);
    }

    public function test_role_middleware_blocks_unauthorized_dashboard_access(): void
    {
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        $this->actingAs($mahasiswa);

        // Mahasiswa attempts to access Kajur dashboard
        $response = $this->get(route('kajur.dashboard'));
        $response->assertRedirect(route('mahasiswa.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_mahasiswa_can_submit_report(): void
    {
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        $this->actingAs($mahasiswa);

        $response = $this->post(route('reports.store'), [
            'reported_name' => 'Mahasiswa Terlapor Test',
            'reported_nim' => 'TEST0002',
            'reported_department' => 'Informatika',
            'incident_date' => '2026-10-15',
            'incident_time' => '09:00',
            'incident_location' => 'Lokasi Test',
            'severity' => 'Sedang',
            'chronology' => 'Laporan uji untuk memeriksa alur pengiriman.',
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertDatabaseHas('violation_reports', [
            'reported_name' => 'Mahasiswa Terlapor Test',
            'status' => 'pending',
            'severity' => 'Sedang',
        ]);
    }

    public function test_mahasiswa_cannot_view_report_history(): void
    {
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        $this->actingAs($mahasiswa);

        // Accessing index should redirect to mahasiswa dashboard
        $response = $this->get(route('reports.index'));
        $response->assertRedirect(route('mahasiswa.dashboard'));

        // Accessing report show should redirect to mahasiswa dashboard
        $detailResponse = $this->get(route('reports.show', 1));
        $detailResponse->assertRedirect(route('mahasiswa.dashboard'));
    }

    public function test_kemahasiswaan_can_correct_and_approve_report(): void
    {
        $kemahasiswaan = User::where('role', 'kemahasiswaan')->first();
        $this->actingAs($kemahasiswaan);

        $report = ViolationReport::where('status', 'pending')->first();
        $this->assertNotNull($report);

        // Koreksi
        $koreksiResponse = $this->post(route('reports.koreksi', $report->id), [
            'reported_name' => 'Mahasiswa Terlapor Test Dikoreksi',
            'reported_nim' => 'TEST0002',
            'reported_department' => 'Informatika',
            'severity' => 'Berat',
            'incident_location' => 'Laboratorium Komputer 3, Gedung B',
            'chronology' => $report->chronology,
        ]);
        $koreksiResponse->assertSessionHas('success');

        // Approve and forward to Kajur
        $approveResponse = $this->post(route('reports.approve', $report->id), [
            'target_department' => 'Informatika',
        ]);
        $approveResponse->assertSessionHas('success');

        $this->assertDatabaseHas('violation_reports', [
            'id' => $report->id,
            'status' => 'dikonfirmasi',
            'target_department' => 'Informatika',
        ]);
    }

    public function test_kajur_can_process_and_resolve_case(): void
    {
        $kajur = User::where('role', 'kajur')->where('department', 'Informatika')->first();
        $this->actingAs($kajur);

        // Find a report in Informatika
        $report = ViolationReport::where('target_department', 'Informatika')
            ->whereIn('status', ['dikonfirmasi', 'diproses'])
            ->first();

        $this->assertNotNull($report);
        $response = $this->post(route('reports.tindakLanjut', $report->id), [
            'status' => 'selesai',
            'sanction_type' => 'Surat Peringatan (SP 1)',
            'kajur_notes' => 'Telah dilakukan pemanggilan dan sidang etika jurusan.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('violation_reports', [
            'id' => $report->id,
            'status' => 'selesai',
            'sanction_type' => 'Surat Peringatan (SP 1)',
        ]);
    }

    public function test_export_endpoints(): void
    {
        $warek = User::where('role', 'warek')->first();
        $this->actingAs($warek);

        $excelResponse = $this->get(route('reports.export.excel'));
        $excelResponse->assertStatus(200);

        $pdfResponse = $this->get(route('reports.export.pdf'));
        $pdfResponse->assertStatus(200);
    }
}
