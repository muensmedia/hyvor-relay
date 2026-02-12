<?php

it('uses HyvorRelayHttp preset instead of direct Http facade in src', function () {
    $allowed = [
        realpath(__DIR__.'/../../src/Facades/HyvorRelayHttp.php'),
    ];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../../src')
    );

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getRealPath();

        if ($path === false || in_array($path, $allowed, true)) {
            continue;
        }

        $content = file_get_contents($path) ?: '';

        expect($content)
            ->not->toContain('Illuminate\\Support\\Facades\\Http');
    }
});

it('prevents stray HyvorRelay console http requests by default in tests', function () {
    expect(fn () => \Muensmedia\HyvorRelay\Actions\Console\Sends\SendEmailAction::run([
        'from' => 'app@example.test',
        'to' => 'john@example.test',
        'subject' => 'Hello',
        'body_text' => 'Hi',
    ]))->toThrow(\RuntimeException::class);
});
