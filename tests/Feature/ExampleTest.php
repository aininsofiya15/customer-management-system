<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The home page redirects to the customer list.
     */
    public function test_home_page_redirects_to_customers(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/customers');
    }
}
