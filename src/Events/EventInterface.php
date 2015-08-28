<?php namespace Acoustep\EntrustGui\Events;

interface EventInterface
{
  public function __construct();

  public function setModel($model);

}
