<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores priority due date and completion timestamp when a task is completed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('dashboard.tasks.save'), [
        'name' => 'Ship billing updates',
        'description' => 'Finalize the lightweight improvements for the billing flow.',
        'status' => Task::STATUS_COMPLETED,
        'priority' => Task::PRIORITY_HIGH,
        'due_date' => '2026-04-15',
    ]);

    $response->assertRedirect(route('dashboard.tasks.index'));

    $task = Task::first();

    expect($task)
        ->name->toBe('Ship billing updates')
        ->priority->toBe(Task::PRIORITY_HIGH)
        ->status->toBe(Task::STATUS_COMPLETED)
        ->due_date->format('Y-m-d')->toBe('2026-04-15')
        ->completed_at->not->toBeNull();
});

it('filters tasks by search without leaking unrelated records through or clauses', function () {
    $user = User::factory()->create();

    Task::create([
        'name' => 'Website polish',
        'description' => 'Homepage updates for launch.',
        'status' => Task::STATUS_PENDING,
        'priority' => Task::PRIORITY_MEDIUM,
    ]);

    Task::create([
        'name' => 'Mobile QA',
        'description' => 'Verify responsive launch checklist.',
        'status' => Task::STATUS_COMPLETED,
        'priority' => Task::PRIORITY_LOW,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.tasks.index', [
        'search' => 'launch',
        'status' => Task::STATUS_PENDING,
    ]));

    $response->assertOk();
    $response->assertSee('Website polish');
    $response->assertDontSee('Mobile QA');
});

it('validates required fields when saving a task', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('dashboard.tasks.save'), [
        'name' => '', // Required
        'status' => 'invalid-status', // Must be valid
    ]);

    $response->assertSessionHasErrors(['name', 'status']);
});

it('can delete a task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['name' => 'Task to be deleted']);

    $response = $this->actingAs($user)->delete(route('dashboard.tasks.destroy', $task));

    $response->assertRedirect(route('dashboard.tasks.index'));
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('sorts tasks by due date', function () {
    $user = User::factory()->create();

    Task::factory()->create(['name' => 'Due Later', 'due_date' => '2026-12-31']);
    Task::factory()->create(['name' => 'Due Sooner', 'due_date' => '2026-01-01']);

    $response = $this->actingAs($user)->get(route('dashboard.tasks.index', ['sort' => 'due_soon']));

    $response->assertOk();
    $response->assertSeeInOrder(['Due Sooner', 'Due Later']);
});
