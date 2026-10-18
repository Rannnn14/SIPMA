<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ViolationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

class SipmaWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_with_nim_and_redirects_to_mahasiswa_dashboard(): void
    {
        // Login using NIM
        $response = $this->post(route('login'), [
            'login' => '2024001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticated();

        $dashboard = $this->get(route('mahasiswa.dashboard'));
        $dashboard->assertStatus(200);
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
            'reported_name' => 'Doni Kusuma',
            'reported_nim' => '2024004',
            'reported_department' => 'Teknik Elektro',
            'incident_date' => '2026-10-15',
            'incident_time' => '09:00',
            'incident_location' => 'Laboratorium Listrik',
            'severity' => 'Sedang',
            'chronology' => 'Mahasiswa merusak fasilitas kabel praktikum saat asistensi.',
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertDatabaseHas('violation_reports', [
            'reported_name' => 'Doni Kusuma',
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
            'reported_name' => 'Budi Santoso Dikoreksi',
            'reported_nim' => '2024002',
            'reported_department' => 'Teknik Informatika',
            'severity' => 'Berat',
            'points' => 75,
            'incident_location' => 'Laboratorium Komputer 3, Gedung B',
            'chronology' => $report->chronology,
        ]);
        $koreksiResponse->assertSessionHas('success');

        // Approve and forward to Kajur
        $approveResponse = $this->post(route('reports.approve', $report->id), [
            'target_department' => 'Teknik Informatika',
            'points' => 75,
        ]);
        $approveResponse->assertSessionHas('success');

        $this->assertDatabaseHas('violation_reports', [
            'id' => $report->id,
            'status' => 'dikonfirmasi',
            'target_department' => 'Teknik Informatika',
        ]);
    }

    public function test_kajur_can_process_and_resolve_case(): void
    {
        $kajur = User::where('role', 'kajur')->where('department', 'Teknik Informatika')->first();
        $this->actingAs($kajur);

        // Find a report in Teknik Informatika
        $report = ViolationReport::where('target_department', 'Teknik Informatika')
            ->whereIn('status', ['dikonfirmasi', 'diproses'])
            ->first();

        if ($report) {
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
