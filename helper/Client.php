<?php


 class Client{

    const GET = 80;
    const POST = 47;

    private $session;
    private $uriBase;

    public function __construct()
     {
         $this->session = curl_init();
         curl_setopt($this->session, CURLOPT_RETURNTRANSFER, true);
     }

    public function setUrlBase($url)
    {
        $this->uriBase = $url;
    }

    public function request($method, $params = [])
    {

        $data = $this->build($params);
         
        curl_setopt($this->session, $method, true);

        ($method == self::POST) ?
            curl_setopt($this->session, CURLOPT_POSTFIELDS, $data): 
            $this->uriBase = "{$this->uriBase}?{$data}";
        
        curl_setopt($this->session, CURLOPT_URL, $this->uriBase);     

        $response = curl_exec($this->session);

        if (curl_errno($this->session)) return false;

        return json_decode($response, true);
          
    }

    public function getErrorRequest()
    {
        if (curl_errno($this->session)) return curl_error($this->session);
    }

    private function build($params = [])
    {
        return http_build_query($params);
    }




    public function __destruct()
    {
        curl_close($this->session);
    }


}