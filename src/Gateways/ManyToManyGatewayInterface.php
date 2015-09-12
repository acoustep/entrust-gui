<?php namespace Acoustep\EntrustGui\Gateways;

interface ManyToManyGatewayInterface
{
  
    public function getModelName();

    public function getRelationName();

    public function getShortRelationName();
}
