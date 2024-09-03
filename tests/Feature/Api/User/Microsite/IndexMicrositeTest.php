<?php

test('list microsites everyone', function () {

    $response = $this->get('/api/microsites');

    $response->assertStatus(200);
});
