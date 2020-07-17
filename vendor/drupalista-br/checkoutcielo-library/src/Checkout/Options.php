<?php

namespace CieloCheckout;

class Options extends Commons {

  public
    $AntifraudEnabled,
    $ReturnUrl;
  protected $property_requirements = [
    'AntifraudEnabled' => [
      'is_bool' => [],
    ],
    'ReturnUrl' => [
      'empty' => ['negate' => FALSE],
    ]
  ];
}
