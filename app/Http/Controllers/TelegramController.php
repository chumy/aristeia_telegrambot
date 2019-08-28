<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Telegram;
use App\Partida;

class TelegramController extends Controller
{
    //
    public function updatedActivity()
    {
         
        //Check if webhook is settled 
        if (strlen(Telegram::getWebhookInfo()['url']) > 0)
        {
            //Log::info('Entrando por webhook ');
            $updates = Telegram::getWebhookUpdates();
            /*
            Telegram::removeCommands([
                Telegram\Bot\Commands\HelpCommand::class,
                Telegram\Bot\Commands\CrearPartidaCommand::class,
                Telegram\Bot\Commands\ListaPartidasCommand::class,
            ]);
        
            Telegram::addCommands([
                Telegram\Bot\Commands\HelpCommand::class,
                Telegram\Bot\Commands\CrearPartidaCommand::class,
                Telegram\Bot\Commands\ListaPartidasCommand::class,
    
            ]);*/

            $commands = Telegram::commandsHandler(true);

        }else{
            $updates = Telegram::getUpdates(); 
            $updates = collect($updates)->last();
        }

   

        //dd($updates);

        //$partida= new Partida();
        
      //  $lista=app('App\Http\Controllers\PartidaController')
            //->listaPartidas();
            //->misPartidas($updates["message"]["from"]["id"]);
            //->crearPartida($updates["message"]["from"]["id"],$updates["message"]["text"]);
        //$this->sendMessage($lista);
        //dd($updates);
        //Log::info('Entrando por updates ');
        
        //$chatId = $updates["message"]["chat"]["id"];
        //$user = $updates["message"]["chat"]["username"];
        //$text = $updates["message"]["text"];

        /*$callback_query_data = $updates['callback_query']['data'];
        $call_back_id  = $updates['callback_query']['id'];
        $call_back_from = isset ($updates['callback_query']['from']['id']) ? $updates['callback_query']['from']['id'] : "";*/
        return 'ok';

            
    }
    
    public function webhookUpdate($updates)
    {
        Log::info('Entrando por updates ');
         $this->sendMessage('Hola ');
    }

    public function sendMessage($data)
    {
        //$text = 'Aquí adjunta lo quieras enviar.';

       //Log::info('Entrando por mensaje ');

       $info = [
            'chat_id' => env('TELEGRAM_CHANNEL_ID', '247049890'),
            'parse_mode' => 'HTML',
            'text' => $data['text'],
            //'reply_markup' => json_encode($data['keyboard']),
        ];
        if (sizeof($data['botonera']) > 0){
           // dd($data['botonera']);
             //$resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
             $reply_markup = array("inline_keyboard" => $data['botonera'],
                                 "one_time_keyboard" => true,
                                 "resize_keyboard" => true,);

            $info['reply_markup'] = json_encode($reply_markup);
        }
       // dd($info);
        Telegram::sendMessage($info);
    }
    
    public function showLista($chatId)
    {
        $reply_markup = null;
        $text = 'No hay partidas actualmente';
        Telegram::sendMessage([
                   'chat_id' => env('TELEGRAM_CHANNEL_ID', '247049890'),
                   'parse_mode' => 'HTML',
                   'text' => $text,
//                   'reply_markup' => json_encode($reply_markup),
                ]);
    
    }
    
    public function setWebhook()
    {

        $url1 = 'https://www.chumy.net/aristeia/bot/'.config(BOT_TELEGRAM).'/webhook';

        $response = Telegram::setWebhook([
                'url' => $url,
                ]);

                 
        Log::info('Setting up webhook ');
                    
        //dd($response);
    
    }

    public function ngrok($text)
    {
        $response = Telegram::removeWebhook();

        $url = "https://".$text.".ngrok.io/'.config(BOT_TELEGRAM).'/webhook";
        
        $response = Telegram::setWebhook([
                'url' => $url,
                ]);

                
        Log::info('Setting up webhook ');
                    
        //dd($response);
    
    }
    
    public function unsetWebhook()
    {
        $response = Telegram::removeWebhook();
        dd($response);
    
    }
}
