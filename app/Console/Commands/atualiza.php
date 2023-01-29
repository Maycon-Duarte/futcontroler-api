<?php

namespace App\Console\Commands;

use App\Models\campeonatos;
use App\Models\partidas;
use Goutte\Client;
use Illuminate\Console\Command;

class atualiza extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:refreshCrawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campeonatos = campeonatos::where('atu', 1)->get();
        for ($i = 0; $i < count($campeonatos); $i++) {

            $client = new Client();
            $crawler = $client->request('GET', $campeonatos[$i]->link);

            $crawler->filter('a > .row')->each(function ($node) {

                $status = $node->filter('.status-name')->html();
                $link = $node->closest('a')->attr('href');
                $casa = str_replace(' Sub-17', '', str_replace(' Sub-18', '', str_replace(' Sub-19', '', str_replace(' Sub-20', '', str_replace(' Feminino', '', $node->filter('.text-right')->html())))));
                $fora = str_replace(' Sub-17', '', str_replace(' Sub-18', '', str_replace(' Sub-19', '', str_replace(' Sub-20', '', str_replace(' Feminino', '', $node->filter('.text-left')->html())))));

                if ($node->filter('.match-score.d-flex.justify-content-end > h4 > .badge-default')->count()) {
                    $gols_casa = $node->filter('.match-score.d-flex.justify-content-end > h4 > .badge-default')->html();
                } else {
                    $gols_casa = 0;
                }

                if ($node->filter('.match-score.d-flex.justify-content-end > h4 > .badge-penalties')->count()) {
                    $penal_casa = $node->filter('.match-score.d-flex.justify-content-end > h4 > .badge-penalties')->html();
                } else {
                    $penal_casa = 0;
                }

                if ($node->filter('.match-score.d-flex.justify-content-start > h4 > .badge-default')->count()) {
                    $gols_fora = $node->filter('.match-score.d-flex.justify-content-start > h4 > .badge-default')->html();
                } else {
                    $gols_fora = 0;
                }

                if ($node->filter('.match-score.d-flex.justify-content-start > h4 > .badge-penalties')->count()) {
                    $penal_fora = $node->filter('.match-score.d-flex.justify-content-start > h4 > .badge-penalties')->html();
                } else {
                    $penal_fora = 0;
                }

                if (partidas::where('token', md5($link))->where('encerrado', false)->where('data', date("d-m-Y"))->exists()) {
                    $partida = partidas::firstWhere('token', md5($link));
                    $partida->casa = $casa;
                    $partida->fora = $fora;
                    $partida->gols_casa = $gols_casa;
                    $partida->gols_fora = $gols_fora;
                    $partida->penaltis_casa = $penal_casa;
                    $partida->penaltis_fora = $penal_fora;
                    $partida->status_jogo = $status;

                    if ($status == 'INTERVALO') {
                        $partida->intervalo = true;
                    }

                    if ($status == 'ENCERRADO') {
                        $partida->encerrado = true;
                    }

                    if ($status == 'PÊNALTI') {
                        $partida->penalti = true;
                    }

                    if ($status == 'PRORROGAÇÃO') {
                        $partida->prorrogacao = true;
                    }

                    $partida->save();
                    
                    $this->comment("[$partida->id] $partida->casa $partida->gols_casa ($partida->penaltis_casa) X ($partida->penaltis_fora) $partida->gols_fora $partida->fora - $partida->campeonato - $partida->status_jogo - ATUALIZADO");

                    $partidasHoje = partidas::where('data', date('d-m-Y'))->where('campeonato_id', $partida->campeonato_id)->where('encerrado', false)->count();
                    if ($partidasHoje > 0) {
                        $campeonato = campeonatos::find($partida->campeonato_id);
                        $campeonato->atu = 1;
                        $campeonato->save();
                    } else {
                        $campeonato = campeonatos::find($partida->campeonato_id);
                        $campeonato->atu = 0;
                        $campeonato->save();
                    }
                }
            });
        }
    }
}
