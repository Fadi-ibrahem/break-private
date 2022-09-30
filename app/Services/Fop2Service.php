<?php

namespace App\Services;

use Amp\Loop;
use Amp\Delayed;
use Amp\Websocket;
use Amp\Websocket\Client;
use Illuminate\Support\Str;

class Fop2Service
{
    public static function changeStatus($extension, $status)
    {
        Loop::run(function () use ($extension, $status) {
            /** @var Client\Connection $connection */
            $connection = yield Client\connect('ws://pbx.hojuzat.com:4445');
            yield $connection->send('<msg data="GENERAL|contexto|1|" />');

            $secret = "";
            $myext = "127";
            $password = "kkfjeff";
            $position = "";
            /** @var Websocket\Message $message */
            while ($message = yield $connection->receive()) {
                $rawPayload = yield $message->buffer();


                // printf("Received: %s\n", $rawPayload);

                if (!$secret && Str::contains($rawPayload, '"cmd": "key"')) {
                    $payload = json_decode($rawPayload);
                    $key = $payload->data;
                    $secret = md5($password . $key);
                    yield $connection->send('<msg data="1|auth|' . $myext . '|' . $secret . '" />');
                }



                if (!$position && Str::contains($rawPayload, '"zbuttons"')) {
                    $payload = json_decode($rawPayload);

                    $data = explode(PHP_EOL, base64_decode($payload->data));

                    foreach ($data as $d) {
                        if (Str::contains($d, "EXTENSION=$extension")) {
                            $position = $d[0];
                            break;
                        }
                    }
                }

                $s = $status == 'break' ? "QnJlYWs=" : "";

                if ($secret && $position) {
                    printf('<msg data="' . $position . '|setastdb|fop2state~' . $extension . '~' . $s . '|' . $secret . '" />');

                    yield $connection->send('<msg data="' . $position . '|setastdb|fop2state~' . $extension . '~' . $s . '|' . $secret . '" />');
                    $connection->close();
                    Loop::stop();
                    break;
                }


                // if ($payload === 'Goodbye!') {
                //     $connection->close();
                //     break;
                // }

                // yield new Delayed(1000);

                // if ($key) {
                //     $secret = md5($password . $key);
                //     yield $connection->send('<msg data="5|setastdb|fop2state~124~QnJlYWs=|' . $secret . '" />');
                //     printf("Sent: %s\n", '<msg data="5|setastdb|fop2state~124~QnJlYWs=|' . $secret . '" />');

                //     $connection->close();
                //     break;
                // }

                // yield $connection->send('<msg data="1|ping||" />');
            }
        });
    }
}
