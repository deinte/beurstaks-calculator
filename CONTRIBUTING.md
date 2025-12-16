# Contributing

Bedankt voor je interesse in het bijdragen aan beurstaks.be!

## Development Setup

### Vereisten

- PHP 8.4+
- Composer
- Node.js 20+
- NPM

### Installatie

```bash
# Clone de repository
git clone https://github.com/deinte/beurstaks-calculator.git
cd beurstaks-calculator

# Installeer dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Build assets
npm run build
```

### Development Server

```bash
# Start Vite dev server
npm run dev

# Of gebruik Laravel Herd / Valet
```

## Code Standaarden

### PHP

- PSR-12 coding standard
- Strict types in alle PHP bestanden
- PHPStan level 5 compliance

```bash
# Run PHPStan
./vendor/bin/phpstan analyse
```

### Testing

```bash
# Run alle tests
php artisan test

# Run specifieke test
php artisan test --filter=CalculateTobForTransactionActionTest
```

### Frontend

- TailwindCSS voor styling
- Alpine.js voor interactiviteit
- Livewire voor componenten

## Pull Request Process

1. Fork de repository
2. Maak een feature branch (`git checkout -b feature/mijn-feature`)
3. Commit je wijzigingen (`git commit -m 'Add: mijn feature'`)
4. Push naar de branch (`git push origin feature/mijn-feature`)
5. Open een Pull Request

### Commit Conventie

```
Add: nieuwe feature
Fix: bug fix
Update: verbetering aan bestaande feature
Refactor: code refactoring
Docs: documentatie wijziging
Test: test toevoegingen of fixes
```

## Structuur

```
app/
├── Tob/                    # TOB Calculator domein
│   ├── Actions/            # Single-purpose acties
│   ├── Data/               # Data Transfer Objects
│   ├── Enums/              # TobRate, TransactionType
│   ├── Livewire/           # Calculator component
│   ├── Mappers/            # Revolut file parser
│   └── Services/           # Calculator service
├── Content/                # Markdown content systeem
resources/
├── content/pages/          # Markdown info pagina's
├── views/                  # Blade templates
```

## Vragen?

Open een issue voor vragen of suggesties.
