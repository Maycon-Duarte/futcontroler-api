<?php

namespace App\Console\Commands;

use App\Models\campeonatos;
use App\Models\partidas;
use Goutte\Client;
use Illuminate\Console\Command;

class resetCrawlerByID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:resetCrawlerByID {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command:resetCrawlerByID {id}';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campeonatos = campeonatos::find($this->argument('id'));

        $client = new Client();
        $crawler = $client->request('GET', $campeonatos->link);
        $campeonato = $campeonatos;
        $crawler->filter('a > .row')->each(function ($node) use (&$campeonato) {
            $status = $node->filter('.status-name')->html();
            if (str_contains($status, 'HOJE') || str_contains($status, 'AMANHÃ')) {
                $hora = explode(' ', trim($status))[1];
                $dia = explode(' ', trim($status))[0];
                $data = $dia == 'HOJE' ? date("d-m-Y") : date('d-m-Y', strtotime('+1 days', strtotime(date("d.m.y"))));
                $link = $node->closest('a')->attr('href');

                $casa = $node->filter('.text-right')->html();
                $fora = $node->filter('.text-left')->html();

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

                if (partidas::where('token', md5($link))->doesntExist()) {
                    $partida = new partidas();
                    $partida->campeonato = $campeonato->nome;
                    $partida->campeonato_id = $campeonato->id;
                    $partida->casa = $casa;
                    $partida->fora = $fora;
                    $partida->gols_casa = $gols_casa;
                    $partida->gols_fora = $gols_fora;
                    $partida->penaltis_casa = $penal_casa;
                    $partida->penaltis_fora = $penal_fora;
                    $partida->status_jogo = $status;
                    $partida->link = $link;
                    $partida->hora_inicio = $hora;
                    $partida->token = md5($link);
                    $partida->data = $data;
                    $partida->save();
                } else {
                    $partida = partidas::firstWhere('token', md5($link));
                    $partida->campeonato = $campeonato->nome;
                    $partida->campeonato_id = $campeonato->id;
                    $partida->casa = $casa;
                    $partida->fora = $fora;
                    $partida->gols_casa = $gols_casa;
                    $partida->gols_fora = $gols_fora;
                    $partida->penaltis_casa = $penal_casa;
                    $partida->penaltis_fora = $penal_fora;
                    $partida->status_jogo = $status;
                    $partida->link = $link;
                    $partida->hora_inicio = $hora;
                    $partida->token = md5($link);
                    $partida->data = $data;
                    $partida->save();
                }
            }
        });
        $partidasHoje = partidas::where('data', date('d-m-Y'))->where('campeonato_id', $campeonatos->id)->count();
        if ($partidasHoje > 0) {
            $campeonato = campeonatos::find($campeonatos->id);
            $campeonato->atu = 1;
            $campeonato->save();
        } else {
            $campeonato = campeonatos::find($campeonatos->id);
            $campeonato->atu = 0;
            $campeonato->save();
        }
    }
}
