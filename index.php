<?php

require_once 'config.php';
require_once 'vendor/autoload.php';

$connection = new \Phergie\Irc\Connection();

$seuBot = 'kastrinhobot';
$seuCanal = '#kastr0walker';
$qtdBots = 0;
$first = true;
$motivosBan = [
    ' não gostar do deninho.',
    ' subir bug na master.',
    ' programar em java.',
    ' trabalhar no co-working.',
    ' colocar purê no dogão.',
];
$langs = [
    'python',
    'php',
    'go',
    'rust',
    'elixir',
    'javascript',
    'ruby',
    'c',
    'cobol'
];
$nicks = [
    ' carol dona da dell',
    ' carlo dônadel',
    ' comandante caroldo',
    ' caroldofortrel',
    ' carol dona da enel',
    ' comandante aroldo nadel',
    ' carol dona daily'
];

$connection
    ->setServerHostname('irc.chat.twitch.tv')
    ->setServerPort(6667)
    ->setPassword($password)
    ->setNickname($seuBot)
    ->setUsername($seuBot);

$client = new \Phergie\Irc\Client\React\Client();

$client->on('connect.after.each', function ($connection, $write) {
    global $seuCanal;
    $write->ircJoin($seuCanal);
    $write->ircPrivmsg($seuCanal, 'kastrinho tá on!');
});

$client->on('irc.received', function ($message, $write, $connection, $logger) {

    global $seuCanal;
    global $motivosBan;
    global $qtdBots;
    global $langs;
    global $first;
    global $nicks;

    $command = explode(' ', $message['params']['text']);

    if ($message['command'] == 'PRIVMSG') {

        if ($message['nick'] == 'caroldonadel' && $first) {
            $nick = $nicks[rand(0, (sizeof($nicks) - 1))];

            $write->ircPrivmsg($seuCanal, 'olá ' . $nick);
            $first = false;
            return;
        }

        switch ($command[0]) {
            case '!github':
                $write->ircPrivmsg($seuCanal, 'https://github.com/kastrowalker');
                break;
            case '!caverna':
                $write->ircPrivmsg($seuCanal, '/me https://discord.gg/h9UCgu9  Por favor, não se esqueça de passar no canal #regras para liberar o acesso á todas as salas do nosso servidor ^^');
                break;
            case '!lives':
                $write->ircPrivmsg($seuCanal, 'https://github.com/Caaddss/awesome-live-coding-streams');
                break;
            case '!twitter':
                $write->ircPrivmsg($seuCanal, 'twitter.com/kastrowalker');
                break;
            case '!bot':
                $qtdBots++;
                $lang = $langs[rand(0, (sizeof($langs) - 1))];

                $write->ircPrivmsg($seuCanal, '/me kastro criou mais um bot em ' . $lang . ' agora temos ' . $qtdBots . ' bots!');
                break;
            case '!ban':
                $motivoBan = $motivosBan[rand(0, (sizeof($motivosBan) - 1))];

                $write->ircPrivmsg($seuCanal, '/me ' . $message['nick'] . ' baniu ' . $command[1] . ' por ' . $motivoBan);
                break;
            case '!carol':
                $nick = $nicks[rand(0, (sizeof($nicks) - 1))];

                $write->ircPrivmsg($seuCanal, $nick);
                break;
            case '!comandos':
                $write->ircPrivmsg($seuCanal, '!carol | !ban | !twitter | !lives | !caverna | !github | !bot');
                break;
            default:
                break;
        }
    }
});

$client->run($connection);
