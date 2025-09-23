<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
    web: [
        __DIR__.'/../routes/web.php',
        __DIR__.'/../routes/core.php', 
        __DIR__.'/../routes/admin.php',
        __DIR__.'/../routes/pos.php',
        __DIR__.'/../routes/public.php',
    ],
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'perm'      => \App\Http\Middleware\CheckPermission::class,
            'firewall'  => \App\Http\Middleware\PosFirewall::class, //  ফায়ারওয়াল alias
            'branchscope' => \App\Http\Middleware\EnsureBranchScope::class, // ব্রাঞ্চ স্কোপ alias
        ]);
        // গ্লোবালি চালাতে :
        // $middleware->web(append: [\App\Http\Middleware\PosFirewall::class]);
    })
    ->withProviders([
            \App\Providers\EventServiceProvider::class, // এখানে প্রোভাইডার রেজিস্টার
        ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
