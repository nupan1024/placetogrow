<?php

test('get token', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
