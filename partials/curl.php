<?php
$ch = curl_init();
curl_setopt_array($ch, $options);
$result = json_decode(curl_exec($ch));
curl_close($ch);