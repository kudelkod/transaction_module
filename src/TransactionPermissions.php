<?php

namespace Drupal\transaction_module;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\transaction_module\Entity\Transaction;


/**
 * Provides dynamic permissions for Transaction of different types.
 *
 * @ingroup transaction_module
 *
 */
class TransactionPermissions{

  use StringTranslationTrait;

  /**
   * Returns an array of node type permissions.
   *
   * @return array
   *   The Transaction by bundle permissions.
   *   @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function generatePermissions() {
    return [

      "view transaction" => [
        'title' => $this->t('View all Transaction')
      ],

      "edit transaction" => [
        'title' => $this->t('Edit Transaction')
      ],

      "create new transaction" => [
        'title' => $this->t("Create new Transaction"),
      ]

    ];
  }

  /**
   * Returns a list of node permissions for a given node type.
   *
   * @param \Drupal\transaction_module\Entity\Transaction $type
   *   The Transaction type.
   *
   * @return array
   *   An associative array of permission names and descriptions.
   */
  protected function buildPermissions(Transaction $type) {
    $type_id = $type->id();
    $type_params = ['%type_name' => $type->label()];

    return [
      "$type_id create entities" => [
        'title' => $this->t('Create new %type_name entities', $type_params),
      ],
      "$type_id edit own entities" => [
        'title' => $this->t('Edit own %type_name entities', $type_params),
      ],
      "$type_id edit any entities" => [
        'title' => $this->t('Edit any %type_name entities', $type_params),
      ],
      "$type_id delete own entities" => [
        'title' => $this->t('Delete own %type_name entities', $type_params),
      ],
      "$type_id delete any entities" => [
        'title' => $this->t('Delete any %type_name entities', $type_params),
      ],
    ];
  }

}
