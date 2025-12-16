<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Content\Actions\ParseMarkdownPageAction;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ShowSourcesController
{
    public function __invoke(ParseMarkdownPageAction $parseAction): View
    {
        $contentSources = $this->getSourcesFromContentPages($parseAction);
        $officialResources = $this->getOfficialResources();

        return view('sources', [
            'contentSources' => $contentSources,
            'officialResources' => $officialResources,
        ]);
    }

    /**
     * @return array<string, array<int, array{name: string, url: string}>>
     */
    private function getSourcesFromContentPages(ParseMarkdownPageAction $parseAction): array
    {
        $sources = [];
        $path = resource_path('content/pages');

        if (! File::isDirectory($path)) {
            return $sources;
        }

        $files = File::files($path);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $slug = $file->getFilenameWithoutExtension();
            $page = $parseAction->execute($slug);

            if ($page && ! empty($page->sources)) {
                $sources[$page->title] = $page->sources;
            }
        }

        return $sources;
    }

    /**
     * @return array<string, array<int, array{name: string, url: string, description?: string}>>
     */
    private function getOfficialResources(): array
    {
        return [
            'FOD Financiën' => [
                [
                    'name' => 'Taks op beursverrichtingen - Overzicht',
                    'url' => 'https://financien.belgium.be/nl/ondernemingen/overige-belastingen/diverse-taksen/taks-beursverrichtingen',
                    'description' => 'Officiële informatie over de TOB van de Belgische overheid.',
                ],
                [
                    'name' => 'Aangifteformulier TOB (PDF)',
                    'url' => 'https://financien.belgium.be/sites/default/files/downloads/124-taks-beursverrichtingen_0.pdf',
                    'description' => 'Het officiële formulier voor papieren aangifte.',
                ],
                [
                    'name' => 'MyMinfin - Online aangifte',
                    'url' => 'https://www.myminfin.be/',
                    'description' => 'Portaal voor online aangifte van diverse taksen.',
                ],
                [
                    'name' => 'Tarieven roerende voorheffing',
                    'url' => 'https://financien.belgium.be/nl/particulieren/belastingaangifte/tarieven-702',
                    'description' => 'Overzicht van belastingtarieven op roerende inkomsten.',
                ],
            ],
            'Wikifin (FSMA)' => [
                [
                    'name' => 'Belastingen op Belgische beleggingen',
                    'url' => 'https://www.wikifin.be/nl/belasting-werk-en-inkomen/belastingaangifte/je-roerend-inkomen/de-belastingen-op-je-belgische',
                    'description' => 'Educatieve uitleg over beleggingsbelastingen van de FSMA.',
                ],
                [
                    'name' => 'Beleggen: kosten en taksen',
                    'url' => 'https://www.wikifin.be/nl/geld-beleggen/beleggen-praktijk/kosten-en-taksen',
                    'description' => 'Overzicht van alle kosten bij beleggen.',
                ],
            ],
            'Europese Commissie' => [
                [
                    'name' => 'Administrative Cooperation in Direct Taxation',
                    'url' => 'https://taxation-customs.ec.europa.eu/taxation/tax-cooperation-and-control/administrative-cooperation-direct-taxation_en',
                    'description' => 'Informatie over de automatische uitwisseling van fiscale gegevens (CRS).',
                ],
                [
                    'name' => 'Common Reporting Standard (OESO)',
                    'url' => 'https://www.oecd.org/tax/automatic-exchange/common-reporting-standard/',
                    'description' => 'De internationale standaard voor automatische gegevensuitwisseling.',
                ],
            ],
            'Wetgeving' => [
                [
                    'name' => 'Wetboek diverse rechten en taksen',
                    'url' => 'https://www.ejustice.just.fgov.be/cgi_loi/change_lg.pl?language=nl&la=N&cn=1927122730&table_name=wet',
                    'description' => 'De Belgische wetgeving over de taks op beursverrichtingen.',
                ],
            ],
        ];
    }
}
