<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_email_is_required_for_login()
    {
        User::create([
            'name' => 'test',
            'email' => 'login-test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_password_is_required_for_login()
    {
        User::create([
            'name' => 'test',
            'email' => 'login@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /*
    public function test_login_fails_with_invalid_credentials()
    {
        User::create([
            'name' => 'test',
            'email' => 'login2@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/      login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $response->assertSee('ログイン情報が登録されていません',
        );
    }
    */    

    public function test_email_is_required_for_admin_login()
        {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin-test@test.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }    

    public function test_password_is_required_for_admin_login()
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin-test2@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('admin/login', [
            'email' => 'admin-test@test.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    
    }
    /*
    public function test_admin_login_fails_with_invalid_credentials()
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->followingRedirects()->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $response->assertSee(
            'ログイン情報が登録されていません');
    }
    */
}
