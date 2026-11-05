<?php

namespace Tests\Feature;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'email' => 'dummy@sweetdreams.test',
            'password' => Hash::make('rahasia123'),
        ]);
    }

    /** Minta OTP lalu kembalikan kode yang terkirim di email. */
    protected function requestOtp(User $admin, string $newEmail): string
    {
        $code = null;
        $this->actingAs($admin)->post('/admin/profile/email/request-otp', [
            'new_email' => $newEmail,
            'current_password' => 'rahasia123',
        ])->assertRedirect('/admin/profile')->assertSessionHasNoErrors();

        Mail::assertSent(SendOtpMail::class, function (SendOtpMail $mail) use ($newEmail, &$code) {
            $code = $mail->otpCode;
            return $mail->hasTo(strtolower($newEmail));   // email disimpan & dikirim dalam huruf kecil
        });

        return $code;
    }

    public function test_profile_page_requires_admin(): void
    {
        $this->get('/admin/profile')->assertRedirect();
        $this->actingAs(User::factory()->create(['role' => 'customer']))->get('/admin/profile')->assertRedirect();
        $this->actingAs($this->admin())->get('/admin/profile')->assertOk()->assertSee('Ganti Email')->assertSee('Ganti Password');
    }

    public function test_otp_is_sent_to_the_new_email_and_changes_email(): void
    {
        Mail::fake();
        $admin = $this->admin();

        $code = $this->requestOtp($admin, 'Admin.Baru@Gmail.com');
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        Mail::assertNotSent(SendOtpMail::class, fn ($m) => $m->hasTo('dummy@sweetdreams.test'));
        $this->assertSame('dummy@sweetdreams.test', $admin->fresh()->email);   // belum berubah sebelum verifikasi

        $this->actingAs($admin)->post('/admin/profile/email/verify-otp', ['otp_code' => $code])
            ->assertRedirect('/admin/profile')->assertSessionHas('success');

        $this->assertSame('admin.baru@gmail.com', $admin->fresh()->email);
        $this->assertNull(Cache::get("admin-email-otp:{$admin->id}"));
    }

    public function test_request_otp_validates_password_and_email(): void
    {
        Mail::fake();
        $admin = $this->admin();
        User::factory()->create(['email' => 'dipakai@example.com']);

        $this->actingAs($admin)->post('/admin/profile/email/request-otp', ['new_email' => 'baru@example.com', 'current_password' => 'salah'])
            ->assertSessionHasErrorsIn('email', 'current_password');
        $this->actingAs($admin)->post('/admin/profile/email/request-otp', ['new_email' => 'dummy@sweetdreams.test', 'current_password' => 'rahasia123'])
            ->assertSessionHasErrorsIn('email', 'new_email');
        $this->actingAs($admin)->post('/admin/profile/email/request-otp', ['new_email' => 'dipakai@example.com', 'current_password' => 'rahasia123'])
            ->assertSessionHasErrorsIn('email', 'new_email');
        $this->actingAs($admin)->post('/admin/profile/email/request-otp', ['new_email' => 'bukan-email', 'current_password' => 'rahasia123'])
            ->assertSessionHasErrorsIn('email', 'new_email');

        Mail::assertNothingSent();
    }

    public function test_wrong_or_expired_otp_does_not_change_email(): void
    {
        Mail::fake();
        $admin = $this->admin();
        $code = $this->requestOtp($admin, 'baru@example.com');
        $wrong = $code === '000000' ? '111111' : '000000';

        // 5x salah -> kode dibuang
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($admin)->post('/admin/profile/email/verify-otp', ['otp_code' => $wrong])
                ->assertSessionHasErrorsIn('otp', 'otp_code');
        }
        $this->actingAs($admin)->post('/admin/profile/email/verify-otp', ['otp_code' => $code])
            ->assertSessionHasErrorsIn('otp', 'otp_code');
        $this->assertSame('dummy@sweetdreams.test', $admin->fresh()->email);

        // kode baru tapi sudah kedaluwarsa
        $this->travel(61)->seconds();
        $code = $this->requestOtp($admin, 'baru@example.com');
        $this->travel(11)->minutes();
        $this->actingAs($admin)->post('/admin/profile/email/verify-otp', ['otp_code' => $code])
            ->assertSessionHasErrorsIn('otp', 'otp_code');
        $this->assertSame('dummy@sweetdreams.test', $admin->fresh()->email);
    }

    public function test_change_password(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'salah', 'password' => 'PasswordBaru1!', 'password_confirmation' => 'PasswordBaru1!',
        ])->assertSessionHasErrorsIn('password', 'current_password');

        $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'rahasia123', 'password' => 'pendek', 'password_confirmation' => 'pendek',
        ])->assertSessionHasErrorsIn('password', 'password');

        $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'rahasia123', 'password' => 'PasswordBaru1!', 'password_confirmation' => 'beda',
        ])->assertSessionHasErrorsIn('password', 'password');

        $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'rahasia123', 'password' => 'PasswordBaru1!', 'password_confirmation' => 'PasswordBaru1!',
        ])->assertRedirect('/admin/profile')->assertSessionHas('success');

        $this->assertTrue(Hash::check('PasswordBaru1!', $admin->fresh()->password));
    }
}
