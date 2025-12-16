<?php

/**
 * TOB (Taks op Beursverrichtingen) Configuration
 *
 * This file contains all Belgian stock exchange tax rates and caps.
 * Update this file when Belgian tax law changes.
 *
 * Sources:
 * - FOD Financiën: https://financien.belgium.be/nl/experten_partners/investeerders/taks-op-beursverrichtingen
 * - Wikifin (FSMA): https://www.wikifin.be/nl/belasting-werk-en-inkomen/belastingaangifte/je-roerend-inkomen/de-belastingen-op-je-belgische
 *
 * Last verified: December 2025
 */

return [

    /*
    |--------------------------------------------------------------------------
    | TOB Tax Rates
    |--------------------------------------------------------------------------
    |
    | The three TOB tax rates applicable in Belgium.
    | Each rate has a cap (plafond) - the maximum tax per transaction.
    |
    | IMPORTANT: When updating rates, also update the TobRate enum!
    |
    */

    'rates' => [

        /*
         * LOW RATE (0.12%)
         * Applies to:
         * - Accumulating ETFs and funds registered in the EEA
         * - Bonds (obligaties)
         * - Regulated real estate companies (GVV/SIR)
         */
        'low' => [
            'percentage' => 0.12,           // Human-readable percentage
            'rate' => 0.0012,               // Decimal rate for calculations
            'cap' => 1300,                  // Maximum tax in EUR per transaction
            'label_nl' => '0,12%',
            'label_en' => '0.12%',
            'description_nl' => 'Accumulerende ETFs/fondsen (EER), obligaties, GVV',
            'description_en' => 'Accumulating ETFs/funds (EEA), bonds, regulated real estate',
        ],

        /*
         * MEDIUM RATE (0.35%)
         * Applies to:
         * - Individual stocks (aandelen)
         * - Distributing ETFs
         * - Most other securities not in the other categories
         */
        'medium' => [
            'percentage' => 0.35,
            'rate' => 0.0035,
            'cap' => 1600,
            'label_nl' => '0,35%',
            'label_en' => '0.35%',
            'description_nl' => 'Individuele aandelen, distribuerende ETFs',
            'description_en' => 'Individual stocks, distributing ETFs',
        ],

        /*
         * HIGH RATE (1.32%)
         * Applies to:
         * - Investment funds NOT registered in the EEA
         * - Distributing funds without EU passport
         */
        'high' => [
            'percentage' => 1.32,
            'rate' => 0.0132,
            'cap' => 4000,
            'label_nl' => '1,32%',
            'label_en' => '1.32%',
            'description_nl' => 'Beleggingsfondsen NIET geregistreerd in EER',
            'description_en' => 'Investment funds NOT registered in EEA',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Declaration Deadlines
    |--------------------------------------------------------------------------
    |
    | TOB must be declared and paid by the last working day of the
    | second month following the transaction month.
    |
    | Example: January transaction -> deadline end of March
    |
    | Special case: January and February transactions can be combined
    | into a single declaration due by end of April.
    |
    */

    'deadlines' => [
        'months_after_transaction' => 2,    // Deadline is 2 months after
        'combine_jan_feb' => true,          // Jan+Feb can be combined
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */

    'file_upload' => [
        'max_size_kb' => 10240,             // 10 MB
        'allowed_extensions' => ['xlsx', 'csv'],
        'allowed_mimes' => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'text/plain',
            'application/csv',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'results_per_page' => 50,
    ],

    'cache' => [
        'markdown_ttl' => 86400,            // 24 hours in seconds
    ],

];
