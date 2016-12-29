# php-circuit-breaker-bundle

[![Build Status](https://travis-ci.org/lemonde/php-circuit-breaker-bundle.svg?branch=master)](https://travis-ci.org/lemonde/php-circuit-breaker-bundle)
[![Build Status](https://scrutinizer-ci.com/g/lemonde/php-circuit-breaker-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lemonde/php-circuit-breaker-bundle/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lemonde/php-circuit-breaker-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lemonde/php-circuit-breaker-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/lemonde/php-circuit-breaker-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lemonde/php-circuit-breaker-bundle/?branch=master)

## Description

Everything is MIT licensed and unsupported unless otherwise stated. Use at your own risk.

Implement circuit breaker pattern see here http://martinfowler.com/bliki/CircuitBreaker.html.

You can find explanation in french here https://medium.com/eleven-labs/le-circuit-breaker-k%C3%A9sako-4763e13a4a03

## How to install ?

### Requirements

- >=PHP 7
- use LoggerInterface
- use CacheInterface

```bash
composer require lemonde/php-circuit-breaker-bundle:lastest
```

Add in your AppKernel.php

```php
public function registerBundles()
    {
        $bundles = [
            ....
            new CircuitBreakerBundle\CircuitBreakerBundle(),
        ];

        return $bundles;
    }
```

Add in your config.yml

```yml
circuit_breaker:
    # Number fail possible in circuit breaker
    threshold: 5
    # Time in seconds resend call after circuit breaker is open
    timeout: 20
```

And add config to CacheInterface in your config.yml

```yml
framework:
    cache:
        app: cache.adapter.filesystem
```


## How to use ?

Before your service's call

```php
if ($this->get('circuit.breaker')->isOpen('your-service')) {
    // If service is down
}
```

You need to send status on service to each call

```php
// $status is bool
$this->get('dispatcher')->dispatch('circuit.breaker', new CircuitBreakerEvent('your-service', $status));
```

## How to contribute ?

You can add issue, and create pull request. https://github.com/lemonde/php-circuit-breaker-bundle/blob/master/.github/CONTRIBUTING.md
