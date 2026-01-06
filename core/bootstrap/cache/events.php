<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Failed' => 
    array (
      0 => 'App\\Listeners\\OnLoginFailedBlockIp',
    ),
    'Illuminate\\Auth\\Events\\Login' => 
    array (
      0 => 'App\\Listeners\\OnLoginSuccessResetCounter',
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Failed' => 
    array (
      0 => 'App\\Listeners\\OnLoginFailedBlockIp@handle',
    ),
    'Illuminate\\Auth\\Events\\Login' => 
    array (
      0 => 'App\\Listeners\\OnLoginSuccessResetCounter@handle',
    ),
  ),
);