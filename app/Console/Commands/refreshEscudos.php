<?php

namespace App\Console\Commands;

use App\Models\campeonatos;
use App\Models\escudos;
use App\Models\partidas;
use Goutte\Client;
use Illuminate\Console\Command;

class refreshEscudos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:refreshEscudos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca por times não cadastrados';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campeonatos = campeonatos::all();
        for ($i = 0; $i < count($campeonatos); $i++) {

            $client = new Client();
            $crawler = $client->request('GET', $campeonatos[$i]->link);
            $campeonato = $campeonatos[$i];
            $crawler->filter('.team_link')->each(function ($node) {
                if(escudos::where('nome', $node->text())->doesntExist()){
                    $escudos = new escudos();
                    $escudos->nome = str_replace(' Sub-17', '' , str_replace(' Sub-18', '' , str_replace(' Sub-19', '' , str_replace(' Sub-20', '' ,str_replace(' Feminino', '',$node->text())))));
                    $escudos->save();

                    $this->comment($escudos->nome . "- ADICIONADO");
                }
            });
        }
    }
}
