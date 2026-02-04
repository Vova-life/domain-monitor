<?php

use Illuminate\Support\Facades\Schedule;

// Запускати команду перевірки кожну хвилину
Schedule::command('domains:check')->everyMinute();
