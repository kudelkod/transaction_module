<?php

namespace Drupal\transaction_module;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a class to build a listing of Transaction entities.
 *
 * @ingroup transaction_module
 */
class TransactionListBuilder extends EntityListBuilder {

  protected $dateFormatter;

  protected $renderer;

  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatter $date_formatter, RendererInterface $renderer) {
    parent::__construct($entity_type, $storage);

    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }


  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }


  public function buildHeader(){
    $header['id'] = $this->t('Linked Entity Label');
    $header['bundle_id'] = $this->t('Bundle');
    $header['owner'] = $this->t('Owner');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Changed');
    $header['sender'] = $this->t('Sender');
    $header['recipient'] = $this->t('Recipient');
    $header['amount'] = $this->t('Amount');

    return $header + parent::buildHeader();
  }


  public function buildRow(EntityInterface $entity) {

    $row['id'] = $entity->toLink($entity->label());
    $row['bundle_id'] = $entity->bundle();
    $row['owner'] = $entity->getOwner()->toLink($entity->getOwner()->label());
    $row['created'] = $this->dateFormatter->format($entity->getCreatedTime(), 'short');
    $row['changed'] = $this->dateFormatter->format($entity->getChangedTime(), 'short');
    $row['sender'] = $entity->sender->value;
    $row['recipient'] = $entity->recipient->value;
    $row['amount'] = $entity->amount->value . '$';

    return $row + parent::buildRow($entity);
  }

}
