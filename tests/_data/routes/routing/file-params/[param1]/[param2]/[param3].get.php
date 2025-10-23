<?php /** @var Symfony\Component\HttpFoundation\Request $request */ ?>

<h1><?= $request->get('param1') ?> <?= $request->get('param2') ?> <?= $request->get('param3') ?></h1>
