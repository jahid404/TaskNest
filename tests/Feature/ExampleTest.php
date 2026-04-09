<?php

test('guests are redirected to the login page from the root route', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
