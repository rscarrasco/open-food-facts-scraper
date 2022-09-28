<?php

include_once("./Product.php");

class OpenFoodFactsScraper {
    private $url = "https://world.openfoodfacts.org";

    private function fetchPage($path){
        $ch = curl_init($this->url . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function collectProductPaths($html){
        $pattern = '#<a href=\"(\/product\/[0-9]+\/[a-zA-Z0-9\-\_\%]*)"#';
        preg_match_all($pattern, $html, $matches);
        return $matches[1];
    }

    private function extractCode($path){
        $pattern = '#\/product\/([0-9]+)\/#';
        preg_match($pattern, $path, $matches);
        return $matches[1];
    }

    private function extractBarcode($page){
        $pattern = '#Barcode:(.*)#';
        preg_match($pattern, $page, $matches);
        return trim(strip_tags($matches[1]));
    }

    private function extractName($page){
        $pattern = '#<h1 property=\"food:name\" itemprop=\"name\">(.*)<\/h1>#';
        preg_match($pattern, $page, $matches);
        return trim($matches[1]);
    }

    private function extractQuantity($page){
        $pattern = '#<span.*id=\"field_quantity_value\">(.*)<\/span>#';
        preg_match($pattern, $page, $matches);
        return trim($matches[1]);
    }

    private function extractCategories($page){
        $pattern = '#<span.*id=\"field_categories_value\">(.*)<\/span>#';
        preg_match($pattern, $page, $matches);
        return trim(strip_tags($matches[1]));
    }

    private function extractPackaging($page){
        $pattern = '#<span.*id=\"field_packaging_value\">(.*)<\/span>#';
        preg_match($pattern, $page, $matches);
        return trim(strip_tags($matches[1]));
    }

    private function extractBrands($page){
        $pattern = '#<span.*id=\"field_brands_value\">(.*)<\/span>#';
        preg_match($pattern, $page, $matches);
        return trim(strip_tags($matches[1]));
    }

    // O desafio originalmente solicita que siga-se as instruções
    // disponibilizadas na [documentação da API](https://wiki.openfoodfacts.org/Developer-How_To) a fim de
    // recuperar as URLs das imagens. Entretanto, a própria
    // documentação orienta que:
    //
    // > To get the image file names, we have to use the 
    // > database dump or the API. 
    //
    // E não faz sentido acessar a API apenas para recuperar um campo,
    // sendo que todos os demais já são raspados da página. Se fosse para 
    // acessar a API, melhor seria recuperar todos os dados, e esquecer
    // HTML. Como entendi que o processamento do HTML é um dos objetivos
    // do desafio, optei por ignorar este procedimento mais complexo, e
    // simplesmente coletar a primeira URL de produto informada na página.
    public function generateImageUrl($code, $page){
        if(strlen($code) <= 8){
            return $this->imageUrl . $code;
        }
        $pattern = "#(\/images\/products[a-zA-Z0-9\/_\-\.]+)#";
        preg_match_all($pattern, $page, $matches);
        return $this->url . $matches[0][0];
    }

    public function scrapeProduct($path){
        $page = $this->fetchPage($path); 
        $data = array();

        $data['code'] = $this->extractCode($path);
        $data['barcode'] = $this->extractBarcode($page);
        $data['status'] = 'imported';
        $data['imported_t'] = date('Y/m/d H:i:s');
        $data['url'] = $this->url . $path;
        $data['product_name'] = $this->extractName($page);
        $data['quantity'] = $this->extractQuantity($page);
        $data['categories'] = $this->extractCategories($page);
        $data['packaging'] = $this->extractPackaging($page);
        $data['brands'] = $this->extractBrands($page);
        $data['image_url'] = $this->generateImageUrl($data['code'], $page);

        return $data;
    }

    public function scrape(){
        $home = $this->fetchPage('/');
        $urls = $this->collectProductPaths($home);
        
        foreach ($urls as $url){
            $data = $this->scrapeProduct($url);
            $product = new Product();
            $product->setData($data);
            $product->store();
        }
    }
}

