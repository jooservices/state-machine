# Multiple State Machines

One subject can host multiple independent graphs by creating multiple machines with different properties:

```php
$payment = $factory->create($order, 'payment', $paymentConfig);
$fulfillment = $factory->create($order, 'fulfillment', $fulfillmentConfig);
```

There is no single multi-graph container object in the package.
