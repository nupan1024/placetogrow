<?php

test('validate main route', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

