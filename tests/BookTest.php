<?php

use App\Models\User;

class BookTest extends TestCase
{
    public function testShouldGetAllBooks()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api')->get("api/books");
        $this->assertEquals(200, $this->response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'result' => [
                '*' => [
                    '_id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    public function testShouldCreatedBook()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api')->post("api/books", [
            'name' => 'Harry Potter',
        ]);
        $this->assertEquals(201, $this->response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'result' => [
                '_id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testShouldUpdatedBook()
    {
        $id = '60f28b0ceb22744a8f128df2';
        $user = User::factory()->create();
        $this->actingAs($user, 'api')->put("api/books/$id", [
            'name' => 'Manusia Setengah Salmon',
        ]);
        $this->assertEquals(200, $this->response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'result' => [
                '_id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testShouldDeletedBook()
    {
        // Change the based on actual ID
        $id = '60f391cc0f7a8b2d3d620cb4';
        $user = User::factory()->create();
        $this->actingAs($user, 'api')->delete("api/books/$id");
        $this->assertEquals(204, $this->response->status());
    }
}
