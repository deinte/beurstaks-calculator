# TOB Calculator

Bereken je Belgische beurstaks (Taks op Beursverrichtingen) voor Revolut transacties.

> **AI-gegenereerde code**
>
> Deze applicatie is grotendeels gegenereerd met behulp van AI (Claude). Hoewel de code is gereviewed en getest, kunnen er fouten in zitten. Gebruik deze tool op eigen risico. Controleer altijd de berekeningen voordat je aangifte doet bij de FOD Financiën.
>
> **Fout gevonden?** [Open een issue](https://github.com/deinte/beurstaks-calculator/issues) of [draag bij](https://github.com/deinte/beurstaks-calculator).

## Features

- Upload Revolut transactie-export (Excel/CSV)
- Automatische herkenning van bekende tickers (ETFs, aandelen)
- Berekening per aangifteperiode met deadlines
- Export naar Excel
- Gebaseerd op officiele tarieven FOD Financien

## TOB Tarieven (2025)

| Tarief | Percentage | Plafond | Van toepassing op |
|--------|------------|---------|-------------------|
| Laag   | 0,12%      | €1.300  | Accumulerende ETFs (EER), obligaties, GVV |
| Medium | 0,35%      | €1.600  | Aandelen, distribuerende ETFs |
| Hoog   | 1,32%      | €4.000  | Niet-EER fondsen |

## Vereisten

- PHP 8.4+
- Composer
- Node.js & NPM
- Laravel Herd (aanbevolen) of andere lokale server

## Installatie

```bash
# Clone de repository
git clone https://github.com/deinte/beurstaks-calculator.git
cd beurstaks-calculator

# Installeer PHP dependencies
composer install

# Installeer Node dependencies
npm install

# Kopieer environment file
cp .env.example .env

# Genereer applicatie key
php artisan key:generate

# Build assets
npm run build
```

## Development

```bash
# Start Vite dev server (hot reload)
npm run dev

# Run PHPStan
./vendor/bin/phpstan analyse

# Run tests
php artisan test
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
│   └── Actions/
└── Providers/
```

## Documentatie

- [ARCHITECTURE.md](docs/ARCHITECTURE.md) - Technische architectuur en implementatieplan
- [TOB Notities](docs/TOB_Revolut_Belgie_Notities.md) - Belgische beurstaks uitleg en bronnen

## Disclaimer

Deze tool is louter informatief en geen juridisch of fiscaal advies. De berekeningen zijn gebaseerd op publiek beschikbare informatie van de FOD Financien. Raadpleeg bij twijfel een fiscalist of neem contact op met de FOD Financien.

## Licentie

MIT License - Zie [LICENSE](LICENSE) voor details.

## Credits

Gemaakt door [Dante Schrauwen](https://danteschrauwen.be)
