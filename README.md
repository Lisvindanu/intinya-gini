# INTINYA GINI - AI TL;DR Content Factory

> "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."

Platform otomatis berbasis AI untuk membuat script TL;DR untuk konten YouTube Shorts / Reels. Riset topik, ringkas, dan export dalam hitungan detik.

## Features

- **AI-Powered Script Generation**: Generate TL;DR scripts otomatis dengan OpenRouter AI
- **Multiple Export Formats**: Text, Markdown, dan SRT subtitle
- **Clean Architecture**: Domain-driven design untuk maintainability
- **Modern UI**: Dark theme dengan TailwindCSS dan Alpine.js
- **Database Logging**: Track semua generations untuk analytics

## Tech Stack

### Backend
- **Laravel 11** - PHP Framework
- **PHP 8.3+** - Language
- **SQLite** - Database (easily switchable to MySQL/PostgreSQL)

### Frontend
- **Blade Templates** - Server-side rendering
- **TailwindCSS v3** - Utility-first CSS
- **Alpine.js** - Lightweight JavaScript framework

### AI Integration
- **OpenRouter API** - Multi-model LLM access
- Default model: `meta-llama/llama-3.1-8b-instruct:free`

## Installation

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & npm
- OpenRouter API key (get one at [openrouter.ai](https://openrouter.ai))

### Setup Steps

1. **Clone & Install Dependencies**
```bash
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure OpenRouter API**
Edit `.env` and add your OpenRouter API key:
```env
OPENROUTER_API_KEY=sk-or-v1-your-api-key-here
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
OPENROUTER_MODEL=meta-llama/llama-3.1-8b-instruct:free
```

4. **Database Setup**
```bash
touch database/database.sqlite
php artisan migrate
```

5. **Build Assets**
```bash
npm run build
```

6. **Start Development Server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Usage

1. **Input Topic**: Masukkan topik yang ingin dibuat TL;DR-nya
2. **Choose Duration**: Pilih target durasi (30s atau 60s)
3. **Generate**: Klik tombol generate dan AI akan membuat script
4. **Review & Export**: Lihat hasil dan export dalam berbagai format

### Example Output

**Input**: "Kotlin Coroutines"

**Output**:
- **Hook**: "Masih bingung coroutines? Santai, TL;DR aja."
- **Script**: Full TL;DR content with motto
- **Key Points**: 3 poin inti dalam bullet points
- **Title**: "Coroutines Kotlin dalam 45 Detik"
- **Caption**: Social media ready caption

## Project Structure

```
app/
├── Domain/              # Business logic & entities
│   ├── Entities/        # Eloquent models
│   └── Services/        # Core services
├── Application/         # Use cases & DTOs
│   ├── Actions/         # Business actions
│   └── DTO/            # Data transfer objects
├── Infrastructure/      # External integrations
│   ├── AI/             # OpenRouter client
│   └── Repositories/   # Data access
└── Http/               # Web layer
    ├── Controllers/    # Route handlers
    └── Requests/       # Form validation
```

## Configuration

### AI Models

You can change the AI model in `.env`:

```env
# Free models
OPENROUTER_MODEL=meta-llama/llama-3.1-8b-instruct:free
OPENROUTER_MODEL=mistralai/mistral-7b-instruct:free

# Paid models (better quality)
OPENROUTER_MODEL=anthropic/claude-3-sonnet
OPENROUTER_MODEL=openai/gpt-4-turbo
```

### Database

Switch from SQLite to MySQL/PostgreSQL by updating `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intinya_gini
DB_USERNAME=root
DB_PASSWORD=
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Dashboard view |
| POST | `/generate` | Generate new TL;DR script |
| GET | `/scripts/{id}` | View script detail |
| GET | `/scripts/{id}/export/{format}` | Export script (text/markdown/srt) |
| DELETE | `/scripts/{id}` | Delete script |

## Development

### Run Development Server
```bash
php artisan serve
npm run dev
```

### Run Tests (coming soon)
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

## Roadmap

### Phase 1 (MVP) - COMPLETED
- Manual topic input
- AI script generation
- Basic export functionality

### Phase 2 - Planned
- Trending topic scraper (Reddit/Twitter)
- Auto content scheduler
- Multiple content formats

### Phase 3 - Future
- Multi-channel support
- Team workspace
- SaaS subscription model
- Video generation integration

## Brand Guidelines

**Core Motto**: "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."

**Tone**:
- Santai & relatable
- Tech-savvy tapi approachable
- Anti ribet
- Insight padat, durasi singkat

**Colors**:
- Primary: `#0f172a` (slate-950)
- Accent: `#22c55e` (green-500)
- Text: `#e5e7eb` (gray-200)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the MIT license.

## Support

For issues and questions, please open an issue on GitHub.

---

Built with Laravel 11 • Powered by OpenRouter AI • Made for content creators who value efficiency
