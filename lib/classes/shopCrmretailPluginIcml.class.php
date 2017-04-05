<?php

class shopCrmretailPluginIcml
{
    protected $settings;
    protected $dom;
    protected $categories;
    protected $offers;
    protected $category;

    protected $products;
    protected $skus;
    protected $product_fields;

    public function generate()
    {
        $this->settings = wa('shop')->getPlugin('crmretail')->getSettings();
        $now = date('Y-m-d H:i:s');
        $s = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<yml_catalog date="$now">
    <shop>
        <name>{$this->settings['shopname']}</name>
        <company>{$this->settings['companyname']}</company>
        <categories/>
        <offers/>
    </shop>
</yml_catalog>
XML;
        $xml = new SimpleXMLElement($s, LIBXML_NOENT |LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_PARSEHUGE);
        $this->dom = new DOMDocument();
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = true;
        $this->dom->loadXML($xml->asXML());

        $this->categories = $this->dom->getElementsByTagName('categories')->item(0);
        $this->offers = $this->dom->getElementsByTagName('offers')->item(0);

        $this->addCategories();
        $this->addOffers();

        waFiles::write(wa('shop')->getDataPath('plugins/crmretail/catalog.xml',true), $this->dom->saveXML());
    }


    protected function addCategories()
    {
        $e = $this->categories->appendChild($this->dom->createElement('category', 'Без категории'));
        $e->setAttribute('id', 0);

        $m = new shopCategoryModel();
        foreach ( $m->getAll() as $k => $v )
        {
            if ( empty($v['name']) || !isset($v['name']) )
                continue;

            $e = $this->categories->appendChild($this->dom->createElement('category', htmlspecialchars($v['name'])));
            $e->setAttribute('id',$v['id']);

            ( $v['parent_id'] != 0 ) && $e->setAttribute('parentId', $v['parent_id']);
            $this->category[$v['id']] = $v['full_url'];
        }
    }

    protected function addOffers()
    {
        $m = new shopProductSkusModel();
        $this->skus = $m->getAll();

        $m = new shopProductModel();
        $this->products = $m->getAll('id');

        $this->getProductFields();

    }


    protected function getProductFields()
    {
        $pfm = new shopProductFeaturesModel();
        $fm = new shopFeatureModel();

        $fields = array();
        foreach ( $pfm->getAll() as $k=>$v )
        {
            $product_id = $v['product_id'];
            if ( empty($fields) )
                $fields = $fm->getValues($fm->getByProduct($product_id));
            $field = $fields[$v['feature_id']];
            ( !isset($this->product_fields[$product_id]) ) && $this->product_fields[$product_id] = array();
            $this->product_fields[$product_id][$field['code']] = $field['values'][$v['feature_value_id']];
        }

        $this->addSkus();
    }


    protected function addSkus()
    {
        foreach ( $this->skus as $k => $v )
        {
            $v['fields'] = isset($this->product_fields[$v['product_id']]) ? $this->product_fields[$v['product_id']] : array();
            $e = $this->offers->appendChild($this->dom->createElement('offer'));

            $e->setAttribute('id',$v['id']);
            $e->setAttribute('productId',$v['product_id']);
            $e->setAttribute('quantity',empty($val['count']) ? 0 : $v['count']);
            $e->setAttribute('available',$v['available'] ? 'true' : 'false');

            $product = $this->products[$v['product_id']];

            $category = $product['category_id'];
            empty($category) && $category = 0;

            $e->appendChild($this->dom->createElement('categoryId', $category));

            $name = $v['name'];
            $product_name = $product['name'];
            empty($name) && $name = $product_name;

            $e->appendChild($this->dom->createElement('name'))->appendChild($this->dom->createTextNode(htmlspecialchars($name)));
            $e->appendChild($this->dom->createElement('productName'))->appendChild($this->dom->createTextNode(htmlspecialchars($product_name)));

            $e->appendChild($this->dom->createElement('price', $v['primary_price']));
            ($val['purchase_price'] > 0) && $e->appendChild($this->dom->createElement('purchasePrice', $v['purchase_price']));

            if ( isset($this->settings['xmlId']) && !empty($this->settings['xmlId']) )
                if ( isset($v['fields'][$this->settings['xmlId']]) && !empty($v['fields'][$this->settings['xmlId']]) )
                    $e->appendChild($this->dom->createElement('xmlId', $v['fields'][$this->settings['xmlId']]));

            $image = array(
                'product_id' => $v['product_id'],
                'id'         => ( !empty($v['image_id']) && $v['image_id'] != 0) ? $v['image_id'] : $product['image_id'],
                'ext'        => $product['ext']
            );

            if ( !empty($image['image_id']) && !empty($image['product_id']) )
                $e->appendChild($this->dom->createElement('picture', $this->getUrl($image)));

            $url = $this->settings['siteurl'];
            /*if (isset($this->settings["routing"]["catalog"]) && !empty($this->settings["routing"]["catalog"])) {
                $url .= $this->settings["routing"]["catalog"] . "/";
            }*/
            if ( isset($this->category[$category]) )
                $url .= $this->category[ $category ] . "/";

            $url .= $product['url'];
            $e->appendChild($this->dom->createElement('url', $url));

            $s = $this->settings;
            foreach ( array('article','size','color','weight','vendor') as $f )
                if ( isset($s[$f]) )
                if ( $sf = $this->check($s[$f]) ) {
                    if ( $value = $this->check($v['fields'][$sf]) ) {
                        if ( $f == 'vendor' ){
                            $e->appendChild($this->dom->createElement('vendor',$value));
                            continue;
                        }
                        $p = $this->dom->createElement('param');
                        $p->setAttribute('name',$f);
                        $p->appendChild($this->dom->createTextNode($value));
                        $e->appendChild($p);
                    }
                }
        }
    }


    protected function check($value)
    {
        return empty($value) ? false : $value;
    }
}