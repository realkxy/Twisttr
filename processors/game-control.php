<?php

require_once '../config/functions.php';
require_once '../config/config.php';
require_once '../config/WordsGenerator.php';

class GameControl extends  Functions
{


    private $config, $data, $userID, $amount, $action, $game_id, $gameUserCanPlay, $gameIDUserCanPlay,
        $userCurrentGameDetail, $user_details, $showGameChat = false, $number_of_players_in_current_user_game;


    function __construct()
    {

        parent::__construct();
        $this->config = new WebsiteDetails();
    }

    function __destruct()
    {
        parent::__destruct(); // TODO: Change the autogenerated stub
    }


    private function isReady(): bool
    {

        return !empty($this->data = json_decode($_POST['data'], true));
    }

    private function setDetails(): bool
    {

        $this->userID = $this->data["userID"];
        $this->amount = $this->data["amount"];
        $this->action = $this->data["action"];
        $this->user_details = $this->fetch_data_from_table($this->users_table_name, "user_id", $this->userID)[0];

        return true;
    }

    private function create_a_new_game()
    {

        $this->game_id = $this->generateID($this->config->GameIDLength);
        if ($this->record_exists_in_table($this->games_table_name, "game_id", $this->game_id))
            $this->create_games_table();

        $words_generator = new WordsGenerator();

        $words = $words_generator->generateRandomWords();
        $words_to_json = json_encode($words);
        $start_time = time();

        $this->insert_into_table($this->games_table_name, ["game_id" => $this->game_id, "words" => $words_to_json, "amount" => $this->amount,
            "started" => "0", "start_time" => $start_time, "number_of_players" => "1", "current_word" => $words[0]]);
        $this->update_multiple_fields($this->users_table_name, ["game_id_about_to_play" => $this->game_id], "user_id='{$this->userID}'");

        return true;
    }


    private function any_existing_game_user_can_play()
    {


        $this->gameUserCanPlay = $this->fetch_data_from_table_with_conditions($this->games_table_name,
            "amount = '{$this->amount}' and started = '0' and number_of_players < {$this->config->MaximumNumberOfPlayers}");

        if (!empty($this->gameUserCanPlay)) {

            $this->gameUserCanPlay = $this->gameUserCanPlay[0];
            $this->gameIDUserCanPlay = $this->gameUserCanPlay["game_id"];
            return true;
        }

        return false;

    }

    private function add_user_to_existing_game()
    {

        $number_of_existing_players = intval($this->gameUserCanPlay["number_of_players"]) + 1;
        $this->number_of_players_in_current_user_game = $number_of_existing_players;
        $this->update_multiple_fields($this->games_table_name, ["number_of_players" => $number_of_existing_players], "game_id ='{$this->gameIDUserCanPlay}'");
        $this->update_multiple_fields($this->users_table_name, ["game_id_about_to_play" => $this->gameIDUserCanPlay], "user_id='{$this->userID}'");
        if ($number_of_existing_players == $this->config->MaximumNumberOfPlayers) {
            $this->update_record($this->games_table_name, 'started', '1', 'game_id', $this->gameIDUserCanPlay);
        }
        $this->showGameChat = true;

        return true;
    }


    private function get_total_number_of_players_playing_now () {
        $total_number_of_users_playing = count($this->fetch_data_from_table_with_conditions($this->games_table_name , "started='1'"));
        $total_number_of_users_playing = $total_number_of_users_playing * $this->config->MaximumNumberOfPlayers;

        $total_number_of_players_waiting = 0;

        $all_awaiting_games = $this->fetch_data_from_table_with_conditions($this->games_table_name , "started='0'");

        foreach ($all_awaiting_games as $awaiting_game){

            $total_number_of_players_waiting += intval($awaiting_game["number_of_players"]);
        }


        return $total_number_of_players_waiting + $total_number_of_users_playing;
    }
    private function add_current_user_to_game()
    {
        if ($this->any_existing_game_user_can_play()) {
            $this->add_user_to_existing_game();
            if ($this->showGameChat)
                return json_encode(Array("start" => "1", "players" => $this->number_of_players_in_current_user_game));
            return json_encode(Array("start" => "0", "players" => $this->number_of_players_in_current_user_game));


        } else {

            $this->create_a_new_game();
            return json_encode(Array("start" => "0", "players" => "1"));

        }


    }

    private function get_current_players_joined()
    {


        $this->userCurrentGameDetail = $this->fetch_data_from_table($this->games_table_name, 'game_id', $this->user_details["game_id_about_to_play"])[0];
        $number_of_players = $this->userCurrentGameDetail["number_of_players"];
        if ($number_of_players == $this->config->MaximumNumberOfPlayers) {
            $this->showGameChat = true;
        }

        return $number_of_players;

    }

    private  function  exit_user_from_game() {
        $this->userCurrentGameDetail = $this->fetch_data_from_table($this->games_table_name, 'game_id', $this->user_details["game_id_about_to_play"])[0];
        $number_of_players = $this->userCurrentGameDetail["number_of_players"];

        $new_number_of_players = $number_of_players - 1;
        $this->update_record($this->games_table_name , "number_of_players" , $new_number_of_players , 'game_id' , $this->userCurrentGameDetail["game_id"]);
        $this->update_record($this->users_table_name , "game_id_about_to_play" , "0" , 'user_id' , $this->userID);
        return true;
    }

    public function Processor()
    {

        if ($this->isReady() && $this->setDetails()) {

            $action = $this->data["action"];

            switch ($action) {

                case 'add_new_user_to_game' :
                    return $this->add_current_user_to_game();
                    break;
                case 'update_number_of_players' :

                    $players = $this->get_current_players_joined();
                    $start = "0";
                    if ($this->showGameChat) {
                        $start = "1";
                    }
                    return json_encode(Array("start" => $start, "players" => $players));
                    break;
                case 'get_total_number_of_players' :
                    $players = $this->get_total_number_of_players_playing_now();
                    return json_encode(["players" => $players]);
                case 'exit_user_from_game' :
                     $this->exit_user_from_game();
                     return json_encode(["success" => "1"]);

            }
        }

    }


}

$GameControl = new GameControl();
echo $GameControl->Processor();









?>