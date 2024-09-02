<?php

test('validate main route', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});
