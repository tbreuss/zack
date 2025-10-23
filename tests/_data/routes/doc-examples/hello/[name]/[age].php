<?php /** @var Symfony\Component\HttpFoundation\Request $request */ ?>

<h1>Hello <?= $request->get('name') ?>! You are <?= $request->get('age') ?> years old.</h1>
