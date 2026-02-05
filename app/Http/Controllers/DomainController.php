<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class DomainController extends Controller
{
    /**
     * Список доменів користувача
     */
    public function index()
    {
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
        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'check_interval' => 'required|integer|min:10|max:86400',
            'timeout' => 'required|integer|min:1|max:30',
            'method' => 'required|in:GET,HEAD',
        ]);

        $request->user()->domains()->create($validated);

        return redirect()->route('domains.index')
            ->with('status', 'Domain added successfully!');
    }

    /**
     * Сторінка перегляду одного домену (разом з логами)
     */
    public function show(Domain $domain)
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $logs = $domain->checkLogs()->latest()->limit(50)->get();

        return view('domains.show', compact('domain', 'logs'));
    }

    /**
     * Форма редагування
     */
    public function edit(Domain $domain)
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }
        return view('domains.edit', compact('domain'));
    }

    /**
     * Оновлення даних
     */
    public function update(Request $request, Domain $domain)
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'check_interval' => 'required|integer|min:10|max:86400',
            'timeout' => 'required|integer|min:1|max:30',
            'method' => 'required|in:GET,HEAD',
        ]);

        $domain->update($validated);

        return redirect()->route('domains.index')
            ->with('status', 'Domain updated successfully!');
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

    /**
     * 👇 РУЧНА ПЕРЕВІРКА ДОМЕНУ (FIX FOR DEMO) 👇
     */
    public function check(Domain $domain)
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        // Викликаємо Artisan-команду програмно
        Artisan::call('domains:check');

        return back()->with('status', 'Monitoring check completed!');
    }
}
