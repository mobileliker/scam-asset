<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use WithoutMiddleware, DatabaseMigrations, DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    //public function testExample()
    //{
    //    $this->assertTrue(true);
    //}
    
    public function testAll()
    {
        $response = $this->get('/admin/user/all');
        $response->assertStatus(200);
    }
}
