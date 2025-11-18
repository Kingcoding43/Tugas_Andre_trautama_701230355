<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testLoginFailed()
    {
        $result = loginFunction("emailygaksada@example.com", "passsalah");
        $this->assertEquals("gagal", $result);
    }

    public function testRegisterSuccess()
    {
        $email = "test" . rand(1,9999) . "@example.com";
        $result = registerFunction("Tester", $email, "password123");
        $this->assertEquals("berhasil", $result);
    }
}
