<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class HomeService
{

    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client, $urlFlux, $urlApi)
    {
        $this->client = $client;
        $this->urlFlux = $urlFlux;
        $this->urlApi = $urlApi;
        $this->logger= $logger;
    }

    /*
      * return content d'un flux rss ou api
      */
    private function getContent($url)
    {
        try {
            $response = $this->client->request(
                'GET',
                $url,
                ['verify_peer' => false,
                    'verify_host' => false]
            );

            if ($response->getStatusCode() == 200) {

                return $response->getContent();
            }
        } catch (Exception $e) {
            $this->logger->error('A technical error occured', ['exception' => $e]);

        }
    }


    /*
     * return un array des urls des images from Flux Rss
     */

    public function getImageFlux()
    { $urlImages = array();
        try {
            $response = $this->getContent($this->urlFlux);
            $dataXml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
            $channel = $dataXml->channel;
            $count = count($channel->item);


            for ($i = 0; $i < $count; $i++) {
                if (!($this->detectDoublant($channel->item[$i]->enclosure->attributes()->url, $urlImages))) {
                    $urlImages[$i] = $channel->item[$i]->enclosure->attributes()->url;
                }
            }

        } catch (Exception $e) {
            $this->logger->error('A technical error occured', ['exception' => $e]);

        }
        return $urlImages;
    }



    /*
     * return un array des urls des images from APi
     */
    public function getImagesApi()
    { $urlImages = array();
        try {
            $response = $this->getContent($this->urlApi);
            $content = json_decode($response);
            for ($i = 0; $i < count($content->articles); $i++) {
                if (!($this->detectDoublant($content->articles[$i]->urlToImage, $urlImages))) {
                    $urlImages[$i] = $content->articles[$i]->urlToImage;
                }
            }
        } catch (Exception $e) {
            $this->logger->error('A technical error occured', ['exception' => $e]);

        }

        return $urlImages;
    }
    /*
     *  detect les doublants  dans un array
     */

    private function detectDoublant($url, $urlImages)
    {
        if (in_array($url, $urlImages)) {
            return true;
        }

        return false;

    }

    /*
     * concat array
     */
    public function concatImages()
    {

        return $this->getImageFlux() + $this->getImagesApi();
    }

}