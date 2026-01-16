## Structure (read this when you’re lost)

This file exists so you can navigate quickly without “blind search”.

## Instructions

- **Namespace shape (how this repo is structured)**:
  - We group by Laravel layer first, then by domain area like `Core` / `Shared` (and `Marketing` when present).
  - Examples you’ll see in this codebase:
    - `App\Actions\Core\…`, `App\Actions\Shared\…`
    - `App\Http\Controllers\Core\…`, `App\Http\Responses\Core\…`, `App\Http\Middleware\Core\…`
    - `App\Models\Core\…`
    - `App\Livewire\Core\…`
    - `App\Filament\Core\…`