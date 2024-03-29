<?php


class shopCrmretailPluginApiClient
{
    const VERSION = 'v4';

    protected $client;


    public function __construct($url, $apiKey)
    {
        $url = rtrim(trim($url),'/') . '/api/' . self::VERSION;

        $this->client = new shopCrmretailPluginClient($url, array('apiKey' => $apiKey));
    }


    public function ordersCreate(shopCrmretailPluginObjectOrder $order, $site)
    {
        return $this->client->makeRequest("/orders/create", shopCrmretailPluginClient::METHOD_POST, array(
            'order' => (string)$order,
            'site' => $site
        ));
    }


    public function ordersEdit(array $order, $by = 'externalId')
    {
        if (!sizeof($order)) {
            throw new InvalidArgumentException('Parameter `order` must contains a data');
        }

        $this->checkIdParameter($by);

        if (!isset($order[$by])) {
            throw new InvalidArgumentException(sprintf('Order array must contain the "%s" parameter.', $by));
        }

        return $this->client->makeRequest("/orders/" . $order[$by] . "/edit", Client::METHOD_POST, array(
            'order' => json_encode($order),
            'by' => $by,
        ));
    }

    /**
     * Upload array of the orders
     *
     * @param  array       $orders
     * @return ApiResponse
     */
    public function ordersUpload(array $orders)
    {
        if (!sizeof($orders)) {
            throw new InvalidArgumentException('Parameter `orders` must contains array of the orders');
        }

        return $this->client->makeRequest("/orders/upload", Client::METHOD_POST, array(
            'orders' => json_encode($orders),
        ));
    }

    /**
     * Get order by id or externalId
     *
     * @param  string      $id
     * @param  string      $by (default: 'externalId')
     * @return ApiResponse
     */
    public function ordersGet($id, $by = 'externalId')
    {
        $this->checkIdParameter($by);

        return $this->client->makeRequest("/orders/$id", Client::METHOD_GET, array('by' => $by));
    }

    /**
     * Returns a orders history
     *
     * @param  \DateTime   $startDate (default: null)
     * @param  \DateTime   $endDate (default: null)
     * @param  int         $limit (default: 100)
     * @param  int         $offset (default: 0)
     * @param  bool        $skipMyChanges (default: true)
     * @return ApiResponse
     */
    public function ordersHistory(
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        $limit = 100,
        $offset = 0,
        $skipMyChanges = true
    ) {
        $parameters = array();

        if ($startDate) {
            $parameters['startDate'] = $startDate->format('Y-m-d H:i:s');
        }
        if ($endDate) {
            $parameters['endDate'] = $endDate->format('Y-m-d H:i:s');
        }
        if ($limit) {
            $parameters['limit'] = (int) $limit;
        }
        if ($offset) {
            $parameters['offset'] = (int) $offset;
        }
        if ($skipMyChanges) {
            $parameters['skipMyChanges'] = (bool) $skipMyChanges;
        }

        return $this->client->makeRequest('/orders/history', Client::METHOD_GET, $parameters);
    }

    /**
     * Returns filtered orders list
     *
     * @param  array       $filter (default: array())
     * @param  int         $page (default: null)
     * @param  int         $limit (default: null)
     * @return ApiResponse
     */
    public function ordersList(array $filter = array(), $page = null, $limit = null)
    {
        $parameters = array();

        if (sizeof($filter)) {
            $parameters['filter'] = $filter;
        }
        if (null !== $page) {
            $parameters['page'] = (int) $page;
        }
        if (null !== $limit) {
            $parameters['limit'] = (int) $limit;
        }

        return $this->client->makeRequest('/orders', Client::METHOD_GET, $parameters);
    }

    /**
     * Returns statuses of the orders
     *
     * @param  array       $ids (default: array())
     * @param  array       $externalIds (default: array())
     * @return ApiResponse
     */
    public function ordersStatuses(array $ids = array(), array $externalIds = array())
    {
        $parameters = array();

        if (sizeof($ids)) {
            $parameters['ids'] = $ids;
        }
        if (sizeof($externalIds)) {
            $parameters['externalIds'] = $externalIds;
        }

        return $this->client->makeRequest('/orders/statuses', Client::METHOD_GET, $parameters);
    }

    /**
     * Save order IDs' (id and externalId) association in the CRM
     *
     * @param  array       $ids
     * @return ApiResponse
     */
    public function ordersFixExternalIds(array $ids)
    {
        if (!sizeof($ids)) {
            throw new InvalidArgumentException('Method parameter must contains at least one IDs pair');
        }

        return $this->client->makeRequest("/orders/fix-external-ids", Client::METHOD_POST, array(
            'orders' => json_encode($ids),
        ));
    }

    /**
     * Create a customer
     *
     * @param  array       $customer
     * @return ApiResponse
     */
    public function customersCreate(array $customer)
    {
        if (!sizeof($customer)) {
            throw new InvalidArgumentException('Parameter `customer` must contains a data');
        }

        return $this->client->makeRequest("/customers/create", Client::METHOD_POST, array(
            'customer' => json_encode($customer)
        ));
    }

    /**
     * Edit a customer
     *
     * @param  array       $customer
     * @return ApiResponse
     */
    public function customersEdit(array $customer, $by = 'externalId')
    {
        if (!sizeof($customer)) {
            throw new InvalidArgumentException('Parameter `customer` must contains a data');
        }

        $this->checkIdParameter($by);

        if (!isset($customer[$by])) {
            throw new InvalidArgumentException(sprintf('Customer array must contain the "%s" parameter.', $by));
        }

        return $this->client->makeRequest("/customers/" . $customer[$by] . "/edit", Client::METHOD_POST, array(
            'customer' => json_encode($customer),
            'by' => $by,
        ));
    }

    /**
     * Upload array of the customers
     *
     * @param  array       $customers
     * @return ApiResponse
     */
    public function customersUpload(array $customers)
    {
        if (!sizeof($customers)) {
            throw new InvalidArgumentException('Parameter `customers` must contains array of the customers');
        }

        return $this->client->makeRequest("/customers/upload", Client::METHOD_POST, array(
            'customers' => json_encode($customers),
        ));
    }

    /**
     * Get customer by id or externalId
     *
     * @param  string      $id
     * @param  string      $by (default: 'externalId')
     * @return ApiResponse
     */
    public function customersGet($id, $by = 'externalId')
    {
        $this->checkIdParameter($by);

        return $this->client->makeRequest("/customers/$id", Client::METHOD_GET, array('by' => $by));
    }

    /**
     * Returns filtered customers list
     *
     * @param  array       $filter (default: array())
     * @param  int         $page (default: null)
     * @param  int         $limit (default: null)
     * @return ApiResponse
     */
    public function customersList(array $filter = array(), $page = null, $limit = null)
    {
        $parameters = array();

        if (sizeof($filter)) {
            $parameters['filter'] = $filter;
        }
        if (null !== $page) {
            $parameters['page'] = (int) $page;
        }
        if (null !== $limit) {
            $parameters['limit'] = (int) $limit;
        }

        return $this->client->makeRequest('/customers', Client::METHOD_GET, $parameters);
    }

    /**
     * Save customer IDs' (id and externalId) association in the CRM
     *
     * @param  array       $ids
     * @return ApiResponse
     */
    public function customersFixExternalIds(array $ids)
    {
        if (!sizeof($ids)) {
            throw new InvalidArgumentException('Method parameter must contains at least one IDs pair');
        }

        return $this->client->makeRequest("/customers/fix-external-ids", Client::METHOD_POST, array(
            'customers' => json_encode($ids),
        ));
    }

    /**
     * Returns deliveryServices list
     *
     * @return ApiResponse
     */
    public function deliveryServicesList()
    {
        return $this->client->makeRequest('/reference/delivery-services', Client::METHOD_GET);
    }

    /**
     * Returns deliveryTypes list
     *
     * @return ApiResponse
     */
    public function deliveryTypesList()
    {
        return $this->client->makeRequest('/reference/delivery-types', Client::METHOD_GET);
    }

    /**
     * Returns orderMethods list
     *
     * @return ApiResponse
     */
    public function orderMethodsList()
    {
        return $this->client->makeRequest('/reference/order-methods', Client::METHOD_GET);
    }

    /**
     * Returns orderTypes list
     *
     * @return ApiResponse
     */
    public function orderTypesList()
    {
        return $this->client->makeRequest('/reference/order-types', Client::METHOD_GET);
    }

    /**
     * Returns paymentStatuses list
     *
     * @return ApiResponse
     */
    public function paymentStatusesList()
    {
        return $this->client->makeRequest('/reference/payment-statuses', Client::METHOD_GET);
    }

    /**
     * Returns paymentTypes list
     *
     * @return ApiResponse
     */
    public function paymentTypesList()
    {
        return $this->client->makeRequest('/reference/payment-types', Client::METHOD_GET);
    }

    /**
     * Returns productStatuses list
     *
     * @return ApiResponse
     */
    public function productStatusesList()
    {
        return $this->client->makeRequest('/reference/product-statuses', Client::METHOD_GET);
    }

    /**
     * Returns statusGroups list
     *
     * @return ApiResponse
     */
    public function statusGroupsList()
    {
        return $this->client->makeRequest('/reference/status-groups', Client::METHOD_GET);
    }

    /**
     * Returns statuses list
     *
     * @return ApiResponse
     */
    public function statusesList()
    {
        return $this->client->makeRequest('/reference/statuses', Client::METHOD_GET);
    }

    /**
     * Edit deliveryService
     *
     * @param array $data delivery service data
     * @return ApiResponse
     */
    public function deliveryServicesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/delivery-services/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'deliveryService' => json_encode($data)
            )
        );
    }

    /**
     * Edit deliveryType
     *
     * @param array $data delivery type data
     * @return ApiResponse
     */
    public function deliveryTypesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/delivery-types/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'deliveryType' => json_encode($data)
            )
        );
    }

    /**
     * Edit orderMethod
     *
     * @param array $data order method data
     * @return ApiResponse
     */
    public function orderMethodsEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/order-methods/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'orderMethod' => json_encode($data)
            )
        );
    }

    /**
     * Edit orderType
     *
     * @param array $data order type data
     * @return ApiResponse
     */
    public function orderTypesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/order-types/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'orderType' => json_encode($data)
            )
        );
    }

    /**
     * Edit paymentStatus
     *
     * @param array $data payment status data
     * @return ApiResponse
     */
    public function paymentStatusesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/payment-statuses/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'paymentStatus' => json_encode($data)
            )
        );
    }

    /**
     * Edit paymentType
     *
     * @param array $data payment type data
     * @return ApiResponse
     */
    public function paymentTypesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/payment-types/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'paymentType' => json_encode($data)
            )
        );
    }

    /**
     * Edit productStatus
     *
     * @param array $data product status data
     * @return ApiResponse
     */
    public function productStatusesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/product-statuses/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'productStatus' => json_encode($data)
            )
        );
    }

    /**
     * Edit order status
     *
     * @param array $data status data
     * @return ApiResponse
     */
    public function statusesEdit(array $data)
    {
        if (!isset($data['code'])) {
            throw new InvalidArgumentException('Data must contain "code" parameter.');
        }

        return $this->client->makeRequest(
            '/reference/statuses/' . $data['code'] . '/edit',
            Client::METHOD_POST,
            array(
                'status' => json_encode($data)
            )
        );
    }

    /**
     * Update CRM basic statistic
     *
     * @return ApiResponse
     */
    public function statisticUpdate()
    {
        return $this->client->makeRequest('/statistic/update', Client::METHOD_GET);
    }

    /**
     * Check ID parameter
     *
     * @param  string $by
     * @return bool
     */
    protected function checkIdParameter($by)
    {
        $allowedForBy = array('externalId', 'id');
        if (!in_array($by, $allowedForBy)) {
            throw new InvalidArgumentException(sprintf(
                'Value "%s" for parameter "by" is not valid. Allowed values are %s.',
                $by,
                implode(', ', $allowedForBy)
            ));
        }

        return true;
    }
}
