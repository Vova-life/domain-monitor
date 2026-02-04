<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    /**
     * Список доменів користувача
     */
    public function index()
    {
        // Отримуємо домени тільки поточного користувача
        $domains = Auth::user()->domains()->latest()->get();
        return view('domains.index', compact('domains'));
    }

    /**
     * Форма створення
     */
    public function create()
    {
        return view('domains.create');
    }

    /**
     * Збереження домену в базу
     */
    public function store(Request $request)
    {
        // 1. Валідація даних
        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'check_interval' => 'required|integer|min:10|max:86400', // Мін 10 сек
            'timeout' => 'required|integer|min:1|max:30',
            'method' => 'required|in:GET,HEAD',
        ]);

        // 2. Створення запису (прив'язка до юзера автоматична через релейшн)
        $request->user()->domains()->create($validated);

        return redirect()->route('domains.index')
            ->with('status', 'Domain added successfully!');
    }

    /**
     * Сторінка перегляду одного домену (разом з логами)
     */
    public function show(Domain $domain)
    {
        // Перевірка, що користувач дивиться свій домен
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        // Завантажуємо логи цього домену (останні 50, щоб не грузити сторінку)
        $logs = $domain->checkLogs()->latest()->limit(50)->get();

        return view('domains.show', compact('domain', 'logs'));
    }

    /**
     * Видалення домену
     */
    public function destroy(Domain $domain)
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $domain->delete();

        return redirect()->route('domains.index')
            ->with('status', 'Domain deleted!');
    }
}
