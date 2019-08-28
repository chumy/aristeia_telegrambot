<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Partida;
use App\User;
use Carbon\Carbon;
use Log;

class PartidaController extends Controller
{
    //
    public function addPartida($user, $fecha){
        
        $partidas= Partida::all()->where('user_id',$user)->count();
        

        if ( $partidas > 0) 
        {
            Partida::where('user_id',$user)->delete();
        }



        Partida::create([
            'user_id' => $user,
            'fecha' => $fecha,
        ]);

    }

    public function delPartida($user_id){
        
        Partida::where('user_id',$user_id)->delete();

    }

    public function listaPartidas(){
        
        $partidas = Partida::all()->where('fecha','>=', Carbon::now())
            ;

       // Log::debug($partidas);
        if ($partidas->count() > 0)
        {
            $text='<b>Partidas Disponibles</b>'.PHP_EOL;
            foreach ($partidas as $partida){
                
                $usuario = User::all()->where('id',$partida['user_id'])->first();
                
                $enlace = '<a href="tg://user?id='.$usuario['chat'].'"> '.$usuario['nick'].'</a>';
                $text .= $partida['id'].' : '.$enlace.' - '.$partida['fecha'].PHP_EOL;
            }
        }else
            $text = 'No hay partidas disponibles'.PHP_EOL;

        return Array('text' => $text , 'parse_mode'=> 'HTML');

    }

    /*[2019-08-25 10:27:56] local.DEBUG: {"update_id":529806898,
        "message":{"message_id":737,
                    "from":{
                            "id":247049890,
                            "is_bot":false,
                            "first_name":"Chumy",
                            "username":"xChumy",
                            "language_code":"es"},
                    "chat":{"id":247049890,"first_name":"Chumy","username":"xChumy","type":"private"},"date":1566720322,"text":"\/mispartidas","entities":[{"offset":0,"length":12,"type":"bot_command"}]}}  
*/
    public function misPartidas($update)
    {
               
            $chat_user_id = $update["message"]["from"]["id"];
            //comprobamos si existe el usuario
            $user_id = $this->checkUser($update);

            //return $this->sendMessage(Array('text'=> $user_id .' nada '.$chat_user_id));
            //$mensaje['text'] = $user_id;
            //return $mensaje;

            $partidas = Partida::all()
                ->where('user_id', $user_id)
                ->where('fecha','>=', Carbon::today());
                
            //solo deberia tener una partida

            
            if ($partidas->count() > 0)
            {
                $text='<b>Mis Partidas</b>'.PHP_EOL;
                foreach ($partidas as $partida){
                    
                    $usuario = User::all()->where('id',$partida['user_id'])->first();
                    //return $this->sendMessage(Array('text'=> $usuario .' nada '));
                    $enlace = '<a href="tg://user?id='.$usuario['chat'].'"> '.$usuario['nick'].'</a>';
                    $text .= $partida['id'].' : '. $enlace.' - '.$partida['fecha'].PHP_EOL;

                    $keyboard[][]= array('text' => 'Borrar '.$partida['id'], 
                                        'callback_data' => '/borrarPartida '.$partida['id']);

                    //$mensaje['reply_markup'] = json_encode($keyboard);
                
                }
            }else{
                $text = 'No hay partidas disponibles'.PHP_EOL;
                
            }


        $mensaje=Array('parse_mode' => 'HTML');
        $mensaje['text'] = $text;

        return $mensaje;
        /*Array('text' => $text, 
                    'reply_markup' => json_encode($keyboard), 
                    'parse_mode' => 'HTML');*/

    }

    public function crearPartida($update)
    {
 
        //$chat_user_id = $update["message"]["from"]["id"];

        $fecha = $update["message"]["text"];

        $fecha = str_replace("/crear ", "", $fecha);

        //return Array('text'=> Carbon::parse($fecha)->toString());

        try 
        {
            if ( Carbon::parse($fecha)->lt(Carbon::now()) )
            {
                $past = Carbon::parse($fecha);
                $date = Carbon::now(); 
                $date->addDay();
                $date->hour= $past->hour;
                $date->minute = $past->minute;
                
            }else{
                $date = Carbon::parse($fecha);
            }
            $date->second=0;

            //return Array('text'=>$date);
            //Check de usuario
            $user_id= $this->checkUser($update);
            
            
            $this->addPartida($user_id,$date);
               
            $text = 'Partida generada a las '.$date.PHP_EOL;
        
        } catch (\Exception $e) {
            $text = 'Formato de fecha desconocido'.PHP_EOL
                    .'<b>Formatos validos:</b>'.PHP_EOL
                    .'/crear 19:00'.PHP_EOL
                    .'/crear 27-10-2019 19:00'.PHP_EOL;
        }

        $mensaje=Array('parse_mode' => 'HTML');
        $mensaje['text'] = $text;

        return $mensaje;

    }

    public function borrarPartida($update){

        
        $user_id = $this->checkUser($update);
      
        $this->delPartida($user_id);
               
        $text = 'Partida eliminada correctamente'.PHP_EOL;
        
        return Array('text' => $text);

    }

    public function checkUser($update){

        //Check de usuario
        $chat_user_id = $update["message"]["from"]["id"];
        $num_user = User::all()->where('chat',$chat_user_id)->count();
        if ($num_user > 0)
        {
            $user_id = User::select('id')->where('chat',$chat_user_id)->get()[0]['id'];
        }else {

            $new_user= User::create([
                'chat' => $chat_user_id,
                'name' => $update["message"]["from"]["first_name"],
                'nick' => $update["message"]["from"]["username"],
                ]);
            $user_id = $new_user->lastInsertId();
        }

        return $user_id;
    }

    public function sendMessage($data)
    {
        $mensaje=Array('parse_mode' => 'HTML');
        if (isset($data['text'])) {
            $mensaje['text'] = $data['text'];
        }
        if (isset($data['keyboard'])) {
            $mensaje['reply_markup'] = json_encode($data['keyboard']);
        }
        
        return $mensaje;
        
    }

    
}
