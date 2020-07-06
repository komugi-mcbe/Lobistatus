<?php

/*
LobiAPI = https://github.com/NewDelion/LobiAPI-PHP
参考 =  https://github.com/ikatyo0702/LobiShoutSystem
*/

namespace xtakumatutix\lobistatus;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use xtakumatutix\lobistatus\LobiAPI;

Class Main extends PluginBase 
{

    public function onEnable() 
    {
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            'mail' => 'test@gmail.com',
            'password' => '123456',
            'guild' => 'A1B2C3D4E5F6G7'
        ]);
        $message = 'サーバーが開きました ('.date('m月d日 G時i分').')';
        $name = '小麦鯖 - Open';
        $description = '開いています ('.date('m月d日 G時i分').')';
        $this->post($message, $name, $description);
    }

    public function onDisable()
    {
        $message = 'サーバーが閉じました ('.date('m月d日 G時i分').')';
        $name = '小麦鯖 - Close';
        $description = '閉じてます ('.date('m月d日 G時i分').')';        
        $this->post($message, $name, $description);
    }

    public function post($message, $name, $description)
    {
        $api = new LobiAPI();
        $mail = $this->config->get('mail');
        $password = $this->config->get('password');
        $guild = $this->config->get('guild');
        if ($api->Login($mail, $password)){
            $this->getLogger()->info('メールでのLobiログイン成功');
            $api->MakeThread($guild, $message, $shout = false);
            $api->ChangeProfile($name, $description);
            $this->getLogger()->info('送信完了 - プロフィール変更完了');
        }else{
            $this->getLogger()->info('ログイン失敗');
        }
    }
}