<?php
class class_openai

{

	const ROOT_URL = "https://api.openai.com/";  

    //构造函数，获取Access Token

    public function __construct($apikey = NULL)

    {

        $this->apikey = $apikey;

    }

    //文字完成 上下文

    public function chat_completions_context($messages)

    {

        $field = array(

            "model"=>"gpt-3.5-turbo",

            // "temperature" => 0,

            // "stream" => true,

            "messages"=>$messages,

        );

        $url = self::ROOT_URL."v1/chat/completions";

        

        $response = $this->http_request($url, json_encode($field));

        $result = json_decode($response, true);

        return trim($result["choices"][0]["message"]["content"]);

    }

	//图片完成

    public function images_generations($prompt)

    {

        $field = array("prompt"=>$prompt,

                       "n"=>1,

                       "size"=>"256x256",

                      );

        $url = self::ROOT_URL."v1/images/generations";

        $response = $this->http_request($url, json_encode($field));

        $result = json_decode($response, true);

        return trim($result["data"][0]["url"]);

    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）

    protected function http_request($url, $data = null)

    {

        $headers = array(

            "Content-Type: application/json",

            "Authorization: Bearer " . $this->apikey

        );

        var_dump($url);

        var_dump($headers);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 120);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)){

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;

    }

}

$chat = new class_openai($apikey = "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");

$prompt = "人生很痛苦，怎么办";

$messages = array(array('role' => "system", 'content' =>"你是专业的心理师"),

                  array('role' => "user", 'content' =>$prompt));

$result = $chat->chat_completions_context($messages);
?>
