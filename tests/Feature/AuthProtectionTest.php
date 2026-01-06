<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthProtectionTest extends TestCase
{
    public function test_guest_cannot_access_tasks_page(): void
    {
        $this->get('/tasks')
            ->assertRedirect('/login');
    }
}
