<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckDomains extends Command
{
    /**
     * Ім'я команди для запуску в терміналі
     */
    protected $signature = 'domains:check';

    /**
     * Опис команди
     */
    protected $description = 'Перевіряє доступність доменів та зберігає логи';

    public function handle()
    {
        $this->info('Починаємо перевірку доменів...');

        // 1. Отримуємо всі домени
        // (Для оптимізації можна використовувати cursor(), якщо доменів дуже багато)
        $domains = Domain::all();

        foreach ($domains as $domain) {
            // 2. Перевіряємо, чи настав час для перевірки цього домену
            // Якщо last_checked_at пустий АБО пройшло більше часу ніж check_interval
            $shouldCheck = is_null($domain->last_checked_at) ||
                $domain->last_checked_at->diffInSeconds(now()) >= $domain->check_interval;

            if ($shouldCheck) {
                $this->performCheck($domain);
            }
        }

        $this->info('Перевірку завершено.');
    }

    private function performCheck(Domain $domain)
    {
        $startTime = microtime(true);
        $statusCode = null;
        $errorMessage = null;

        try {
            // Виконуємо запит
            // Використовуємо метод (GET/HEAD), URL та Таймаут з налаштувань домену
            $response = Http::timeout($domain->timeout)
                ->send($domain->method, $domain->url);

            $statusCode = $response->status();

        } catch (\Exception $e) {
            // Якщо сталася помилка (наприклад, DNS не знайдено або таймаут)
            $statusCode = 0; // Або null, залежно від логіки
            $errorMessage = $e->getMessage();
        }

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 4); // Час відповіді в секундах

        // 3. Зберігаємо лог
        $domain->checkLogs()->create([
            'status_code' => $statusCode,
            'response_time' => $duration,
            'error_message' => $errorMessage ? substr($errorMessage, 0, 65535) : null, // Обрізаємо якщо дуже довга
        ]);

        // 4. Оновлюємо час останньої перевірки домену
        $domain->update([
            'last_checked_at' => now(),
        ]);

        $this->line("Checked: {$domain->url} | Status: {$statusCode} | Time: {$duration}s");
    }
}
